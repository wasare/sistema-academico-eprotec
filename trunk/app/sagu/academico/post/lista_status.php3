<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<?
CheckFormParameters(array('periodo_id',
            			  'curso_id',
            			  'campus_id',
            			  'status_matricula'));
?>
<html>
<head>
<title>Lista de Alunos por Status da Matrícula</title>

<script language="PHP">
function Lista_Alunos($periodo_id, $curso_id, $campus_id, $idx_status)
{
   global $status_matricula;
   
   $conn = new Connection;

   $conn->open();

   $sql = " select curso_desc($curso_id);";
   
   $query = $conn->CreateQuery($sql);

   $query->MoveNext();
   
   $nome_curso = $query->GetValue(1);
   
   $query->Close();

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Lista de Alunos por Status da Matrícula - $status_matricula[$idx_status]</b></center></font></td>");
   echo("</tr>");
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"7\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Curso: $curso_id - $nome_curso &nbsp;&nbsp;&nbsp; Campus:$campus_id</center></font></td>");
   echo("</tr>");
   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
   echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Aluno</b></font></td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
   echo ("<td width=\"34%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
   echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Período</b></font></td>");
   echo ("<td width=\"7%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Status</b></font></td>");
   echo ("<td width=\"8%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nota</b></font></td>");
   echo ("  </tr>"); 

   $sql = " select id, " .
          "        ref_pessoa, " .
          "        pessoa_nome(ref_pessoa), " .
    	  "        ref_disciplina, " .
    	  "        descricao_disciplina(ref_disciplina), " .
    	  "        nota_final, " .
    	  "        ref_periodo, " .
    	  "        fl_liberado " .
	      " from matricula " .
    	  " where ref_periodo = '$periodo_id' and " .
    	  "       ref_curso = '$curso_id' and " .
    	  "       ref_campus = '$campus_id' and " .
    	  "       dt_cancelamento is null and ";
          
          if ($idx_status == '1')     // Reprovados
          {
            $sql .= " (fl_liberado = '$idx_status' or (nota_final < get_media_final(ref_periodo) and fl_liberado <> '2')) ";
          }
          elseif ($idx_status == '3') // Aprovados
          {
            $sql .= " (fl_liberado = '$idx_status' or conceito <> '' or nota_final >= get_media_final(ref_periodo)) ";
          }
          else
          {
	        $sql .= " fl_liberado = '$idx_status' ";
          }
	      
          $sql .= " order by pessoa_nome(ref_pessoa);";

   $query = $conn->CreateQuery($sql);

   $i=1;
   $total = 0;

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
     list ($id,
           $ref_pessoa,
           $nome_pessoa,
           $ref_disciplina,
           $nome_disciplina,
           $nota_final,
           $ref_periodo,
           $fl_liberado) = $query->GetRowValues();

     $nota_final = sprintf("%.2f", $nota_final);
     
     $href  = "<a href=\"/academico/matricula_altera.phtml?id=$id\">$nome_disciplina</a>";
      
     if ( $i % 2 )
     {
       echo("<tr bgcolor=\"$bg1\">\n");
       echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
       echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
       echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
       echo ("<td width=\"34%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");
       echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_periodo</td>");
       echo ("<td width=\"7%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$fl_liberado</td>");
       echo ("<td width=\"8%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nota_final</td>");
       echo("  </tr>");
      }
      else
      {
       echo("<tr bgcolor=\"$bg2\">\n");
       echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
       echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_pessoa</td>");
       echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
       echo ("<td width=\"34%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");
       echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_periodo</td>");
       echo ("<td width=\"7%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$fl_liberado</td>");
       echo ("<td width=\"8%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nota_final</td>");
       echo("  </tr>\n");
      }
     $i++;
     $total++;
   }
   echo("</tr>");

   if ($i == 1)
   {
       echo ("<tr>");
       echo ("<td colspan=\"7\" align=\"center\"><font face=\"Verdana\" size=\"3\" color=\"red\"><b>Nenhum aluno se encaixou na restrição...</b></font></td>");
       echo ("</td></tr>\n");
   }

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"6\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Alunos:</b></font></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total alunos</b></font></td>");
   echo ("  </tr>"); 
  
   echo ("<tr><td colspan=\"7\" align=\"center\"><hr size=1>");
   echo ("  <input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\">");
   echo ("</td></tr>\n");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Alunos($periodo_id, $curso_id, $campus_id, $idx_status);
  </script>
</form>
</body>
</html>
