<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="PHP">
function ListaAlunos($ref_periodo, $letra1, $letra2, $letra3)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select id,                                  " .
          "        ref_pessoa,                          " .
          "        pessoa_nome(ref_pessoa),             " .
          "        pessoa_fone(ref_pessoa),             " .
          "        curso_desc(ref_curso)                " .
          " from contratos                              " .
          " where ref_last_periodo='$ref_periodo' and   " .
          " (pessoa_nome(ref_pessoa) like '$letra1%' or " .
          " pessoa_nome(ref_pessoa) like '$letra2%'  or " .
          " pessoa_nome(ref_pessoa) like '$letra3%')    " .
          " order by pessoa_nome(ref_pessoa);           ";
   
   $query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

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
     list ( $id, 
            $ref_pessoa,
            $nome, 
            $fone, 
            $curso_desc) = $query->GetRowValues();
   
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"35\"><font size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Alunos - $letra1 $letra2 $letra3</b></font></td>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Assinatura</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome</td>");
          echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">______________________________________</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome</td>");
          echo ("<td width=\"10%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
          echo ("<td width=\"40%\" height=\"20\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">______________________________________</td>");
          echo("  </tr>\n");
         }

     $i++;

   }

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF">
<form method="post" action="">
<p> 
<script language="PHP">
  ListaAlunos($ref_periodo, $letra1, $letra2, $letra3);
</script>
</p>
</form>
<hr>
</body>
</html>