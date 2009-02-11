<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Relatório de Financeiro</title>

<?
  CheckFormParameters(array("periodo_id","campus_id","dt_inicial","dt_final"));
  $dt_inicial = InvData($dt_inicial);
  $dt_final   = InvData($dt_final);

function gera_relatorio($periodo_id, $dt_inicial, $dt_final, $campus_id)
{
   $conn = new Connection;
   $conn->open();

   $sql = " select nome_campus from campus where id = '$campus_id';";
   
   $query = $conn->CreateQuery($sql);
  
   if ( $query->MoveNext() )
   {
       list($nome_campus) = $query->GetRowValues();
   }
   else
   {
       SaguAssert(0, "Não foi possível consultar nome do campus!!!");
   }
   
   $query->Close();
   
   // Matriculas até a dt_inicial
   
   $sql = " select B.cod_ccusto, " .
          "        get_descr_ccusto(B.cod_ccusto), " .
          "        count(distinct A.ref_pessoa), " .
          "        num_creditos_curso_data(B.cod_ccusto,'$campus_id','$periodo_id','$dt_inicial') " .
          " from matricula A, rel_curso_cc B " .
          " where A.ref_curso = B.ref_curso and " .
          "       A.ref_campus = B.ref_campus and " .
          "       A.ref_periodo = '$periodo_id' and " .
          "       A.ref_campus = '$campus_id' and " .
          "       get_curriculo_mco(A.ref_curso, A.ref_campus, A.ref_disciplina) <> 'A' and " .
          "       (A.dt_cancelamento is null or A.dt_cancelamento >= '$dt_inicial') and " .
          "       A.dt_matricula <= '$dt_inicial' " .
          " group by B.cod_ccusto;";

   $query = $conn->CreateQuery($sql);

   // cores fundo
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg1 = "#000099";
   $fg2 = "#000099";


   echo ("<br>");    
   echo ("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr>");    
   echo ("<td bgcolor=\"#000099\" colspan=\"10\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>EXTRATO DE MATRÍCULAS<br>Campus - $campus_id - $nome_campus<br>(" . InvData($dt_inicial) . " até " . InvData($dt_final) . ")</b></center></font></td>");
   echo ("</tr>");    
   
   echo ("<tr bgcolor=\"#000099\"><td colspan=\"10\">&nbsp;</td></tr>");    

   echo ("<tr bgcolor=\"#000099\">\n");
   echo ("<td width=\"30%\" colspan=\"2\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Curso</b></td>");
   echo ("<td width=\"17%\" colspan=\"2\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Dia " . InvData($dt_inicial) . "</b></td>");
   echo ("<td width=\"17%\" colspan=\"2\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>&nbsp;&nbsp;Saídas</b></td>");
   echo ("<td width=\"17%\" colspan=\"2\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Entradas</b></td>");
   echo ("<td width=\"19%\" colspan=\"2\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Dia " . InvData($dt_final) . "</b></td>");
   echo ("</tr>");

   echo ("<tr bgcolor=\"#000099\"><td colspan=\"10\">&nbsp;</td></tr>");    
   
   echo ("<tr bgcolor=\"#000099\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>CCusto</b></td>");
   echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Curso</b></td>");
   echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Alunos</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Créditos</b></td>");
   echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Alunos com Transações</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Créditos</b></td>");
   echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Alunos com Transações</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Créditos</b></td>");
   echo ("<td width=\"9%\" align=\"right\" ><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Alunos</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>Créditos</b></td>");
   echo ("</tr>");

   $extrato = "EXTRATO DE MATRÍCULAS (" . InvData($dt_inicial) . " até " . InvData($dt_final) . ") Campus - $campus_id - $nome_campus\n\n";
   $extrato .= "Centro de Custo, Descrição do Curso, Alunos Início, Créditos Início, Alunos com Transações de Saída, Créditos Saída, Alunos Com Transações de Entrada, Créditos Entrada, Saldo Alunos, Saldo Créditos\n";

   $num_total_alunos = 0;
   $num_total_creditos = 0;
   $num_total_alunos_saida =  0;
   $num_total_creditos_saida = 0;
   $num_total_alunos_entrada = 0;
   $num_total_creditos_entrada = 0;
   $soma_total_creditos = 0;
   $soma_total_alunos = 0;

   $i = 0;
   
   while( $query->MoveNext() )
   {
       list ($cod_ccusto,
             $curso_descricao,
             $num_alunos,
             $num_creditos) = $query->GetRowValues();

       $num_alunos_saida = 0;
       $num_creditos_saida = 0;

       $sql_saida = " select count(distinct A.ref_pessoa), " .
                    "        num_creditos_curso_saida(B.cod_ccusto,'$campus_id','$periodo_id','$dt_inicial','$dt_final') " .
                    " from matricula A, rel_curso_cc B " .
                    " where A.ref_curso = B.ref_curso and " .
                    "       A.ref_campus = B.ref_campus and " .
                    "       A.ref_periodo = '$periodo_id' and " .
                    "       B.cod_ccusto = '$cod_ccusto' and " .
                    "       A.ref_campus = '$campus_id' and " .
                    "       get_curriculo_mco(A.ref_curso, A.ref_campus, A.ref_disciplina) <> 'A' and " .
                    "       A.dt_cancelamento >= '$dt_inicial' and " .
                    "       A.dt_cancelamento <= '$dt_final' " .
                    " group by B.cod_ccusto;";
       
       $query_saida = $conn->CreateQuery($sql_saida);

       while( $query_saida->MoveNext() )
       {
           list ($num_alunos_saida,
                 $num_creditos_saida) = $query_saida->GetRowValues();
       }

       $num_alunos_entrada = 0;
       $num_creditos_entrada = 0;
       
       $sql_entrada = " select count(distinct A.ref_pessoa), " .
                      "        num_creditos_curso_entrada(B.cod_ccusto,'$campus_id','$periodo_id','$dt_inicial','$dt_final') " .
                      " from matricula A, rel_curso_cc B " .
                      " where A.ref_curso = B.ref_curso and " .
                      "       A.ref_campus = B.ref_campus and " .
                      "       A.ref_periodo = '$periodo_id' and " .
                      "       B.cod_ccusto = '$cod_ccusto' and " .
                      "       A.ref_campus = '$campus_id' and " .
                      "       get_curriculo_mco(A.ref_curso, A.ref_campus, A.ref_disciplina) <> 'A' and " .
                      "       A.dt_matricula >= '$dt_inicial' and " .
                      "       A.dt_matricula <= '$dt_final' " .
                      " group by B.cod_ccusto;";
       
       $query_entrada = $conn->CreateQuery($sql_entrada);

       while( $query_entrada->MoveNext() )
       {
           list ($num_alunos_entrada,
                 $num_creditos_entrada) = $query_entrada->GetRowValues();
       }
        
       $sql_final = " select count(distinct A.ref_pessoa) " .
                    " from matricula A, rel_curso_cc B " .
                    " where A.ref_curso = B.ref_curso and " .
                    "       A.ref_campus = B.ref_campus and " .
                    "       A.ref_periodo = '$periodo_id' and " .
                    "       B.cod_ccusto = '$cod_ccusto' and " .
                    "       A.ref_campus = '$campus_id' and " .
                    "       get_curriculo_mco(A.ref_curso, A.ref_campus, A.ref_disciplina) <> 'A' and " .
                    "       (A.dt_cancelamento is null or A.dt_cancelamento >= '$dt_final') and " .
                    "       A.dt_matricula <= '$dt_final' " .
                    " group by B.cod_ccusto;";
    
       $query_final = $conn->CreateQuery($sql_final);

       while( $query_final->MoveNext() )
       {
           list ($num_total_alunos_curso) = $query_final->GetRowValues();
       }

       $num_creditos = sprintf("%.2f", $num_creditos);
       $num_creditos_saida = sprintf("%.2f", $num_creditos_saida);
       $num_creditos_entrada = sprintf("%.2f", $num_creditos_entrada); 

       $num_total_creditos_curso = $num_creditos + $num_creditos_entrada - $num_creditos_saida;
       $num_total_creditos_curso = sprintf("%.2f", $num_total_creditos_curso);

       if ( $i % 2 )
       {
         echo ("<tr bgcolor=\"$bg1\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$cod_ccusto</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$curso_descricao</td>");
         echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num_alunos</td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num_creditos</td>");
         echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$num_alunos_saida</td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$num_creditos_saida</td>");
         echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num_alunos_entrada</td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num_creditos_entrada</td>");
         echo ("<td width=\"9%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$num_total_alunos_curso</b></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$num_total_creditos_curso</b></td>");
         echo ("</tr>");
       }
       else
       {
         echo ("<tr bgcolor=\"$bg2\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$cod_ccusto</td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$curso_descricao</td>");
         echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num_alunos</td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num_creditos</td>");
         echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$num_alunos_saida</td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$num_creditos_saida</td>");
         echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num_alunos_entrada</td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num_creditos_entrada</td>");
         echo ("<td width=\"9%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\"><b>$num_total_alunos_curso</b></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\"><b>$num_total_creditos_curso</b></td>");
         echo ("</tr>");
       }
   
       $extrato .= "$cod_ccusto, $curso_descricao, $num_alunos, $num_creditos, $num_alunos_saida, $num_creditos_saida, $num_alunos_entrada, $num_creditos_entrada, $num_total_alunos_curso, $num_total_creditos_curso\n";
   
   
       $num_total_alunos += $num_alunos;
       $num_total_creditos += $num_creditos;
       $num_total_alunos_saida +=  $num_alunos_saida;
       $num_total_creditos_saida += $num_creditos_saida;
       $num_total_alunos_entrada += $num_alunos_entrada;
       $num_total_creditos_entrada += $num_creditos_entrada;
       $soma_total_creditos += $num_total_creditos_curso;
       $soma_total_alunos += $num_total_alunos_curso;
   
       $i++;
   
   }

   $num_total_creditos = sprintf("%.2f", $num_total_creditos);
   $num_total_creditos_saida = sprintf("%.2f", $num_total_creditos_saida);
   $num_total_creditos_entrada = sprintf("%.2f", $num_total_creditos_entrada);
   $soma_total_creditos = sprintf("%.2f", $soma_total_creditos);
   
   echo ("<tr bgcolor=\"#000099\">\n");
   echo ("<td width=\"30%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;</td>");
   echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$num_total_alunos</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$num_total_creditos</b></td>");
   echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$num_total_alunos_saida</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$num_total_creditos_saida</b></td>");
   echo ("<td width=\"7%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$num_total_alunos_entrada</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$num_total_creditos_entrada</b></td>");
   echo ("<td width=\"9%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>&nbsp;$soma_total_alunos</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#FFFFFF\"><b>$soma_total_creditos</b></td>");
   echo ("</tr>");
   
   $extrato .= "SOMA TOTAL,, $num_total_alunos, $num_total_creditos, $num_total_alunos_saida, $num_total_creditos_saida, $num_total_alunos_entrada, $num_total_creditos_entrada, $soma_total_alunos, $soma_total_creditos\n";
 
   echo("</table></center>");

   $filename = 'extrato_matricula.txt';
   $fp = fopen($filename, "w");
   fwrite($fp, $extrato);
   fclose($fp);
?>
   <br><br>
   <center><a href="<? echo($filename); ?>"> Visualizar Arquivo Texto </a></center>
   <br><br>
<?
   $query->Close();
   @$conn->Close();
}
?>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="" name="myform">
<?
   gera_relatorio($periodo_id, $dt_inicial, $dt_final, $campus_id);
?>
</form>
</body>
</html>
