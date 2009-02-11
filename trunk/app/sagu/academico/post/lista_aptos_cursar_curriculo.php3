<? require("../../../../lib/common.php"); ?>
<? require("../../lib/GetField.php3"); ?>
<html>
<head>
<title>Untitled Document</title>
<script language="PHP">
function ListaAlunos($ref_curso, $ref_campus, $ref_disciplina, $ref_periodo)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select A.ref_pessoa, " .
          "        pessoa_nome(A.ref_pessoa),  " .
          "        pessoa_fone(A.ref_pessoa), " .
          "        descricao_disciplina($ref_disciplina) " .
          " from disciplinas_todos_alunos A, contratos B " .
          " where A.ref_curso = B.ref_curso and " .
          "       A.ref_campus = B.ref_campus and " .
          "       A.ref_pessoa = B.ref_pessoa and " .
          "       A.ref_disciplina = '$ref_disciplina' and " .
          "       A.ref_curso = '$ref_curso' and " .
          "       A.ref_campus = '$ref_campus' and " .
          "       B.ref_last_periodo = '$ref_periodo' and " .
          "       B.dt_desativacao is null and " .
          "       A.status = '1' " .
          " order by pessoa_nome(A.ref_pessoa);";
  
   $query = $conn->CreateQuery($sql);

   $n = $query->GetRowCount();

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;

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
     list ( $ref_pessoa,
            $pessoa_nome, 
            $pessoa_fone, 
            $desc_disciplina) = $query->GetRowValues();
   
     if ($i == 1)
     {
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"5\" height=\"35\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de alunos aptos a cursar a disciplina $ref_disciplina - $desc_disciplina no período $ref_periodo.</b></font></td></tr>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"35%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"35%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("  </tr>"); 
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
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_pessoa</td>");
     echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$pessoa_nome</td>");
     echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$pessoa_fone</td>");
     echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_curso</td>");
     echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_campus</td>");
     echo("  </tr>");

     $i++;

   }
   
   echo ("<tr><td bgcolor=\"#000000\" colspan=\"5\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Total de Alunos: $n &nbsp;&nbsp;</b></font></td></tr>");

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF">
<br>
<form method="post" action="">
<p> 
<script language="PHP">
  ListaAlunos($ref_curso, $ref_campus, $ref_disciplina, $ref_periodo);
</script>
</p>
</form>
<hr>
</body>
</html>
