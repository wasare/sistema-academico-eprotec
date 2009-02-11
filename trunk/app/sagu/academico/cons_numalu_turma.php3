<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<?
    $dia = array('','Domingo','Segunda-Feira','Terça-Feira','Quarta-Feira','Quinta-Feira','Sexta-Feira','Sábado','Intensivo');
?>
<html>
<head>
<title>Número de Alunos por Turma</title>
<?

CheckFormParameters(array("ref_periodo"));

function ListaCursos($ref_periodo, $dt_livro_matricula, $anterior)
{
   global $dia;

   $conn = new Connection;
   $conn->Open();

   $total=0;

   $sql = " SELECT C.id, ".
         "         C.ref_disciplina, ".
         "         descricao_disciplina(C.ref_disciplina), ".
         "         C.ref_curso, " .
         "         C.ref_campus," .
         "         get_curso_abrv(C.ref_curso), " .
         "         get_campus(C.ref_campus)," .
         "         D.dia_semana,".
         "         count(*) ".
         " FROM matricula A, contratos B, disciplinas_ofer C, disciplinas_ofer_compl D ".
         " WHERE A.ref_disciplina_ofer = C.id and ".
         "       D.ref_disciplina_ofer = C.id and ".
         "       A.ref_contrato=B.id and ".
         "       A.ref_periodo='$ref_periodo' and ".
         "       A.obs_aproveitamento = '' and ";
         
         if ($anterior=='true')
         {
           $sql .= " (A.dt_cancelamento is null or A.dt_cancelamento > '$dt_livro_matricula') and ";
         }
         else
         {
           $sql .= " A.dt_cancelamento is null and ";
         }
         
   $sql.= "      C.is_cancelada = '0' ".
         " GROUP BY C.id, ".
         "          C.ref_disciplina, ".
         "          C.ref_curso, " .
         "          C.ref_campus," .
         "          D.dia_semana".
         " ORDER BY D.dia_semana, C.ref_campus, C.ref_curso, C.ref_disciplina";
          
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >");

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
   
   while( $query->MoveNext() )
   {
     list ( $id, 
            $ref_disciplina,
            $disciplina, 
            $ref_curso,
            $ref_campus,
            $curso,
            $campus,
            $dia_semana,
            $count) = $query->GetRowValues();     
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Turma e Dia da Semana</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>");

         if ($anterior=='true')
         { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
         else
         { $periodo_antigo = " Não"; }
         echo("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo: <b>$periodo_antigo</b></font></td>");
         echo ("</tr>"); 
         
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cod</b></font></td>");
         echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("</tr>"); 

         echo ("<tr>");
         echo ("<td bgcolor=\"#009999\" colspan=\"3\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>" . $dia[$dia_semana]."</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$ref_curso: $curso - $campus</b></font></td>");
         echo ("</tr>"); 
         $ref_curso_  = $ref_curso;
         $ref_campus_ = $ref_campus;
         $dia_semana_ = $dia_semana;
     }

     if (($ref_curso_ <> $ref_curso) || ($ref_campus_ <> $ref_campus))
     {
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" align=\"right\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL CURSO: $total_curso</b></font></td>");
         echo ("</tr>"); 
         $total_curso = 0;
         echo ("<tr>");
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"3\"><hr></td>");
         echo ("</tr>"); 
     }
     
     if ($dia_semana_ <> $dia_semana)
     {
         echo ("<tr>");
         echo ("<td bgcolor=\"#009999\" colspan=\"3\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>" . $dia[$dia_semana]."</b></font></td>");
         echo ("</tr>"); 
         $dia_semana_ = $dia_semana;
     }     
     
     if (($ref_curso_ <> $ref_curso) || ($ref_campus_ <> $ref_campus))
     {
         echo ("<tr>"); 
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$ref_curso: $curso - $campus</b></font></td>");
         echo ("</tr>"); 
         $ref_curso_  = $ref_curso;
         $ref_campus_ = $ref_campus;
     }
     
     
     if ( $i % 2 )
     {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$id</td>");
          echo ("<td width=\"70%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina - $disciplina</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$count</td>");
          echo("  </tr>");
          $total_curso += $count;
      }
      else
      {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$id</td>");
          echo ("<td width=\"70%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina - $disciplina</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$count</td>");
          echo("  </tr>\n");
          $total_curso += $count;
      }
      $i++;
      $total=$total+$count;      
   }
   
   echo("<tr><td colspan=\"3\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
?>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   $dt_livro_matricula = Invdata($dt_livro_matricula);
   ListaCursos($ref_periodo, $dt_livro_matricula, $anterior);
</script>
<div align="center">
  <input type="button" name="Button" value="  Voltar  " onClick="location='cons_numalu.phtml'">
</div>
</form>
</body>
</html>
