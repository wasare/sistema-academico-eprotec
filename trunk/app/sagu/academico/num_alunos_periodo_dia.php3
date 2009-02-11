<? require("../../../lib/common.php"); ?>
<html>
<head>
<title>Número de Alunos por Curso</title>
<script language="PHP">

CheckFormParameters(array("periodo_id",
                          "campus_id"));

function ListaAlunosPeriodoDia($id_periodo, $id_campus)
{
   $conn = new Connection;

   $conn->open();

   $total=0;
   
   $sql = " select distinct " .
          "        A.ref_campus," .
    	  "        get_campus(A.ref_campus), ".
          "        C.dia_semana, " .
          "        get_dia_semana_abrv(C.dia_semana), " .
          "        C.turno, " .
          "        count(*) ".
          " from contratos A, matricula B, disciplinas_ofer_compl C " .
          " where A.ref_last_periodo='$id_periodo' and " .
          "       B.ref_periodo='$id_periodo' and " .
          "       A.ref_campus='$id_campus' and " .
          "       A.dt_desativacao is null and" .
    	  "       B.dt_cancelamento is null and" .
          "       B.ref_disciplina_ofer = C.ref_disciplina_ofer and " .
          "       A.ref_pessoa = B.ref_pessoa and " .
          "       A.id = B.ref_contrato " .
          " group by A.ref_campus, get_campus(A.ref_campus), C.dia_semana, C.turno " .
          " order by C.dia_semana, C.turno; " ;
   
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
     list ( $ref_campus,
            $campus,
    	    $dia,
            $dia_semana,
            $turno,
            $numero) = $query->GetRowValues();

     $href1 = "<a href=\"javascript:Lista_Alunos_Periodo('$id_periodo', '$ref_campus', '$dia', '$turno')\">TODOS OS CURSOS</a>";
     
     $sqlM = " select count(*) " .
             " from contratos A, matricula B, disciplinas_ofer_compl C " .
             " where A.ref_last_periodo = '$id_periodo' and " .
             "       B.ref_periodo = '$id_periodo' and " .
             "       A.ref_campus = '$ref_campus' and " .
             "       C.dia_semana = '$dia' and " .
             "       C.turno = '$turno' and " .
             "       A.dt_desativacao is null and " .
             "       B.dt_cancelamento is null and " .
             "       A.ref_pessoa = B.ref_pessoa and " .
             "       A.id = B.ref_contrato and " .
             "       B.ref_disciplina_ofer = C.ref_disciplina_ofer and " .
             "       get_sexo(A.ref_pessoa) = 'M' " .
             " group by A.ref_campus, " .
             "          get_campus(A.ref_campus), " .
             "          C.dia_semana, " .
             "          C.turno, " .
             "          get_sexo(A.ref_pessoa)";
    
     $queryM = $conn->CreateQuery($sqlM);
     
     $masculino = 0;
     
     if( $queryM->MoveNext() )
     {
         $masculino = $queryM->GetValue(1);
     }
     
     $queryM->Close();
						     
     $sqlF = " select count(*) " .
             " from contratos A, matricula B, disciplinas_ofer_compl C " .
             " where A.ref_last_periodo = '$id_periodo' and " .
             "       B.ref_periodo = '$id_periodo' and " .
             "       A.ref_campus = '$ref_campus' and " .
             "       C.dia_semana = '$dia' and " .
             "       C.turno = '$turno' and " .
             "       A.dt_desativacao is null and " .
             "       B.dt_cancelamento is null and " .
             "       A.ref_pessoa = B.ref_pessoa and " .
             "       A.id = B.ref_contrato and " .
             "       B.ref_disciplina_ofer = C.ref_disciplina_ofer and " .
             "       get_sexo(A.ref_pessoa) = 'F' " .
             " group by A.ref_campus, " .
             "          get_campus(A.ref_campus), " .
             "          C.dia_semana, " .
             "          C.turno, " .
             "          get_sexo(A.ref_pessoa)";
    
     $queryF = $conn->CreateQuery($sqlF);
   
     $feminino = 0;
   
     if( $queryF->MoveNext() )
     {
         $feminino = $queryF->GetValue(1);
     }
     
     $queryF->Close();

     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Período/Campus/Dia da Semana/Turno</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $id_periodo . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia</b></font></td>");
         echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Turno</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição do Curso</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Mas.</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fem.</b></font></td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Tot.</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$dia_semana</td>");
          echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$turno</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$campus</td>");
          echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href1</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$masculino&nbsp;</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$feminino&nbsp;</td>");
          echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$numero</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dia_semana</td>");
          echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$turno</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$campus</td>");
          echo ("<td width=\"55%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href1</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$masculino&nbsp;</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$feminino&nbsp;</td>");
          echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$numero</td>");
          echo("  </tr>\n");
         }

     $i++;

     $total=$total+$numero;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"70%\" colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS:</td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"7\"><hr></td></tr>");

   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
<script language="JavaScript">
function Lista_Alunos_Periodo(ref_periodo, ref_campus, dia, turno)
{
  var url = "lista_alunos_periodo_dia.php3" +
            "?ref_periodo=" + escape(ref_periodo) +
            "&ref_campus=" + escape(ref_campus) +
            "&dia_semana=" + escape(dia) +
            "&turno=" + escape(turno);

  location = url; 
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
    ListaAlunosPeriodoDia($periodo_id, $campus_id);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onClick="location='alunos_curso_dia.phtml'">
</div>
</form>
</body>
</html>
