<? require("../../../../lib/common.php"); ?>
<?
CheckFormParameters(array('periodo_id',
			  'curso_id',
			  'campus_id'));
?>
<html>
<head>
<title>Lista de Alunos que Reprovaram Disciplinas</title>

<script language="PHP">
function Lista_Alunos($periodo_id, $curso_id, $campus_id, $nota_zero)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select ref_pessoa, " .
          "        pessoa_nome(ref_pessoa), " .
	  "        ref_disciplina, " .
	  "        descricao_disciplina(ref_disciplina), " .
	  "        professor_disciplina_ofer(ref_disciplina_ofer), " .
	  "        nota_final, " .
	  "        curso_desc(ref_curso), " .
	  "        ref_periodo " .
	  " from matricula " .
	  " where ref_periodo <> '$periodo_id' and " .
	  "       ref_curso = '$curso_id' and " .
	  "       ref_campus = '$campus_id' and " .
	  "       dt_cancelamento is null and " .
	  "       is_matriculado('$periodo_id', ref_pessoa) = ref_pessoa and ";
	  
	  if (empty($nota_zero))
	  {
	  $sql = $sql . " ((nota_final < '5' and nota_final <> '0' and ";
          }
	  else
	  {
	  $sql = $sql . " ((nota_final = '0' and ";
	  }
	  
	  $sql = $sql . " trim(fl_liberado) = '') or (fl_liberado = '1'))" .
	  " order by ref_periodo, ref_pessoa;";
 

   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;
   $total = 0;

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
           $nome_professor,
           $nota_final,
           $nome_curso,
           $ref_periodo) = $query->GetRowValues();

     $nota_final = sprintf("%.2f", $nota_final);

     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Listagem de Alunos que Reprovaram em Disciplinas, exceto o período $periodo_id</b></center></font></td>");
	 echo("</tr>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Curso: $curso_id - $nome_curso &nbsp;&nbsp;&nbsp; Campus:$campus_id</center></font></td>");
	 echo("</tr>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
         echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Aluno</b></font></td>");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
         echo ("<td width=\"24%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Período</b></font></td>");
         echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Professor</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nota</b></font></td>");
         echo ("  </tr>"); 
      }
      
     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
          echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
          echo ("<td width=\"24%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_disciplina</td>");
          echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_periodo</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_professor&nbsp;</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nota_final</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
          echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
          echo ("<td width=\"24%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_disciplina</td>");
          echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_periodo</td>");
          echo ("<td width=\"25%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_professor&nbsp;</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nota_final</td>");
          echo("  </tr>\n");
         }
     $i++;
     $total++;
   }
   echo("</tr>");
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"6\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Alunos:</b></font></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total alunos</b></font></td>");
   echo ("  </tr>"); 
  
   echo ("<tr><td colspan=7 align=center><hr size=1>" .
         "<input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\">" .
         "</td></tr>\n");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Alunos($periodo_id, $curso_id, $campus_id, $nota_zero);
  </script>
</form>
</body>
</html>
