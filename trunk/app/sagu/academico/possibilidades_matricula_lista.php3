<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<?

   CheckFormParameters(array("curso_id",
                              "campus_id",
                              "periodo_id"));
   $conn = new Connection;
   $conn->open();

   $curso_id = trim($curso_id);
   $campus_id = trim($campus_id);

   $sql = " select ref_disciplina, " .
          "        descricao_disciplina(ref_disciplina), " .
          "        count(*) " .
          " from disciplinas_todos_alunos " .
          " where ref_curso='$curso_id' and " .
          "       ref_campus='$campus_id' and " .
          "       status=1 " .
          " group by ref_disciplina " .
          " order by ref_disciplina; ";

   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $i=1;

   $curso_desc = GetField($curso_id, "abreviatura", "cursos", true);

   while( $query->MoveNext() )
   {
     list ( $ref_disciplina,
            $disciplina,
            $num ) = $query->GetRowValues();
 
     $href  = "<a href=\"lista_alunos_possibilidades.php3?ref_curso=$curso_id&ref_campus=$campus_id&ref_disciplina=$ref_disciplina&status=1\">$disciplina</a>";
 
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Possibilidades de Matrícula $periodo_id</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $curso_desc . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Campus: " . $campus_id . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num Alunos</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
          echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
          echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");
          echo ("<td width=\"10%\"  align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num</td>");
          echo("  </tr>\n");
         }

     $i++;

   }

   echo("<tr><td colspan=\"3\"><hr></td></tr>");
   echo("</table>");

?>
</head>
<form method="post" action="" name="myform">
<div align="center">
  <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
</div>
</form>
</body>
</html
