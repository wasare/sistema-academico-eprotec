<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Número de Alunos por Turma</title>

<script language="PHP">

function ListaCursos($ref_periodo, $data_geracao)
{
   $conn = new Connection;
   $conn->open();

   $total=0;

   $sql = " SELECT D.id, ".
         "         D.ref_disciplina, ".
         "         descricao_disciplina(D.ref_disciplina), ".
         "         professor_disciplina_ofer_todos(D.id), " .
         "         D.ref_curso, " .
         "         D.ref_campus," .
         "         get_curso_abrv(D.ref_curso), " .
         "         get_campus(D.ref_campus)," .
         "         get_dia_semana(dia_disciplina_ofer_todos(D.id)),".
         "         get_turno(turno_disciplina_ofer_todos(D.id)),".
         "         count(*) ".
         " FROM matricula A, livro_matricula B, status_matricula C, disciplinas_ofer D ".
         " WHERE A.ref_disciplina_ofer = D.id and ".
         "       A.ref_pessoa = B.ref_pessoa and " .
         "       A.ref_periodo = B.ref_periodo and " .
         "       B.ref_periodo = D.ref_periodo and " .
         "       A.ref_periodo='$ref_periodo' and ".
         "       B.ref_status = C.id and " .
         "       C.fl_in_lm = 'f' and " . 
         "       (A.dt_cancelamento is null or A.dt_cancelamento > '$data_geracao') and ".
         "       D.is_cancelada = '0' and ".
         "       B.ref_curso_atual<>6  " .
         " GROUP BY D.id, ".
         "          D.ref_disciplina, ".
         "          D.ref_curso, " .
         "          D.ref_campus," .
         "          get_dia_semana(dia_disciplina_ofer_todos(D.id)),".
         "          get_turno(turno_disciplina_ofer_todos(D.id)) " .
         " ORDER BY dia_disciplina_ofer_todos(D.id), " .
         "          turno_disciplina_ofer_todos(D.id), " .
         "          D.ref_campus, " .
         "          D.ref_curso, " .
         "          D.ref_disciplina";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >");

   $i=1;
   // cores fundo
   $bg0 = "#DDFFDD";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#000000";
   $fg1 = "#000099";
   $fg2 = "#000099";
   
   $ref_curso_  = '';
   $ref_campus_ = '';
   $dia_semana_ = '';
   $turno_      = '';
   
   while( $query->MoveNext() )
   {
     list ( $id, 
            $ref_disciplina,
            $disciplina, 
            $professor,
            $ref_curso,
            $ref_campus,
            $curso,
            $campus,
            $dia_semana,
            $turno,
            $count) = $query->GetRowValues();     
    
     if ($dia_semana == '')
     {
        $dia_semana = 'NI';
     }

     if ($turno == '')
     {
        $turno = 'NI';
     }
    
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Oferta e Curso</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cod</b></font></td>");
         echo ("<td width=\"35%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Professor</b></font></td>");
         echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("</tr>"); 

         echo ("<tr>");
         echo ("<td bgcolor=\"#009999\" colspan=\"5\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Dia da Semana: $dia_semana Turno: $turno</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$ref_curso: $curso - $campus</b></font></td>");
         echo ("</tr>"); 
         $ref_curso_  = $ref_curso;
         $ref_campus_ = $ref_campus;
         $dia_semana_ = $dia_semana;
         $turno_      = $turno;
     }

     if (($ref_curso_ <> $ref_curso) || ($ref_campus_ <> $ref_campus))
     {
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" align=\"right\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL CURSO: $total_curso</b></font></td>");
         echo ("</tr>"); 
         $total_curso = 0;
         echo ("<tr>");
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"5\"><hr></td>");
         echo ("</tr>"); 
     }
     
     if (($dia_semana_ <> $dia_semana) || ($turno_ <> $turno))
     {
         echo ("<tr>");
         echo ("<td bgcolor=\"#009999\" colspan=\"5\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Dia da Semana: $dia_semana Turno: $turno</b></font></td>");
         echo ("</tr>"); 
         $dia_semana_ = $dia_semana;
         $turno_      = $turno;
     }     
     
     if (($ref_curso_ <> $ref_curso) || ($ref_campus_ <> $ref_campus))
     {
         echo ("<tr>"); 
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$ref_curso: $curso - $campus</b></font></td>");
         echo ("</tr>"); 
         $ref_curso_  = $ref_curso;
         $ref_campus_ = $ref_campus;
     }
     
     
     if ( $i % 2 )
     {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$id</td>");
          echo ("<td width=\"35%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina - $disciplina</td>");
          echo ("<td width=\"30%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$professor</td>");
          echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$count</td>");
          echo("  </tr>");
          $total_curso += $count;
      }
      else
      {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$id</td>");
          echo ("<td width=\"35%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina - $disciplina</td>");
          echo ("<td width=\"30%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$professor</td>");
          echo ("<td width=\"15%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$count</td>");
          echo("  </tr>\n");
          $total_curso += $count;
      }
      $i++;
      $total=$total+$count;      
   }
      
   echo("<tr><td colspan=\"5\"<hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   $data_geracao = Invdata($data_geracao);

   ListaCursos($ref_periodo, $data_geracao);
</script>
<div align="center">
  <input type="button" name="Button" value="  Voltar  " onclick="javascript:history.go(-1)">
</div>

</form>
</body>
</html>
