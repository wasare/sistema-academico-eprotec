<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Lista de Alunos por Possibilidae</title>
<script language="PHP">
function ListaAlunos($ref_curso, $ref_campus, $ref_disciplina, $status)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select ref_pessoa, " .
          "        pessoa_nome(ref_pessoa), " .
    	  "        pessoa_fone(ref_pessoa), " .
	      "        ref_curso, " .
	      "        curso_desc(ref_curso), " .
    	  "        descricao_disciplina($ref_disciplina) " .
          " from disciplinas_todos_alunos " .
          " where ref_curso='$ref_curso' and " .
          "       ref_campus='$ref_campus' and " .
          "       ref_disciplina='$ref_disciplina' and " .
          "       status='$status' " .
          " order by pessoa_nome(ref_pessoa); ";
										   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

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
     list ($id,
           $nome,
           $fone,
           $ref_curso,
           $curso_descricao,
    	   $descricao_disciplina) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b><center>UNIVATES - Centro Universitário</center></b></font></td></tr>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: $ref_curso - $curso_descricao  Campus: $ref_campus</b></font></td>");
         echo ("</tr>"); 

         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Disciplina: $ref_disciplina - $descricao_disciplina</b></font></td>");
         echo ("</tr>"); 

         
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fones</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("  </tr>"); 
        }
     
     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$id</td>");
          echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$nome</td>");
          echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$fone</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp;$ref_curso</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$id</td>");
          echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp;$nome</td>");
          echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$fone</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp;$ref_curso</td>");
          echo("  </tr>\n");
         }

     $i++;

   }

   echo("<tr><td colspan=\"5\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
  <script language="PHP">
   CheckFormParameters(array("ref_curso",
                             "ref_campus",
                             "ref_disciplina",
                             "status"));

   ListaAlunos($ref_curso, $ref_campus, $ref_disciplina, $status);
  </script>
  <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
