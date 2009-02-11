<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<?
CheckFormParameters(array('periodo_id',
            			  'curso_id',
			              'campus_id',
                          'status_aluno',
                          'status_disciplina'));
?>
<html>
<head>
<title>Lista de Disciplinas do Currículo Cursadas</title>

<script language="JavaScript">
function Mostra_Alunos(periodo_id, curso_id, campus_id, disciplina_id, status_aluno, status_disciplina)
{
  var url = "lista_alunos_disc_cursadas.phtml" +
            "?ref_periodo=" + escape(periodo_id) +
            "&ref_curso=" + escape(curso_id) +
            "&ref_campus=" + escape(campus_id) +
            "&ref_disciplina=" + escape(disciplina_id) +
            "&status_aluno=" + escape(status_aluno) +
            "&status_disciplina=" + escape(status_disciplina);
            
  location = url;
}
</script>

<script language="PHP">
function Lista_disciplinas($periodo_id, $curso_id, $campus_id, $status_aluno, $status_disciplina)
{
   $vet[1][1] = 'Somente Alunos Ativos';
   $vet[1][2] = 'Alunos Ativos e Passivos';
   $vet[2][1] = 'Somente as disciplinas concluídas';
   $vet[2][2] = 'Disciplinas concluídas e matriculadas';

   $conn = new Connection;

   $conn->Open();

   $sql = " select ref_disciplina, ".
          "        descricao_disciplina(ref_disciplina), " .
          "        ref_curso, " .
          "        curso_desc($curso_id), " .
          "        semestre_curso, " .
          "        dt_final_curriculo " .
          " from cursos_disciplinas ".
          " where ref_curso = '$curso_id' and ".
          "       ref_campus = '$campus_id' and " .
          "       (curriculo_mco = 'M' or curriculo_mco = 'C') " .
          " order by semestre_curso;" ;
       
    $query = $conn->CreateQuery($sql);

    echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

    $i=1;
    $j=0;

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
              $desc_disciplina,
              $ref_curso,
              $desc_curso,
    	      $semestre,
              $dt_final_curriculo) = $query->GetRowValues();
        
        $dt_final_curriculo = Invdata($dt_final_curriculo);
        
        $sql = " select count(*) " .
         	   " from matricula A, contratos B " .
        	   " where A.ref_contrato = B.id and " .
               "       A.ref_curso = B.ref_curso and " .
               "       A.ref_campus = B.ref_campus and " .
	           "       B.ref_curso = '$curso_id' and " .
	           "       B.ref_campus = '$campus_id' and " .
         	   "       A.ref_disciplina = '$ref_disciplina' and " .
         	   "       A.dt_cancelamento is null ";

        if ($status_aluno == 1)       // Somente alunos ativos
        {
          $sql .= " and B.ref_last_periodo = '$periodo_id' " .
    	          " and B.dt_desativacao is null ";
        }

        if ($status_disciplina == 1)  //Somente disciplinas aprovadas com nota
        {
          $sql .= " and A.nota_final >= 5 ";
        }
   
        $query2 = $conn->CreateQuery($sql);

        $query2->MoveNext();

        $count = $query2->GetValue(1);
     
        if ($i == 1)
        {
            echo ("<tr><td bgcolor=\"#000099\" height=\"30\" colspan=\"8\"><font size=\"4\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Listagem de Disciplinas Cursadas do Currículo</b></center></font></td></tr>");
            echo ("<tr><td bgcolor=\"#000099\" colspan=\"8\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período, Curso e Campus: $periodo_id, $curso_id e $campus_id</b></font></td></tr>");
            echo ("<tr><td bgcolor=\"#000099\" colspan=\"8\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Opções: {$vet[1][$status_aluno]} e {$vet[2][$status_disciplina]}</b></font></td></tr>");
            echo ("<tr bgcolor=\"#000000\">\n");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Curso</b></font></td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cod.</b></font></td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Dt. Fim Curr.</b></font></td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Semestre</b></font></td>");
            echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Alunos</b></font></td>");
            echo ("  </tr>"); 
        }
      
        $href = "<a href=\"javascript:Mostra_Alunos('$periodo_id','$curso_id','$campus_id','$ref_disciplina','$status_aluno','$status_disciplina')\"><img src=\"../images/select.gif\" title='Mostrar Alunos' border=0></a>";
     
        if ( $i % 2 )
        {
            echo("<tr bgcolor=\"$bg1\">\n");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$desc_disciplina</td>");
            echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$dt_final_curriculo</td>");
            echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$semestre</td>");
            echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$count</td>");
            echo("  </tr>");
         }
         else
         {
            echo("<tr bgcolor=\"$bg2\">\n");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
            echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
            echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$desc_disciplina</td>");
            echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dt_final_curriculo</td>");
            echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$semestre</td>");
            echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$count</td>");
            echo("  </tr>\n");
        }

        $i++;
        $query2->Close();
    }
   
    echo ("<tr><td colspan=8 align=center><hr size=1>" .
         "<input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\">" .
         "</td></tr>\n");
   
    echo("</table></center>");

    $query->Close();

    $conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_disciplinas($periodo_id, $curso_id, $campus_id, $status_aluno, $status_disciplina);
  </script>
</form>
</body>
</html>
