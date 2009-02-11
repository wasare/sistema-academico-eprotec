<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Relatório de Cancelamentos</title>

<script language="PHP">
  CheckFormParameters(array("periodo_id","dt_inicial","dt_final"));
  $dt_inicial = InvData($dt_inicial);
  $dt_final   = InvData($dt_final);
</script>

<script language="PHP">
function gera_relatorio($periodo_id, $dt_inicial, $dt_final)
{
   global $href;

   $conn = new Connection;

   $conn->open();
   
   // INICIO REINGRESSOS
   
   $sql = " SELECT ref_pessoa, " .
   	  "        pessoa_nome(ref_pessoa), " .
   	  "        ref_curso, " .
   	  "        curso_desc(ref_curso) " .
	  " FROM contratos " .
	  " WHERE ref_last_periodo = '$periodo_id' and " .
	  "       cod_status = 3 " .       
	  "       order by pessoa_nome(ref_pessoa); ";
  
   $query = $conn->CreateQuery($sql);
  
   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
   echo ("<tr><td>&nbsp;</td></tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"5\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Listagem de Reingressos em $periodo_id</b></center></font></td>");
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cont</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Aluno</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód</b></font></td>");
   echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Curso</b></font></td>");
   echo ("  </tr>"); 
  
   $soma_total = 0;
   $i = 1;

   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";

   $fg1 = "#000099";
   $fg2 = "#000099";

   while( $query->MoveNext() )
   {
       list ($ref_pessoa,
             $pessoa_nome,
             $ref_curso,
             $curso_nome) = $query->GetRowValues();
    
     if ( $i % 2 )
     {
         echo ("<tr bgcolor=\"$bg1\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
         echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$curso_nome</td>");
         echo ("  </tr>");
     }
     else
     {
         echo ("<tr bgcolor=\"$bg2\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$pessoa_nome</td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
         echo ("<td width=\"40%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$curso_nome</td>");
         echo ("  </tr>\n");
     }
   $i = $i + 1;
   $soma_total = $soma_total + 1;
   }

   echo ("<tr><td bgcolor=\"#000099\" colspan=\"5\" align=\"right\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL DE ALUNOS: $soma_total</b></font></td></tr>");
   
   echo("</table></center>");
   
   $query->Close();
   
   // FIM REINGRESSOS

   @$conn->Close();

}

</script>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="" name="myform">
<script language="PHP">
  gera_relatorio($periodo_id, $dt_inicial, $dt_final);
</script>
</form>
</body>
</html>
