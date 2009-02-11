<? require("../../../lib/common.php"); ?>
<? require("../lib/InvData.php3"); ?>
<? require("../lib/GetField.php3"); ?>
<html>
<head>
<title>Número de Alunos por Curso</title>
<script language="JavaScript">

function Select_Disciplina(id)
{
  var url = "altera_salas_ofer.phtml" +
            "?ref_disciplina_ofer=" + escape(id);

  location = url;
}

function Select_Alunos(id, prof_nome, dt_livro_matricula, anterior)
{
  var url = "cons_numalu_alunos.php3" +
            "?ref_disciplina_ofer=" + escape(id) + 
            "&nome_prof=" + escape(prof_nome) +
            "&dt_livro_matricula=" + escape(dt_livro_matricula) +
            "&anterior=" + escape(anterior);
  
  location = url; 
}
</script>

<script language="JavaScript">
function Mostra_Todos(ref_curso, dt_livro_matricula, anterior)
{
  var url = "cons_alunos_ordem_mat.php3" +
            "?ref_curso=" + escape(ref_curso) +
            "&dt_livro_matricula=" + escape(dt_livro_matricula) +
            "&anterior=" + escape(anterior);

  location = url; 
}
</script>


<script language="PHP">
function ListaDisciplinas($ref_curso, $ref_campus, $ref_periodo, $dt_livro_matricula, $anterior)
{
   $conn = new Connection;

   $conn->Open();

   $total=0;

   $sql = " select distinct  A.id,  " .
          "                  A.ref_curso,  " .
          "                  A.ref_periodo,  " .
          "                  A.ref_disciplina,  " .
          "                  descricao_disciplina(A.ref_disciplina),  " .
          "                  A.ref_campus, " .
          "                  get_campus(A.ref_campus),  " .
          "                  dia_disciplina_ofer_todos(A.id), ".
          "                  get_dia_semana_abrv(dia_disciplina_ofer_todos(A.id)), ".
          "                  num_sala_disciplina_ofer_todos(A.id), ".
          "                  professor_disciplina_ofer(A.id),".
          "                  A.num_alunos, ".
          "                  A.is_cancelada, " .
          "                  count(*)  " .
          " from disciplinas_ofer A, matricula B " .
          " where A.ref_periodo = B.ref_periodo and " .
          "       A.ref_curso='$ref_curso' and " .
          "       A.ref_campus='$ref_campus' and " .
          "       A.ref_periodo='$ref_periodo' and " .  
          "       B.ref_periodo='$ref_periodo' and  " .
          "       A.id=B.ref_disciplina_ofer and " .
          "       B.obs_aproveitamento = '' and ";
          
          if ($anterior)
          {
            $sql .= " (B.dt_cancelamento is null or B.dt_cancelamento > '$dt_livro_matricula') ";
          }
          else
          {
            $sql .= " B.dt_cancelamento is null ";
          }
          
   $sql.= " group by A.ref_disciplina, " . 
          "          B.ref_disciplina_ofer,  " . 
          "          A.id,  " .
          "          A.ref_campus, " .
          "          A.ref_curso, " .
          "          A.ref_periodo, " .
          "          A.num_alunos, ".
          "          A.is_cancelada ".
          " order by dia_disciplina_ofer_todos(A.id), " .
          "          A.ref_disciplina; " ;
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=0;

   // cores fundo
   $bg0 = "#000000";
   $bg1 = "#EEEEFF";
   $bg2 = "#FFFFEE";
 
   // cores fonte
   $fg0 = "#FFFFFF";
   $fg1 = "#000099";
   $fg2 = "#000099";

   $total_alunos = 0;
   
   while( $query->MoveNext() )
   {
     list ( $id, 
            $ref_curso, 
            $ref_periodo, 
            $ref_disciplina, 
            $disciplina, 
            $ref_campus, 
            $campus, 
            $dia,
            $dias,
            $sala,
    	    $nome_prof,
	        $num_alunos,
            $is_cancelada,
            $numero) = $query->GetRowValues();

     $total_alunos += $numero;
     
     if ($is_cancelada)
     {
        $is_cancelada = "<font color=red><b>Cancelada</b></font>";
     }
     else
     {
        $is_cancelada = "<font color=blue><b>Confirmada</b></font>";
     }
    
     $desc_curso=GetField2($ref_curso, "abreviatura", "cursos", $conn);
     $desc_disc=GetField2($ref_disciplina, "descricao_disciplina", "disciplinas", $conn);

     $href = "<a href=\"javascript:Select_Alunos('$id', '$nome_prof','$dt_livro_matricula', '$anterior')\"> " . $ref_disciplina . "</a>";
     $href1 = "<a href=\"javascript:Mostra_Todos('$id','$dt_livro_matricula', '$anterior')\"> " . $desc_disc . "</a>";
     $href2 = "<a href=\"javascript:Divisao_de_Turma('$id', '$numero')\"><img src=\"../images/dividir.gif\" alt='Dividir a Turma' align='absmiddle' border=0></a>"; 
     $href3 = "<a href=\"javascript:Select_Disciplina($id)\">" . $num_alunos . "</a>";

     if ($i == 0)
     {
         echo ("<td bgcolor=\"#000099\" colspan=\"8\" height=\"28\ align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Número de Matriculados por Disciplina - " . $campus . "</b></font></td>");
         echo ("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"8\" height=\"28\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $id_curso . " - " . $desc_curso . "</b></font></td>");
         echo ("</tr>");
         
         if ($anterior)
         { $periodo_antigo = " Sim - Data Geração Livro Matrícula: " . InvData($dt_livro_matricula); }
         else
         { $periodo_antigo = " Não"; }
         echo("<tr>");
         echo ("<td bgcolor=\"#000099\" colspan=\"8\" height=\"28\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Período Antigo: <b>$periodo_antigo</b></font></td>");
         echo ("</tr>"); 
         
         echo ("<tr bgcolor=\"#000000\">\n");
         echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
         echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Div</b></font></td>"); 
         echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Dia</b></font></td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Disciplina</b></font></td>");
         echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Professor</b></font></td>");
         echo ("<td width=\"12%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Status</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Num.</b></font></td>");
         echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"1\" color=\"#ffffff\"><b>Sala</b></font></td>");
         echo ("  </tr>"); 
     }

     if ( $i % 2 )
        {
          echo("<tr bgcolor=\"$bg1\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href</td>");

          if($numero>$num_alunos) 
          { 
             echo ("<td width=\"5%\"  align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href2</td>"); 
          } 
          else 
          { 
             echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp</td>"); 
          } 

          echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$dias</td>");
          echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href1</td>");
          echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$nome_prof</td>");
          echo ("<td width=\"12%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$is_cancelada</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp $numero/$href3</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"1\" color=\"$fg1\">&nbsp $sala</td>");
          echo("  </tr>");
         }
      else
         {
          echo("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"5%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href</td>");

          if($numero>$num_alunos) 
          { 
             echo ("<td width=\"5%\"  align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$href2</td>"); 
          } 
          else 
          { 
             echo ("<td width=\"5%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">&nbsp</td>"); 
          } 

          echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dias</td>");
          echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href1</td>");
          echo ("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$nome_prof</td>");
          echo ("<td width=\"12%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$is_cancelada</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">&nbsp $numero/$href3</td>");
          echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"1\" color=\"$fg2\">&nbsp $sala</td>");
          echo("  </tr>\n");
         }
     $i++;
   }

   echo("<tr><td colspan=\"8\"><hr></td></tr>");

   echo("<tr>");
   echo("<td colspan=\"4\">&nbsp</td>");
   $media = $total_alunos/$i;
   $media = sprintf("%.2f", $media);
   
   echo("<td colspan=\"4\"><b>Média de Alunos por Disciplina: $total_alunos/$i = " . $media . " alunos.</b></td>");
   echo("</tr>");
   
   echo("<tr><td colspan=\"8\"><hr></td></tr>");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();
}
</script>
<script language="JavaScript">
function Divisao_de_Turma(id, numero)
{
  var num_turmas = prompt("Dividir em quantas turmas?","2");
  var url = "divide_turma.php3" +
            "?id=" + escape(id) +
            "&numero=" + escape(numero) +
            "&num_turmas=" + escape(num_turmas);

  location = url; 
}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="">
<script language="PHP">
    ListaDisciplinas($ref_curso, $ref_campus, $ref_periodo, $dt_livro_matricula, $anterior);
</script>
<div align="center"> 
  <input type="button" name="Button" value="  Voltar  "  onclick="javascript:history.go(-1)">
</div>
</form>
</body>
</html>
