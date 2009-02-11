<script language="PHP">

function calc_dv($ag_cedente, $cod_cedente, $ano, $byte, $seq)
{
   $aux_calc = $ag_cedente . $cod_cedente . $ano . $byte . $seq;
    
   $tam = strlen($aux_calc);
   $tam--;
   $indice = 2;

   for ($x=$tam; $x>=0; $x--)
   {
       $dig = substr($aux_calc, $x, 1) * $indice;
       $soma += $dig;

       $indice++;
       if($indice>9)
       {
           $indice = 2;
       }
   }

   $resto = 11 - ($soma % 11);

   if( ($resto == 10) || ($resto == 11) )
   {
       $resto = 0;
   }

   return $resto;
}

function gera_num_banco($ag_cedente, $cod_cedente, $num_titulo, $conn)
{
   $brancos = "                                                  " .  //  50
              "                                                  " .  // 100
              "                                                  " .  // 150
              "                                                  " .  // 200
              "                                                  " .  // 250
              "                                                  " .  // 300
              "                                                  " .  // 350
              "                                                  " ;  // 400
 
   $zeros = "00000000000000000000000000000000000000000000000000" ; // 50

   $sql = " select cod_banco " .
          " from titulos_cr " .
          " where id = '$num_titulo'";

   $query_num = $conn->CreateQuery($sql);
   
   if( $query_num->MoveNext() )
   {
       $num_banco = $query_num->GetValue(1);
   }
   $query_num->Close();

   if (trim($num_banco) == '')
   {
       $sql = " select id, " .
              "        byte_ctrl, " .
              "        ano " .
              " from sequencial_banco";
    
       $query_gera = $conn->CreateQuery($sql);
    
       if( $query_gera->MoveNext() )
       {
           $seq          = $query_gera->GetValue(1);
           $byte         = $query_gera->GetValue(2);
           $ano_corrente = $query_gera->GetValue(3);
           $seq          = substr($zeros, 0, 5-strlen($seq)) . $seq;
       }
       
       $query_gera->Close();
       
       if($ano_corrente == date(Y) )
       {
           if($seq == 99999)
           {
               $aux_seq = 0;
               $byte++;
           }
           else
           {
               $aux_seq = $seq + 1;
           }
       }
       else
       {
           $aux_seq      = 0;
           $byte         = 2;
           $ano_corrente = date(Y);
       }
    
       $sql = " update sequencial_banco set " .
              "     id = '$aux_seq', " .
              "     byte_ctrl = '$byte', " .
              "     ano = '$ano_corrente';";
              
       $ok = $conn->Execute($sql);
       
       $ano = substr($ano_corrente, 2, 2);
       $dig_ver = calc_dv($ag_cedente, $cod_cedente, $ano, $byte, $seq);
   
       $seq = substr($zeros, 0, 5-strlen($seq)) . $seq;
        
       $num_banco = $ano . $byte . $seq . $dig_ver;
        
       $sql = " update titulos_cr set " .
              "     cod_banco = '$num_banco', " .
              "     dt_remessa = date(now()) " .
              " where id = '$num_titulo'";
    
       $ok = $conn->Execute($sql);
   
   }
   
   return $num_banco;
}
</script>
