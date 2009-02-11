<?
  //----------------------------------------------------------------------------------
  // Converte data do formato ddmmaa para dd/mm/aa
  //----------------------------------------------------------------------------------
  Function Cdata($dt)
  {
    $obj = substr($dt, 0, 2) . "/";
    $obj = $obj . substr($dt, 2, 2) . "/";
    $obj = $obj . substr($dt, 4, 2);
  
    return $obj;
  }

  //----------------------------------------------------------------------------------
  // Converte data do formato ddmmaa para mm/dd/aa
  //----------------------------------------------------------------------------------
  Function Cdata0($dt)
  {
    $obj = substr($dt, 2, 2) . "/";
    $obj = $obj . substr($dt, 0, 2) . "/20";
    $obj = $obj . substr($dt, 4, 2);
  
    return $obj;
  }


  //----------------------------------------------------------------------------------
  // Converte data do formato ddmmaa para dd/mm/aaaa
  //----------------------------------------------------------------------------------
  Function Cdata1($dt)
  {
    $obj = substr($dt, 0, 2) . "/";
    $obj = $obj . substr($dt, 2, 2) . "/20";
    $obj = $obj . substr($dt, 4, 2);

    return $obj;
  }

  //----------------------------------------------------------------------------------
  // Converte data do formato aaaa/mm/dd para ddmmaa
  //----------------------------------------------------------------------------------
  Function Cdata2($dt)
  {
    $obj = substr($dt, 8, 2); //dd
    $obj = $obj . substr($dt, 5, 2); //mm
    $obj = $obj . substr($dt, 2, 2); //aa

    return $obj;
  }


  //----------------------------------------------------------------------------------
  // Coloca um caractere de separacao em uma string
  // recebe o texto e a posicao do carectere anterior
  //----------------------------------------------------------------------------------
  Function InsCaract($texto, $pos, $str)
  {
    $tam = strlen($texto);

    $obj = substr($texto, 0, $pos) . $str;
    $obj = $obj . substr($texto, $pos, $tam-$pos);

    return $obj;
  }

  // -----------------------------------------------------------
  // Ve se pode processar o a ocorrencia
  // -----------------------------------------------------------
  Function Is_proc($conn, $ocorrencia, $local)
  {
    $sql = " select fl_processa_entr " .
           " from ocorr_locais_pgto " .
           " where ocorrencia='$ocorrencia' and " .
           "       ref_local = '$local'; ";
  
    $query = $conn->CreateQuery($sql);
  
    if ( @$query->MoveNext() )
    {
      $proc = $query->GetValue(1);    
    }
 
    $query->Close();

    return $proc;
  }

  Function Get_Ocorr($conn, $ocorrencia, $local)
  {
    $sql = " select descricao " .
           " from ocorr_locais_pgto " .
           " where ocorrencia='$ocorrencia' and " .
           "       ref_local = '$local'; ";
  
    $query = $conn->CreateQuery($sql);
  
    if ( @$query->MoveNext() )
    {
      $descricao = $query->GetValue(1);    
    }
 
    $query->Close();

    return $descricao;
  }

  // -----------------------------------------------------------
  // Retorna campo determinado
  // -----------------------------------------------------------
  Function Pega_hist($conn, $ocorrencia, $local, $ind)
  {
    $sql = " select hist_val_tar, " .
           "        hist_out_tar, " .
           "        hist_jur_desc, " .
           "        hist_iof_desc, " .
           "        hist_abat, " .
           "        hist_desc_conced, " .
           "        hist_val_lcto, " .
           "        hist_juros_mora, " .
           "        hist_outros_receb, " .
           "        hist_abat_n_aprov, " .
           "        hist_val_lcto " .
           " from ocorr_locais_pgto " .
           " where ocorrencia='$ocorrencia' and " .
           "       ref_local = '$local'; ";
    
    $query = $conn->CreateQuery($sql);
  
    if ( @$query->MoveNext() )
    {
      $proc = $query->GetValue($ind); 
    }

    $query->Close();

    return $proc;
  }

  // -----------------------------------------------------------
  // Retorna campo determinado
  // -----------------------------------------------------------
  Function Calcula_Saldo($num_titulo)
  {

      $conn = new Connection;

      $conn->Open();

      $sql = " select " .
       "       A.ref_historico, " .
       "       A.valor_lancto, " .
       "       B.operacao " .
       "  from lancamentos_cr A, historicos B" .
       "  where A.ref_titulo='$num_titulo' and " .
       "        A.ref_historico=b.id";

       $query = $conn->CreateQuery($sql);

       $saldo = 0;

       while ($query->MoveNext())
       {
           list( $ref_historico,
                 $valor_lancto,
                 $operacao )= $query->GetRowValues();
            
           if ($operacao=='C') 
     	   {
             $saldo = $saldo - $valor_lancto;
	       }  
	       else
	       {
             $saldo = $saldo + $valor_lancto;
	       }
       }

      $saldo = $saldo*100;
      settype($saldo, "integer");
      $saldo = $saldo/100;

      return $saldo; 

  }

//============================================================================
// Funcao que retorna uma string com os lancamentos de um titulo para ser 
// incorporada ao arquivo que vai para o banco
//============================================================================

Function Ret_Lancamentos_Banco($num_titulo)
{

  $tracos= "--------------------------------------------------" .  //  50
           "--------------------------------------------------" .  // 100
           "--------------------------------------------------" .  // 150
           "--------------------------------------------------" .  // 200
           "--------------------------------------------------" .  // 250
           "--------------------------------------------------" .  // 300
           "--------------------------------------------------" .  // 350
           "--------------------------------------------------" ;  // 400


  $conn = new Connection;
  $conn->open();

  $sql = " select A.ref_titulo, " . 
         "        A.ref_historico, " . 
         "        B.descricao, " . 
         "        A.valor_lancto " . 
         " from lancamentos_cr A, historicos B  " . 
         " where A.ref_titulo='$num_titulo' and " . 
         "       A.ref_historico=B.id; " ; 

  $query = $conn->CreateQuery($sql);

  while ($query->MoveNext())
  {
    list ($ref_titulo,
          $ref_historico,
          $descricao,
          $valor_lancto) = $query->GetRowValues();    
     
    $x = "$ref_historico - $descricao";
    $kk = 91 - (strlen($x) + strlen($valor_lancto));
    $aux = $x . substr($tracos, 0, $kk);
    $aux = $aux . $valor_lancto;

    $linha = $linha . $aux;

  } // while

  return $linha;
 
} // function
?>
