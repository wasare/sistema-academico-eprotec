<? require("../../../lib/common.php"); ?>

<html>
<head>
<title>Untitled Document</title>
<script language="PHP">
function ListaAlunos($ref_motivo)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select ref_pessoa, " .
          "        pessoa_nome(ref_pessoa), " .
	  "        ref_curso, " .
	  "        curso_desc(ref_curso), " .
	  "        ref_campus, " .
	  "        get_campus(ref_campus), " .
	  "        ref_last_periodo " .
	  " from contratos " .
	  " where ref_motivo_desativacao in ($ref_motivo) " .
	  " order by ref_curso, 2;";
   
   $query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de Alunos que transferiram-se para outra Instituição</b></font></td>");
   echo ("</tr>"); 
   
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
   echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;Último Período</b></font></td>");
   echo ("</tr>"); 

   $i=1;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $aux_curso = -1;
   $aux_campus = -1;

   while( $query->MoveNext() )
   {
     list ( $ref_pessoa, 
            $pessoa_nome, 
            $ref_curso, 
            $curso, 
            $ref_campus, 
            $campus, 
            $ref_last_periodo) = $query->GetRowValues();
  
     if (($aux_curso <> $ref_curso) || ($aux_campus <> $ref_campus))
     {
        echo ("<tr>");
        echo ("<td bgcolor=\"#000099\" colspan=\"4\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$curso - $campus</b></font></td>");
        echo ("</tr>");
        $aux_curso = $ref_curso;
	$aux_campus = $ref_campus;
       
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
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
     echo ("<td width=\"65%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</td>");
     echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp;$ref_last_periodo</td>");
     echo("  </tr>");
     
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
       ListaAlunos($ref_motivo);
    </script>
  </p>
</form>
</body>
</html>
