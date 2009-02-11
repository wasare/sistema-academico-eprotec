<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Curso e Campus</title>
<script language="PHP">

CheckFormParameters(array("ref_periodo"));

function ListaCursos($ref_periodo)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select ref_curso, " .
          "        get_campus(ref_campus), " .
          "        curso_desc(ref_curso), " .
          "        count(*), " .
          "        ref_campus  " .
          " from contratos " .
          " group by ref_curso, ref_campus; "  ;


   $query = $conn->CreateQuery($sql);

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
     list ( $ref_curso,
            $campus, 
            $curso,
            $numero, 
            $ref_campus) = $query->GetRowValues();

     $href1 = "<a href=\"javascript:Lista_Alunos_Curso_Campus('$ref_curso', '$ref_campus', '$ref_periodo')\"> " . $curso . "</a>";
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Alunos por Curso e Campus - Ativo + Passivo</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição do Curso</b></font></td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$campus</td>");
          echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href1</td>");
          echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$numero</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$campus</td>");
          echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href1</td>");
          echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$numero</td>");
          echo("  </tr>\n");
         }

     $i++;

     $total=$total+$numero;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"80%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS:</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"4\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
<script language="JavaScript">
function Lista_Alunos_Curso_Campus(ref_curso, ref_campus, ref_periodo)
{
  var url = "lista_alunos_curso_campus.php3" +
            "?ref_curso=" + escape(ref_curso) +
            "&ref_campus=" + escape(ref_campus) +
            "&ref_periodo=" + escape(ref_periodo);

  location = url; 
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaCursos($ref_periodo);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='cons_numalu.phtml'">
</div>
</form>
</body>
</html>
