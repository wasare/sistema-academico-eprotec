<? require("../../../lib/common.php"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<title>Listagem de Documentos Pendentes</title>
<script language="PHP">
function ListaAlunos($ref_periodo, $ref_curso, $ref_campus)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select A.ref_pessoa, " .
          "        pessoa_nome(A.ref_pessoa), " .
          "        A.rg_num, " .
          "        A.cpf, " .
          "        A.hist_escolar, " .
          "        A.titulo_eleitor, " .
          "        A.quitacao_eleitoral, " .
          "        A.doc_militar, " .
          "        A.foto, " .
          "        A.hist_original, " .
          "        A.atestado_medico, " .
          "        A.diploma_autenticado, " .
          "        A.solteiro_emancipado " .
          " from documentos A, contratos B " .
          " where A.ref_pessoa = B.ref_pessoa and " .
	  "       B.ref_curso = '$ref_curso' and " .
	  "       B.ref_campus = '$ref_campus' and " .
	  "       B.dt_desativacao is null " .
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

   while( $query->MoveNext() )
   {
     list ( $ref_pessoa,
            $pessoa_nome,
            $rg_num,
            $cpf,
            $hist_escolar,
            $titulo_eleitor,
            $quitacao_eleitoral, 
            $doc_militar,
            $foto,
            $hist_original,
            $atestado_medico,
            $diploma_autenticado,
            $solteiro_emancipado) = $query->GetRowValues();
   
     if ($rg_num == 't')
       $rg_num = "<INPUT type=\"checkbox\" checked>";
     else
       $rg_num = "<INPUT type=\"checkbox\">";

     if ($cpf == 't')
       $cpf = "<INPUT type=\"checkbox\" checked>";
     else
       $cpf = "<INPUT type=\"checkbox\">";

     if ($hist_escolar == 't')
       $hist_escolar = "<INPUT type=\"checkbox\" checked>";
     else
       $hist_escolar = "<INPUT type=\"checkbox\">";

     if ($titulo_eleitor == 't')
       $titulo_eleitor = "<INPUT type=\"checkbox\" checked>";
     else
       $titulo_eleitor = "<INPUT type=\"checkbox\">";

     if ($quitacao_eleitoral == 't')
       $quitacao_eleitoral = "<INPUT type=\"checkbox\" checked>";
     else
       $quitacao_eleitoral = "<INPUT type=\"checkbox\">";

     if ($doc_militar == 't')
       $doc_militar = "<INPUT type=\"checkbox\" checked>";
     else
       $doc_militar = "<INPUT type=\"checkbox\">";

     if ($foto == 't')
       $foto = "<INPUT type=\"checkbox\" checked>";
     else
       $foto = "<INPUT type=\"checkbox\">";

     if ($hist_original == 't')
       $hist_original = "<INPUT type=\"checkbox\" checked>";
     else
       $hist_original = "<INPUT type=\"checkbox\">";

     if ($atestado_medico == 't')
       $atestado_medico = "<INPUT type=\"checkbox\" checked>";
     else
       $atestado_medico = "<INPUT type=\"checkbox\">";
     
     if ($diploma_autenticado == 't')
       $diploma_autenticado = "<INPUT type=\"checkbox\" checked>";
     else
       $diploma_autenticado = "<INPUT type=\"checkbox\">";

     if ($solteiro_emancipado == 't')
       $solteiro_emancipado = "<INPUT type=\"checkbox\" checked>";
     else
       $solteiro_emancipado = "<INPUT type=\"checkbox\">";

     $href  = "<a href=\"documentos_edita.phtml?id=$ref_pessoa\"><img src=\"../images/hist.gif\" alt='Documentos' align='absmiddle' border=0></a>";
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"14\" height=\"35\"><font size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Documentação Pendente</b></font></td>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>R.G.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>CPF.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>H.E.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>H.O.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>T.E.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Q.E.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>D.M.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>A.M.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>D.A.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>S.E.</b></font></td>");
         echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Foto</b></font></td>");
         echo ("<td width=\"5%\" align=\"right\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\">&nbsp;</font></td>");
         echo ("  </tr>"); 
        }

      if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</font></td>");
          echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$rg_num</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$cpf</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$hist_escolar</font></td>");
	  echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$hist_original</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$titulo_eleitor</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$quitacao_eleitoral</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$doc_militar</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$atestado_medico</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$diploma_autenticado</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$solteiro_emancipado</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$foto</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</font></td>");
 
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"8%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</font></td>");
          echo ("<td width=\"32%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$pessoa_nome</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$rg_num</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$cpf</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$hist_escolar</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$hist_original</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$titulo_eleitor</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$quitacao_eleitoral</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$doc_militar</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$atestado_medico</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$diploma_autenticado</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$solteiro_emancipado</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$foto</font></td>");
          echo ("<td width=\"5%\"><font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</font></td>");
          echo("  </tr>\n");
         }

     $i++;

   }

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"14\"><font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Legenda:</b></font></td>");
   echo("  </tr>\n");

   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>R.G. - Cópia do RG</b></font></td>");
   echo ("<td colspan=\"10\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>CPF. - Cópia do CPF</b></font></td>");
   echo("  </tr>\n");
   
   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>H.E. - Cópia do Histórico</b></font></td>");
   echo ("<td colspan=\"10\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>H.O. - Histórico Original</b></font></td>");
   echo("  </tr>\n");
  
   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>T.E. - Título de Eleitor</b></font></td>");
   echo ("<td colspan=\"10\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Q.E. - Quitação Eleitoral</b></font></td>");
   echo("  </tr>\n");
  
   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>D.M. - Documentação Militar</b></font></td>");
   echo ("<td colspan=\"10\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>A.M. - Atestado Médico</b></font></td>");
   echo("  </tr>\n");
   
   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"4\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>D.A. - Diploma Autenticado</b></font></td>");
   echo ("<td colspan=\"10\"><font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>S.E. - Solteiro Emancipado</b></font></td>");
   echo("  </tr>\n");
   
   echo ("<tr bgcolor=\"#ffffff\">\n");
   echo ("<td colspan=\"14\"><hr></td>");
   echo("  </tr>\n");
   
   echo("</table>");

   echo ("<br><input type=\"button\" name=\"Button\" value=\"  Sair  \" onClick=\"javascript:history.go(-1)\">");
   
   echo ("</form></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF">
<form method="post" action="">
<p> 
<script language="PHP">
  ListaAlunos($ref_periodo, $ref_curso, $ref_campus);
</script>
</p>
</form>
</body>
</html>
