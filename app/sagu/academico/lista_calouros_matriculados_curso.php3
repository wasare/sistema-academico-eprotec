<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Calouros Matriculados</title>
<script language="PHP">
function ListaAlunos($ref_curso, $ref_periodo, $ref_campus, $ref_vestibular)
{
   $conn = new Connection;

   $conn->open();

   $sql = " select ref_disciplina, " .
       	  "        descricao_disciplina(ref_disciplina), " .
   	      "        get_dia_semana_abrv(dia_disciplina_ofer_todos(ref_disciplina_ofer)), " .
    	  "	       get_turno(turno_disciplina_ofer_todos(ref_disciplina_ofer)), " .
	      "        curso_desc(ref_curso), " .
          "        get_campus(ref_campus), " .
	      "        count(*) " .
    	  " from matricula " .
	      " where ref_curso = '$ref_curso' and " .
    	  "       ref_periodo = '$ref_periodo' and "  .
	      "       dt_cancelamento is null and " .
    	  "       is_calouro(ref_pessoa, '$ref_vestibular') = 't' and " .
	      "       ref_campus = '$ref_campus' " .
    	  " group by ref_disciplina, " .
	      "          ref_disciplina_ofer, " .
    	  "          ref_curso, " .
	      "          ref_campus " .
    	  " order by dia_disciplina_ofer_todos(ref_disciplina_ofer)";

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
     list ($ref_disciplina,
           $nome_disciplina, 
    	   $dia_semana,
           $turno,
	   $curso_desc,
	   $campus,
	   $num_alunos) = $query->GetRowValues();
   
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Lista de Calouros Matriculados por Curso e Dia</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"6\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $curso_desc . " - " . $campus . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
         echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num. Alunos</b></font></td>");
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
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$i</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</td>");
     echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome_disciplina</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$dia_semana</td>");
     echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$turno</td>");
     echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$num_alunos</td>");
     echo("  </tr>");

     $i++;

   }

   echo("<tr><td colspan=\"6\"><hr></td></tr>");

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
      ListaAlunos($ref_curso, $ref_periodo, $ref_campus, $ref_vestibular);
    </script>
  </p>
  <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
  </div>
</form>
</body>
</html>
