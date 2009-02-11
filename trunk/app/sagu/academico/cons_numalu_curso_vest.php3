<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Curso</title>
<script language="PHP">
function ListaCursos($ref_periodo, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;

   $conn->open();

   $total_vest=0;
   $total_ant=0;
   $total=0;

   $sql = " select ref_curso, " .
          "        get_campus(ref_campus), " .
          "        curso_desc(ref_curso), " .
          "        get_num_matr_vest(ref_curso, ref_campus, '$ref_periodo', '$dt_livro_matricula'), ".
          "        count(*) - coalesce(get_num_matr_vest(ref_curso, ref_campus, '$ref_periodo', '$dt_livro_matricula'),0), ".
          "        count(*), " .
          "        ref_campus " .
          " from contratos " .
          " where ref_last_periodo='$ref_periodo' and " .
          "       id = is_matriculado_cntr('$ref_periodo', id) and " . 
          "       dt_desativacao is null and ".
          "       ref_curso<>6 and " .
          "       fl_ouvinte<>'1' " . 
          " group by ref_curso, ref_campus" ;

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
            $numero_vest, 
            $numero_ant, 
            $numero, 
            $ref_campus) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Alunos Matriculados por Curso separando por Vestibular</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>"); 
         echo("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Vestibular: <b>$dt_livro_matricula</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição do Curso</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Calouros</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Veteranos</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$campus</td>");
          echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$curso</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$numero_vest</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$numero_ant</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$numero</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$campus</td>");
          echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$curso</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$numero_vest</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$numero_ant</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$numero</td>");
          echo("  </tr>\n");
         }

     $i++;

     $total_vest=$total_vest+$numero_vest;
     $total_ant=$total_ant+$numero_ant;
     $total=$total+$numero;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"65%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS:</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total_vest</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total_ant</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"7\" align=\"center\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaCursos($ref_periodo, $dt_livro_matricula, $anterior);
</script>
<div align="center">
  <input type="button" name="Button" value="  Voltar  " onClick="location='cons_numalu.phtml'">
</div>
</form>
</body>
</html>
