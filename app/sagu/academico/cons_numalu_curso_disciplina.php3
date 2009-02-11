<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Número de Alunos por Curso e Disciplina</title>
<?

CheckFormParameters(array("ref_periodo"));

function ListaCursos($ref_periodo, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;
   $conn->open();

   $total=0;

   $sql = " SELECT A.ref_disciplina, ".
          "        descricao_disciplina(A.ref_disciplina), ".
          "        B.ref_curso, ".
          "        get_curso_abrv(B.ref_curso), ".
          "        B.ref_campus, ".
          "        get_campus(B.ref_campus), ".
          "        count(*) ".
          " FROM matricula A, contratos B, disciplinas_ofer C ".
          " WHERE A.ref_disciplina_ofer = C.id and ".
          "       A.ref_contrato=B.id and ".
          "       A.ref_periodo='$ref_periodo' and " .
          "       A.obs_aproveitamento = '' and ";
          
          if ($anterior=='true')
          {
            $sql .= " (A.dt_cancelamento is null or A.dt_cancelamento > '$dt_livro_matricula') and ";
          }
          else
          {
            $sql .= " A.dt_cancelamento is null and ";
          }
          
   $sql.= "       C.is_cancelada = '0' ".
          " GROUP BY A.ref_disciplina, ".
          "          B.ref_curso, ".
          "          B.ref_campus".
          " ORDER BY B.ref_curso, ".
          "          B.ref_campus, ".
          "          descricao_disciplina(A.ref_disciplina)";
   
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
   
   while( $query->MoveNext() )
   {
     list ( $ref_disciplina,
            $disciplina, 
            $ref_curso,
            $curso,
            $ref_campus,
            $campus, 
            $count) = $query->GetRowValues();     
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"3\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Curso e Disciplina</b></font></td>");
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
         echo ("  </tr>"); 
         echo("<tr bgcolor=\"$bg0\">\n");
         echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"$fg0\"><b>$ref_curso - $curso - $campus</b></td>");
         echo("  </tr>\n");
         $curso_  = $curso;
         $campus_ = $campus;
     }
     
     if(($curso!=$curso_) || ($campus!=$campus_))
     {
         $curso_  = $curso;
         $campus_ = $campus;
         
         echo("<tr bgcolor=\"$bg0\">\n");
         echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
         echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL DE ALUNOS:</b></td>");
         echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total</b></td>");
         echo("  </tr>\n");
         echo("<tr bgcolor=\"$bg0\">\n");
         echo ("<td colspan=\"3\"><hr></td>");
         echo("  </tr>\n");
         echo("<tr bgcolor=\"$bg0\">\n");
         echo ("<td colspan=\"3\"><Font face=\"Verdana\" size=\"3\" color=\"$fg0\"><b>$ref_curso - $curso - $campus</b></td>");
         echo("  </tr>\n");
         $total=0;
     }

     if ( $i % 2 )
     {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_disciplina</td>");
          echo ("<td width=\"70%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$disciplina</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$count</td>");
          echo("  </tr>");
      }
      else
      {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_disciplina</td>");
          echo ("<td width=\"70%\" ><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$disciplina</td>");
          echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$count</td>");
          echo("  </tr>\n");
      }
      $i++;
      $total=$total+$count;      
   }
       
   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"70%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>TOTAL DE ALUNOS:</b></td>");
   echo ("<td width=\"20%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\"><b>$total</b></td>");
   echo("  </tr>\n");

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
