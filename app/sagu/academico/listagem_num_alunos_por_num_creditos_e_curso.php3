<? header("Cache-Control: no-cache"); ?>
<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Listagem dos Créditos</title>
<script language="PHP">
function ListaCursosBolsa($periodo_id, $data_geracao, $num_semanas)
{
   $conn = new Connection;

   $conn->open();

   // Este select pega informações dos Contratos.
   /*$sql = " select ref_curso, " .
          "        curso_desc(ref_curso), " .
          "        ref_campus, " .
          "        get_campus(ref_campus), " .
          "        sum(num_creditos2(ref_pessoa, '$periodo_id', '$data_geracao')), " .
          "        count(*) " .
          " from contratos " .
          " where ref_last_periodo = '$periodo_id' and " .
          "       ref_curso <> 6 and " .
	      "       fl_ouvinte <> '1' and " .
	      "       dt_desativacao is null " .
          " group by ref_curso, " .
	      "          ref_campus, " .
	      "          num_creditos2(ref_pessoa, '$periodo_id', '$data_geracao') " .
          " order by ref_curso, " .
	      "          ref_campus, " .
	      "          num_creditos2(ref_pessoa, '$periodo_id', '$data_geracao'); " ;*/

   // Este select pega as informações do Livro matrícula do respectivo período. 
   $sql = " select A.ref_curso_atual, " .
          "        curso_desc(A.ref_curso_atual),  " .
          "        A.ref_campus_atual,  " .
          "        get_campus(A.ref_campus_atual), " .
          "        num_creditos2(A.ref_pessoa, '$periodo_id', '$data_geracao'), " .
	      "        sum(num_creditos2(A.ref_pessoa, '$periodo_id', '$data_geracao')), " .
          "        count(*) " .
          " from livro_matricula A, status_matricula B " .
          " where A.ref_status = B.id and " .
          "	      B.fl_in_lm = 'f' and " .
          "	      A.ref_periodo= '$periodo_id' and " .
          "       num_creditos2(A.ref_pessoa, '$periodo_id', '$data_geracao') is not null and " .
          "       A.ref_curso_atual<>6  " .
          " group by A.ref_curso_atual, " .
	      "	         A.ref_campus_atual, " .
	      "	         num_creditos2(A.ref_pessoa, '$periodo_id', '$data_geracao') " .
          " order by A.ref_curso_atual, " .
          "          A.ref_campus_atual, " .
          "          num_creditos2(A.ref_pessoa, '$periodo_id', '$data_geracao'); " ; 
   
   $query = $conn->CreateQuery($sql);

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

   $total_soma_creditos_geral  = 0;
   $total_num_alunos_geral     = 0;
   $total_num_creditos_geral   = 0;
   $total_horas_semanais_geral = 0;

   echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $total_soma_creditos  = 0;
   $total_num_alunos     = 0;
   $total_num_creditos   = 0;
   $total_horas_semanais = 0;

   $i=1;

   while( $query->MoveNext() )
   {
     list ( $ref_curso, 
            $curso,
            $ref_campus, 
            $campus, 
            $num_creditos,
            $soma_creditos,
            $num_alunos) = $query->GetRowValues();

     $soma_creditos = (int) $soma_creditos;
     $num_alunos    = (int) $num_alunos ;
   
     if ($i == 1)
     {
         echo ("<tr><td bgcolor=\"#000099\" colspan=\"7\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Alunos por número de créditos e curso</b></font></td></tr>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $periodo_id . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Número Créditos</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Horas-Aula Semanais</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total de Créditos</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Total Alunos</b></font></td>");
         echo ("  </tr>"); 
     }

     if(($aux_curso != $ref_curso) || ($aux_campus != $ref_campus))
     {
        if($i != 1)
        {
           echo ("<tr bgcolor=\"#CCCCCC\">\n");
           echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>&nbsp;</b></font></td>");
           echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total do Curso:</b></font></td>");
           echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>&nbsp;</b></font></td>");
           echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_num_creditos</b></font></td>");
           echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_horas_semanais</b></font></td>");
           echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_soma_creditos</b></font></td>");
           echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_num_alunos</b></font></td>");
           echo ("  </tr>"); 
        }

        $total_soma_creditos  = 0;
        $total_num_alunos     = 0;
        $total_num_creditos   = 0;
        $total_horas_semanais = 0;

        echo ("<tr>");
        echo ("<td bgcolor=\"#ffffff\" colspan=\"7\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
        echo ("</tr>"); 
        $aux_curso = $ref_curso;
        $aux_campus = $ref_campus;
     }

     $horas_semanais = ($num_creditos*15)/$num_semanas;
     
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
     
     echo("<tr bgcolor=\"$bg1\">\n");
     echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
     echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$curso</td>");
     echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$campus</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num_creditos</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">" . sprintf("%.3f", $horas_semanais) . "</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">" . sprintf("%.2f", $soma_creditos) . "</td>");
     echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$num_alunos</td>");
     echo("  </tr>");
     
     $total_soma_creditos  += $soma_creditos;
     $total_num_alunos     += $num_alunos;
     $total_num_creditos   += $num_creditos;
     $total_horas_semanais += $horas_semanais;

     $total_soma_creditos_geral  += $soma_creditos;
     $total_num_alunos_geral     += $num_alunos;
     $total_num_creditos_geral   += $num_creditos;
     $total_horas_semanais_geral += $horas_semanais;

     $i++;

   }

   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>&nbsp;</b></font></td>");
   echo ("<td width=\"45%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total do Curso:</b></font></td>");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>&nbsp;</b></font></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_num_creditos</b></font></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_horas_semanais</b></font></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_soma_creditos</b></font></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_num_alunos</b></font></td>");
   echo ("  </tr>"); 

   echo ("<tr>");
   echo ("<td bgcolor=\"#ffffff\" colspan=\"7\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#000000\"><hr></font></td>");
   echo ("</tr>"); 


   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td colspan=\"5\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total Geral de Créditos</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_num_creditos_geral</b></font></td>");
   echo ("</tr>");

   $total_horas_semanais_geral = round($total_horas_semanais_geral, 2);

   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td colspan=\"5\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total Geral do Horas Semanais</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_horas_semanais_geral</b></font></td>");
   echo ("</tr>");

   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td colspan=\"5\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total Geral da Soma de Créditos</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_soma_creditos_geral</b></font></td>");
   echo ("</tr>");

   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td colspan=\"5\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Total Geral de Alunos</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$total_num_alunos_geral</b></font></td>");
   echo ("</tr>");
   
   $media = $total_soma_creditos_geral / $total_num_alunos_geral;
   $media = sprintf("%0.2f", $media);

   echo ("<tr bgcolor=\"#CCCCCC\">\n");
   echo ("<td colspan=\"5\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>Média de Créditos por aluno</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#000099\"><b>$media</b></font></td>");
   echo ("</tr>");

   echo ("<tr>\n");
   echo ("<td colspan=\"7\"><hr></td>");
   echo ("</tr>");
   
   echo ("<tr>\n");
   echo ("<td colspan=\"7\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"red\"><b>Obs.: A coluna Horas-Aula Semanais está baseado em $num_semanas semanas por semestre e 15 horas-aula por crédito.</b></font></td>");
   echo ("</tr>");

   echo ("<tr>\n");
   echo ("<td colspan=\"7\"><hr></td>");
   echo ("</tr>");

   echo("</table>");
   
   $query->Close();

   $conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
  CheckFormParameters(array("ref_periodo","data_geracao","num_semanas"));
  $data_geracao = InvData($data_geracao);
  
  ListaCursosBolsa($ref_periodo, $data_geracao, $num_semanas)
  
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  " onclick="history.go(-1)">
</div>
</form>
</body>
</html>
