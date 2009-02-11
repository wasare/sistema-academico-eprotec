<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<title>Listagem de Trancamentos</title>
<script language="PHP">
function ListaAlunos()
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select distinct ref_pessoa, " .
          "        pessoa_nome(ref_pessoa),  " .
          "        pessoa_fone(ref_pessoa), " .
          "        ref_curso_atual " .
          " from livro_matricula " .
          " where ref_status = 8 or " .
          "       ref_status = 9 or " .
          "       ref_status = 12 or " .
          "       ref_status = 13 or " .
          "       ref_status = 15 or " .
          "       ref_status = 16 or " .
          "       ref_status = 17 " .
          " order by pessoa_nome(ref_pessoa);";
   
   $query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=0;

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
            $ref_curso_atual) = $query->GetRowValues();
   
     if ($i == 0)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"35\"><font size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Alunos com matrícula trancada</b></font></td>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fone</b></font></td>");
         echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</td>");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_fone</td>");
          echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso_atual</td>");

          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$pessoa_nome</td>");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$pessoa_fone</td>");
          echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso_atual</td>");

          echo("  </tr>\n");
         }

     $i++;

   }
   
   echo("<tr bgcolor=\"#000000\">\n");
   echo("   <td height=\"20\" align=\"right\" colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total: $i alunos&nbsp;</b></font></td>");
   echo("</tr>"); 

   echo("<tr><td colspan=4><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
  ListaAlunos($ref_periodo, $letra1, $letra2, $letra3);
</script>
<div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onClick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
