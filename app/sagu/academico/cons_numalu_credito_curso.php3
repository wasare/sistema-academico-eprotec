<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Número de Alunos por Número de Créditos</title>
<script language="PHP">

CheckFormParameters(array("ref_periodo"));

function ListaCursos($ref_periodo, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;
   $conn->Open();
   $conn->Begin();

   $sql = " CREATE TEMP TABLE creditos_temp ".
          " AS SELECT ref_campus, ".
          "           ref_curso, ".
          "           ref_pessoa, ";
          
          if ($anterior=='true')
          {
            $sql.= " num_creditos2(ref_pessoa, '$ref_periodo', '$dt_livro_matricula') as soma ";
          }
          else
          {
            $sql.= " num_creditos2(ref_pessoa, '$ref_periodo', date(now())+1) as soma ";
          }
          
   $sql .=" FROM contratos ".
          " WHERE ";

          if($anterior=='true')
          {
	      $sql .= " id in (select distinct ref_contrato " .
                  "       from matricula " .
                  "       where ref_periodo = '$ref_periodo' and " .
                  "             obs_aproveitamento = '' and " .
                  "             (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
	              " ref_pessoa in (select distinct ref_pessoa " .
                  "       from matricula " .
                  "       where ref_periodo = '$ref_periodo' and " .
                  "             obs_aproveitamento = '' and " .
                  "             (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
		          " (dt_desativacao is null or dt_desativacao > '$dt_livro_matricula') and ";
          }
          else
          {
          $sql .= " ref_last_periodo='$ref_periodo' and " .
          	      " id = is_matriculado_cntr('$ref_periodo', id) and " . 
           	      " dt_desativacao is null and ";
	  }

   $sql.= "       ref_curso<>6 AND " .
          "       fl_ouvinte<>'1' " .
          " GROUP BY ref_curso, " .
          "          ref_campus, " .
          "          ref_pessoa, " .
          "          ref_last_periodo" ;
   
   $ok = $conn->Execute($sql);
   
   $sql = " SELECT ref_curso, ".
          "        get_curso_abrv(ref_curso),".
          "        ref_campus,". 
          "        get_campus(ref_campus),". 
          "        soma, ".
          "        count(*) ".
          " FROM creditos_temp ".
          " GROUP BY ref_curso, ".
          "          ref_campus,". 
          "          soma " .
          " ORDER BY ref_curso, ".
          "          ref_campus,". 
          "          soma " ;
          
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;

   // cores fundo
   $bg0 = "#DDFFDD";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#000000";
   $fg1 = "#000099";
   $fg2 = "#000099";
   
   while( $query->MoveNext() )
   {
     list ( $ref_curso,
            $curso,
            $ref_campus,
            $campus, 
            $creditos,
            $count) = $query->GetRowValues();     
    
     $href  = "<a href=\"javascript:Lista_Alunos_Curso('$ref_curso', '$ref_campus', '$creditos','$ref_periodo', '$dt_livro_matricula', '$anterior')\"> " . $curso . "</a>";
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Crédito/Curso</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>"); 

         if ($anterior == 'true')
         { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
         else
         { $periodo_antigo = " Não"; }
         
         echo("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"7\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo:<b>$periodo_antigo</b></font></td>");
         echo ("</tr>"); 
         
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Código</b></font></td>");
         echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Curso</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Créditos</b></font></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("  </tr>"); 
         $aux_curso  = $curso;
         $aux_campus = $campus;
     }
     
     if(($curso != $aux_curso) || ($campus != $aux_campus))
     {
         $aux_curso  = $curso;
         $aux_campus = $campus;
         
         echo("<tr bgcolor=\"$bg0\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
         echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
         echo ("<td width=\"20%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL DE ALUNOS:</b></td>");
         echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total</b></td>");
         echo("  </tr>\n");
         $total=0;
     }

     if ( $i % 2 )
     {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_curso</td>");
          echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$campus</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$creditos</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$count</td>");
          echo("  </tr>");
      }
      else
      {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>");
          echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$campus</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$creditos</td>");
          echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$count</td>");
          echo("  </tr>\n");
      }
      $i++;
      $total=$total+$count;
      $total_geral+=$count;
      
   }
       
   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"20%\" colspan=2><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL DE ALUNOS:</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total</b></td>");
   echo("  </tr>\n");

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"20%\" colspan=2><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL GERAL:</b></td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total_geral</b></td>");
   echo("  </tr>\n");

   echo("<tr>");
   echo("<td colspan=\"5\"><hr></td>");
   echo("</tr>\n");
   
   echo("<form>");
   echo("<tr>");
   echo("<td colspan=\"5\" align=\"center\">");
   echo("<input type=\"button\" name=\"Button\" value=\"  Voltar  \" onClick=\"location='cons_numalu.phtml'\"></td>");
   echo("</tr>\n");
   echo("</form>");
   
   echo("</table></center>");

   $query->Close();

   $conn->Finish();
   $conn->Close();
}
</script>

<script language="JavaScript">
function Lista_Alunos_Curso(ref_curso, ref_campus, creditos, ref_periodo, dt_livro_matricula, anterior)
{
  var url = "lista_alunos_creditos.php3" +
            "?ref_curso=" + escape(ref_curso) +
            "&ref_campus=" + escape(ref_campus) +
            "&creditos=" + escape(creditos) +
            "&ref_periodo=" + escape(ref_periodo) + 
            "&dt_livro_matricula=" + escape(dt_livro_matricula) + 
            "&anterior=" + escape(anterior);

  location = url; 
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<script language="PHP">
   $dt_livro_matricula = Invdata($dt_livro_matricula);
   ListaCursos($ref_periodo, $dt_livro_matricula, $anterior);
</script>
</body>
</html>
