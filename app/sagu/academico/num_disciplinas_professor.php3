<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<?
    $dia = array('','Domingo','Segunda-Feira','Terça-Feira','Quarta-Feira','Quinta-Feira','Sexta-Feira','Sábado','Intensivo');
?>
<html>
<head>
<title>Número de Disciplinas por Professor</title>
<?
function ListaCursos($ref_periodo, $data_geracao)
{
   global $dia;

   $conn = new Connection;
   $conn->open();

   $total=0;

   $sql = " SELECT E.ref_professor, ".
         "         pessoa_nome(E.ref_professor), " .
         "         D.ref_curso," .
         "         D.ref_campus," .
         "         get_curso_abrv(D.ref_curso), " .
         "         get_campus(D.ref_campus)," .
         "         count(distinct A.ref_disciplina_ofer) ".
         " FROM matricula A, livro_matricula B, status_matricula C, disciplinas_ofer D, disciplinas_ofer_prof E ".
         " WHERE A.ref_disciplina_ofer = D.id and ".
         "       A.ref_disciplina_ofer = E.ref_disciplina_ofer and " .
         "       E.ref_disciplina_ofer = D.id and ".
         "       A.ref_pessoa = B.ref_pessoa and " .
         "       A.ref_periodo = B.ref_periodo and " .
         "       A.ref_periodo='$ref_periodo' and ".
         "       B.ref_status = C.id and " .
         "       C.fl_in_lm = 'f' and " . 
         "       (A.dt_cancelamento is null or dt_cancelamento > '$data_geracao') and ".
         "       D.is_cancelada = '0' and ".
         "       B.ref_curso_atual<>6  " .
         " GROUP BY E.ref_professor, ".
         "          D.ref_curso, " .
         "          D.ref_campus" .
         " ORDER BY D.ref_campus, D.ref_curso, pessoa_nome(E.ref_professor)";
  
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"75%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >");

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

   while( $query->MoveNext() )
   {
     list ( $ref_professor, 
            $professor,
            $ref_curso,
            $ref_campus,
            $curso,
            $campus,
            $count) = $query->GetRowValues();     
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de turmas por professor e por curso e campus</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cont</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cod</b></font></td>");
         echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Professor</b></font></td>");
         echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("</tr>"); 
         
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$ref_curso: $curso - $campus</b></font></td>");
         echo ("</tr>"); 
         $ref_curso_  = $ref_curso;
         $ref_campus_ = $ref_campus;
     }

     if (($ref_curso_ <> $ref_curso) || ($ref_campus_ <> $ref_campus))
     {
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" align=\"right\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL CURSO: $total_curso</b></font></td>");
         echo ("</tr>");

         echo ("<tr>");
         echo ("<td bgcolor=\"#FFFFFF\" colspan=\"4\"><hr></td>");
         echo ("</tr>"); 
         
         echo ("<tr>"); 
         echo ("<td bgcolor=\"#000099\" colspan=\"4\" height=\"28\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>$ref_curso: $curso - $campus</b></font></td>");
         echo ("</tr>"); 

         $total_curso = 0;
         
         $ref_curso_  = $ref_curso;
         $ref_campus_ = $ref_campus;
     }
     
     if ( $i % 2 )
     {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_professor</td>");
          echo ("<td width=\"60%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$professor</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$count</td>");
          echo("  </tr>");
          $total_curso += $count;
      }
      else
      {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_professor</td>");
          echo ("<td width=\"60%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$professor</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$count</td>");
          echo("  </tr>\n");
          $total_curso += $count;
      }
      $i++;
      $total=$total+$count;      
   }

   echo ("<tr>");
   echo ("<td bgcolor=\"#000099\" colspan=\"4\" align=\"right\"> <font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>TOTAL CURSO: $total_curso</b></font></td>");
   echo ("</tr>");
   echo ("<tr>");
   echo ("<td bgcolor=\"#FFFFFF\" colspan=\"4\"><hr></td>");
   echo ("</tr>"); 
       
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
?>
</head>

<body bgcolor="#FFFFFF">
<form method="post" action="">
  <p>
    <?
       $data_geracao = Invdata($data_geracao);
       ListaCursos($ref_periodo, $data_geracao);
    ?>
  </p>
</form>
</body>
</html>
