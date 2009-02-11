<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<html>
<head>
<title>Número de Alunos por Curso</title>
<script language="PHP">
function ListaCursos($ref_periodo, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;

   $conn->open();

   $total=0;

   $sql = " select ref_curso, " .
          "        get_campus(ref_campus), " .
          "        get_curso_abrv(ref_curso), " .
          "        count(*), " .
          "        ref_campus  " .
          " from contratos " .
          " where ";
          
          if($anterior)
          {
	      $sql .= " id in (select distinct ref_contrato " .
                  "       from matricula " .
                  "       where ref_periodo = '$ref_periodo' and " .
                  "             trim(obs_aproveitamento) = '' and " .
                  "             (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
	              "             ref_pessoa in (select distinct ref_pessoa " .
                  "                            from matricula " .
                  "                            where ref_periodo = '$ref_periodo' and " .
                  "                                  trim(obs_aproveitamento) = '' and " .
                  "                                  (dt_cancelamento is null or dt_cancelamento > '$dt_livro_matricula')) and " . 
		          "                                  (dt_desativacao is null or dt_desativacao > '$dt_livro_matricula') and ";
          }
          else
          {
          $sql .= " ref_last_periodo='$ref_periodo' and " .
          	      " id = is_matriculado_cntr('$ref_periodo', id) and " . 
           	      " dt_desativacao is null and ";
	      }
              
   $sql.= "       ref_curso<>6 and " .
          "       fl_ouvinte<>'1' " . 
          " group by ref_curso, ref_campus " .
          " order by get_sequencia_curso(ref_curso), ref_curso, ref_campus";
  
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
     list ( $ref_curso,
            $campus, 
            $curso,
            $numero, 
            $ref_campus) = $query->GetRowValues();

     $href  = "<a href=\"javascript:Disciplinas_Curso('$ref_curso', '$ref_campus', '$ref_periodo','$dt_livro_matricula', '$anterior')\"> " . $ref_curso . "</a>";
     $href1 = "<a href=\"javascript:Alunos_Curso('$ref_curso', '$ref_campus', '$ref_periodo','$dt_livro_matricula', '$anterior')\"> " . $curso . "</a>";
     $href2 = "<a href=\"javascript:Idade_Alunos('$ref_curso', '$ref_campus', '$ref_periodo','$dt_livro_matricula', '$anterior')\">Idade</a>";
     
     if ($i == 1)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Curso</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período: " . $ref_periodo . "</b></font></td>");
         echo ("</tr>"); 
         if ($anterior)
         { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
         else
         { $periodo_antigo = " Não"; }
         echo("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"5\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo:<b>$periodo_antigo</b></font></td>");
         echo ("</tr>"); 
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Campus</b></font></td>");
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Idade</b></font></td>");
         echo ("<td width=\"72%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Descrição do Curso</b></font></td>");
         echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("  </tr>"); 
        }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$campus</td>");
          echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href2</td>");
          echo ("<td width=\"72%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href1</td>");
          echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$numero</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$campus</td>");
          echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href2</td>");
          echo ("<td width=\"72%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href1</td>");
          echo ("<td width=\"5%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$numero</td>");
          echo("  </tr>\n");
         }

     $i++;

     $total=$total+$numero;

   }

   echo("<tr bgcolor=\"$bg0\">\n");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">&nbsp</td>");
   echo ("<td width=\"80%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">TOTAL DE ALUNOS:</td>");
   echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg0\">$total</td>");
   echo("  </tr>\n");

   echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");
   echo("</table></center>");

   $query->Close();

   $conn->Close();
}
</script>
<script language="JavaScript">
function Disciplinas_Curso(ref_curso, ref_campus, ref_periodo, dt_livro_matricula, anterior)
{
  var url = "cons_numalu_disc.php3" +
            "?ref_curso=" + escape(ref_curso) + 
            "&ref_campus=" + escape(ref_campus) +
            "&ref_periodo=" + escape(ref_periodo) +
            "&dt_livro_matricula=" + escape(dt_livro_matricula) +
            "&anterior=" + escape(anterior);

  location = url; 
}

function Alunos_Curso(ref_curso, ref_campus, ref_periodo, dt_livro_matricula, anterior)
{
  var url = "lista_alunos_curso.php3" +
            "?ref_curso=" + escape(ref_curso) +
            "&ref_campus=" + escape(ref_campus) +
            "&ref_periodo=" + escape(ref_periodo) +
            "&dt_livro_matricula=" + escape(dt_livro_matricula) +
            "&anterior=" + escape(anterior);

  location = url; 
}

function Idade_Alunos(ref_curso, ref_campus, ref_periodo, dt_livro_matricula, anterior)
{
  var url = "lista_alunos_faixa_etariaT.phtml" +
            "?ref_curso=" + escape(ref_curso) +
            "&ref_campus=" + escape(ref_campus) +
            "&ref_periodo=" + escape(ref_periodo) +
            "&dt_livro_matricula=" + escape(dt_livro_matricula) +
            "&anterior=" + escape(anterior);

  location = url; 
}

</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
   $dt_livro_matricula = InvData($dt_livro_matricula);
   ListaCursos($ref_periodo, $dt_livro_matricula, $anterior);
</script>
<div align="center">
  <input type="button" name="Button" value="  Voltar  " onClick="location='cons_numalu.phtml'">
</div>
</form>
</body>
</html>
