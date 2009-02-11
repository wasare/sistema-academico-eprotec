<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Listagem dos Créditos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="PHP">
function ListaCursosBolsa($periodo_id)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select distinct B.ref_curso,  " .
          "                 curso_desc(B.ref_curso),  " .
          "                 B.ref_campus, " .
          "                 get_campus(B.ref_campus), " .
          "                 A.ref_tipo_bolsa, " .
          "                 C.descricao," .
          "                 count(*)" .
          " from bolsas A, contratos B, aux_bolsas C " .
          " where A.ref_contrato=B.id and  " .
          "       A.ref_tipo_bolsa=C.id and  " .
          "       A.dt_inicio<=date(now()) and  " .
          "       A.dt_validade>=date(now()) and" .  
          "       A.percentual <> 0 and " . 
          "       B.dt_desativacao is null and " .
          "       B.ref_last_periodo='$periodo_id' " .
          "  group by B.ref_curso,  " .
          "           B.ref_campus, " .
          "           A.ref_tipo_bolsa, " .
          "           C.descricao; " ;

   $query = $conn->CreateQuery($sql);

   $n = $query->GetColumnCount();

   echo("<center><table width=\"550\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

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

   $total = 0;
   $total_geral = 0;
   
   while( $query->MoveNext() )
   {
     list ($ref_curso,
           $curso,
           $ref_campus, 
           $campus,
           $tipo_bolsa,
           $descricao,  
           $num) = $query->GetRowValues();

     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Alunos - Cédito Educativo</b></font></td>");
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Bolsa</b></font></td>");
         echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num</b></font></td>");
         echo ("  </tr>"); 
      }

     if(($aux_curso != $ref_curso) || ($aux_campus != $ref_campus))
     {

         if($i != 1)
         {
           echo ("<tr bgcolor=\"#CCCCCC\">\n");
           echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\">&nbsp;</font></td>");
           echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total do Curso</b></font></td>");
           echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total</b></font></td>");
           echo ("  </tr>");
         }

         echo ("<tr>"); 
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
         echo ("</tr>"); 

         echo ("<tr>"); 
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><b>$ref_curso - $curso - $campus</b></font></td>");
         echo ("</tr>"); 
         $aux_curso = $ref_curso;
         $aux_campus = $ref_campus;
         $total = 0;
     }
     
     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$tipo_bolsa</td>");
          echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$descricao</td>");
          echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$tipo_bolsa</td>");
          echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$descricao</td>");
          echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$num</td>");
          echo("  </tr>\n");
         }
     $total += $num;
     $total_geral += $num;

     $i++;

   }


   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\">&nbsp;</font></td>");
   echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total do Curso</b></font></td>");
   echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total</b></font></td>");
   echo ("  </tr>");

   echo ("<tr>"); 
   echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
   echo ("</tr>"); 

   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td width=\"15%\"><Font face=\"Verdana\" size=\"3\" color=\"#000099\">&nbsp;</font></td>");
   echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"3\" color=\"#000099\"><b>Total Geral</b></font></td>");
   echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_geral</b></font></td>");
   echo ("  </tr>");

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
  ListaCursosBolsa($ref_periodo)
</script>
  </p>
  <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onclick="location='listagem_bolsas.phtml'">
  </div>
</form>
</body>
</html>
