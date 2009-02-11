<? require("../../../lib/common.php"); ?>
<? require("../../../lib/config.php"); ?>

<html>
<head>
<title>Número de Alunos Calouros Matriculados por Sala</title>
<script language="PHP">
function ListaSalas($id_periodo, $id_vestibular)
{
   $conn = new Connection;

   $conn->open();

   $total_calouros = 0;
   $total          = 0;

   $sql = " select trim(num_sala_disciplina_ofer_todos(ref_disciplina_ofer)), " .
          "        dia_disciplina_ofer_todos(ref_disciplina_ofer), " .
          "        get_dia_semana_abrv(dia_disciplina_ofer_todos(ref_disciplina_ofer)), " .
          "        turno_disciplina_ofer_todos(ref_disciplina_ofer), " .
          "        get_turno(turno_disciplina_ofer_todos(ref_disciplina_ofer)), " .
          "        ref_disciplina_ofer, " .
    	  "        get_disciplina_de_disciplina_of(ref_disciplina_ofer), " .
	      "        descricao_disciplina(get_disciplina_de_disciplina_of(ref_disciplina_ofer)), " .
          "        get_num_matriculados(ref_disciplina_ofer), " .
          "        count(*) " .
          " from matricula " .
          " where ref_periodo = '$id_periodo' and " .
          "       is_calouro(ref_pessoa, '$id_vestibular') = 't' and " .
          "       dt_cancelamento is null and " .
          "       num_creditos(ref_pessoa, '$id_periodo') > 0 " .
          " group by trim(num_sala_disciplina_ofer_todos(ref_disciplina_ofer)), " .
          "          dia_disciplina_ofer_todos(ref_disciplina_ofer), " .
          "          turno_disciplina_ofer_todos(ref_disciplina_ofer), " .
          "          ref_disciplina_ofer " .
          " order by dia_disciplina_ofer_todos(ref_disciplina_ofer), " .
    	  "	         turno_disciplina_ofer_todos(ref_disciplina_ofer), " .
	      "	         trim(num_sala_disciplina_ofer_todos(ref_disciplina_ofer));";

   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

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
     list ( $num_sala,
            $dia, 
            $dia_desc,
            $turno, 
            $turno_desc,
            $ref_disciplina_ofer, 
    	    $ref_disciplina,
	        $nome_disciplina,
            $num_alunos, 
            $num_alunos_calouros) = $query->GetRowValues();

     $href  = "<a href=\"javascript:Select('$num_sala','$dia','$turno','$id_periodo','$id_vestibular','$ref_disciplina_ofer')\">" . $num_sala . "</a>";
     
     $href1  = "<a href=\"javascript:Select('$num_sala','$dia','$turno','$id_periodo','$id_vestibular','$ref_disciplina_ofer')\">" . $nome_disciplina . "</a>";

     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"8\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Calouros Matriculados por Sala</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"8\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . " </b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Sala<br>Prédio</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cod.</b></font></td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Ofer.</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia</b></font></td>");
         echo ("<td width=\"7%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num. Calouros</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num. Total</b></font></td>");
         echo ("  </tr>"); 
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
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
     echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$href1</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina_ofer</td>");
     echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dia_desc</td>");
     echo ("<td width=\"7%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$turno_desc</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_alunos_calouros</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_alunos</td>");
     echo("  </tr>");

     $i++;

     $total_calouros = $total_calouros + $num_alunos_calouros;
     $total          = $total + $num_alunos;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"80%\" colspan=\"6\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS POR SALA:</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total_calouros</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"8\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
<script language="JavaScript">
function Select(num_sala, dia, turno, ref_periodo, ref_vestibular, ref_disciplina_ofer)
{
  var url = "calouros_matriculados_select.php3" +
            "?num_sala=" + escape(num_sala) + 
            "&dia=" + escape(dia) + 
            "&turno=" + escape(turno) + 
            "&ref_periodo=" + escape(ref_periodo) + 
            "&ref_vestibular=" + escape(ref_vestibular) + 
            "&ref_disciplina_ofer=" + escape(ref_disciplina_ofer);

  location = url; 
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   ListaSalas($ref_periodo, $ref_vestibular);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='calouros_matriculados.phtml'">
</div>
</form>
</body>
</html>
