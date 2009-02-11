<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<title>Listagem de Anotações nos Documentos</title>
<script language="PHP">
function ListaAlunos()
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select ref_pessoa, " .
          "        pessoa_nome(ref_pessoa), " .
          "        anotacoes " .
          " from documentos " .
          " where anotacoes <> '' " .
          " order by 2;";
   
   $query = $conn->CreateQuery($sql);
   
   echo ("<form>");
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

   $ctrl = 0;

   while( $query->MoveNext() )
   {
     list ( $ref_pessoa,
            $pessoa_nome,
            $anotacoes) = $query->GetRowValues();
   
     $href  = "<a href=\"documentos_edita.phtml?id=$ref_pessoa\"><img src=\"../images/hist.gif\" alt='Documentos' align='absmiddle' border=0></a>";
     $ctrl = 1;
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"35\"><font size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Anotações de Documentação</b></font></td>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"55%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Anotação</b></font></td>");
	 echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;</font></td>");
         echo ("  </tr>"); 
        }

      if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</font></td>");
          echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</font></td>");
          echo ("<td width=\"55%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$anotacoes</font></td>");
	  echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</font></td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</font></td>");
          echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$pessoa_nome</font></td>");
          echo ("<td width=\"55%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$anotacoes</font></td>");
	  echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</font></td>");
          echo("  </tr>\n");
         }

     $i++;

   }
   if ($ctrl == 0)
   {
      echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"35\"><font size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Anotações de Documentação</b></font></td>");
      echo ("<tr bgcolor=\"#000000\">\n");
      echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
      echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
      echo ("<td width=\"55%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Anotação</b></font></td>");
      echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;</font></td>");
      echo ("  </tr>"); 

      echo("<tr bgcolor=\"#ffffff\" align=\"center\">\n");
      echo ("<td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"red\">Nenhuma pessoa se encaixou na restrição...</font></td>");
      echo("  </tr>\n");


   }

   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"4\"><hr></td>");
   echo("  </tr>\n");
   
   echo("</table>");

   echo ("<br><input type=\"button\" name=\"Button\" value=\"  Voltar  \" onClick=\"location='documentos_pendentes.phtml'\">");
   
   echo ("</form></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<p> 
<script language="PHP">
  ListaAlunos();
</script>
</p>
</form>
</body>
</html>
