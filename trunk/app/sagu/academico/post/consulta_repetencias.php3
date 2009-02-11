<? require("../../../../lib/common.php"); ?>
<?
CheckFormParameters(array('periodo_id'));
?>
<html>
<head>
<title>Lista de Alunos que estão repetindo disciplinas - Ordem de Aluno</title>

<script language="PHP">
function Lista_Alunos($periodo_id)
{
    $conn = new Connection;

    $conn->open();

    $sql = " select A.ref_pessoa, " .
           "        pessoa_nome(A.ref_pessoa), " .
           "        A.ref_disciplina, " .
	       "        descricao_disciplina(A.ref_disciplina), " .
           "        get_creditos(A.ref_disciplina), " .
           "        A.ref_periodo, " .
           "        A.nota_final " .
           " from matricula A, " .
           "      (select ref_pessoa, ref_disciplina " .
           "       from matricula " .
           "       where ref_periodo = '$periodo_id' and " .
           "             dt_cancelamento is null and " .
           "             (fl_liberado = '' or fl_liberado = '1')) as B " .
           " where A.ref_pessoa = B.ref_pessoa and " .
           "       A.ref_disciplina = B.ref_disciplina and " .
           "       A.nota_final > 0 and " .
           "       nota_final < get_media_final(ref_periodo) and " .
           "       A.fl_liberado <> '2' and " .
           "       A.ref_periodo <> '$periodo_id' and " .
           "       A.dt_cancelamento is null " .
           " order by pessoa_nome(A.ref_pessoa), descricao_disciplina(A.ref_disciplina);";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;
   $total_alunos = 0;
   
   $total_disciplinas = 0;
   $total_creditos = 0;
   
   $total_disciplinas_distintas = 0;
   $total_creditos_distintos = 0;
   
   $total_disciplinas_distintas_relat = 0;
   $total_creditos_distintos_relat = 0;
   $array_disciplinas = null;

   $aux_ref_pessoa = 0;
   $aux_ref_disciplina = 0;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";
   
   while( $query->MoveNext() )
   {
     list ($ref_pessoa,
           $nome_pessoa,
           $ref_disciplina,
           $nome_disciplina,
           $num_creditos,
           $ref_periodo,
           $nota_final) = $query->GetRowValues();
        
        $nota_final = sprintf("%.2f", $nota_final);

        if ($i == 1)
        {
	        echo("<tr>");
            echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"5\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Listagem de Alunos que estão repetindo disciplinas em $periodo_id</b></center></font></td>");
	        echo("</tr>");
            echo ("<tr bgcolor=\"#000000\">\n");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
            echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
            echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>C.H.</b></font></td>");
            echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Reprovou em</b></font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nota Final</b></font></td>");
            echo ("  </tr>"); 

            $array_disciplinas[] = $ref_disciplina; 
            $total_disciplinas_distintas_relat++;
            $total_creditos_distintos_relat = $total_creditos_distintos_relat + $num_creditos;
        }
        elseif (!in_array($ref_disciplina, $array_disciplinas))
        {
           $total_disciplinas_distintas_relat++;
           $total_creditos_distintos_relat = $total_creditos_distintos_relat + $num_creditos;
           $array_disciplinas[] = $ref_disciplina; 
        }

        if ($ref_pessoa != $aux_ref_pessoa)
        {
            $aux_ref_pessoa = $ref_pessoa;

            // Primeira disciplina da pessoa
            $total_disciplinas_distintas++;
            $total_creditos_distintos = $total_creditos_distintos + $num_creditos;
            $aux_ref_disciplina = $ref_disciplina;
                                    
            echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");
            echo("<tr bgcolor=\"$bg\">\n");
            echo ("<td colspan=\"5\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"blue\"><b>Aluno: $ref_pessoa - $nome_pessoa</b></td>");
            echo("</tr>");
            $total_alunos++;
        }

        if (($ref_disciplina != $aux_ref_disciplina) && ($ref_pessoa == $aux_ref_pessoa))
        {
            $aux_ref_disciplina = $ref_disciplina;
            $total_disciplinas_distintas++;
            $total_creditos_distintos = $total_creditos_distintos + $num_creditos;
        }
 
        if ( $i % 2 )
        {
            $bg = $bg1;
            $fg = $fg1;
        }
        else
        {
            $bg = $bg2;
            $fg = $fg2;
        }
       
        echo("<tr bgcolor=\"$bg\">\n");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
        echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome_disciplina</td>");
        echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_creditos</td>");
        echo ("<td width=\"15%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_periodo</td>");
        echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$nota_final</td>");
        echo("</tr>");
        
        $i++;
        $total_disciplinas++;
        $total_creditos = $total_creditos + $num_creditos;
   }
   
   echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Alunos:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_alunos alunos</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Disciplinas:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_disciplinas disciplinas</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Somatório dos créditos das $total_disciplinas disciplinas:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_creditos créditos</b></font></td>");
   echo ("</tr>"); 

   echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Disciplinas distintas por aluno:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_disciplinas_distintas disciplinas</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Somatório dos créditos das $total_disciplinas_distintas disciplinas distintas por aluno:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_creditos_distintos créditos</b></font></td>");
   echo ("</tr>"); 

   echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Disciplinas distintas do relatório:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_disciplinas_distintas_relat disciplinas</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Somatório dos créditos das $total_disciplinas_distintas_relat disciplinas distintas do relatório:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_creditos_distintos_relat créditos</b></font></td>");
   echo ("</tr>"); 

   echo ("<tr><td colspan=\"5\" align=center>");
   echo ("<hr size=1>");
   echo ("<input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\">");
   echo ("</td></tr>\n");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Alunos($periodo_id);
  </script>
</form>
</body>
</html>
