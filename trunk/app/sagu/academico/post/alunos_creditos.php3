<? require("../../../../lib/common.php"); ?>
<? require("../../lib/GetField.php3"); ?>
<html>
<head>
<title>Alunos por Curso e nº de Créditos</title>

<?
 CheckFormParameters(array("ref_periodo",
                           "ref_curso",
                           "ref_campus",
                           "num_creditos1",
                           "num_creditos2"));
?>

<script language="PHP">

//Função que retorna o total de horas aula cursadas pelo aluno
function get_total_creditos($ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, & $creditos_cursando)
{

    $conn = new Connection;
    $conn->open();

    $sql = " select sum(B.num_creditos), ".
           "        count(distinct A.ref_periodo) " .
           " from matricula A, disciplinas B ".
           " where A.ref_disciplina = B.id and ".
           "       A.ref_pessoa = '$ref_pessoa' and ".
           "       A.ref_curso = '$ref_curso' and ".
         //"       A.ref_campus = '$ref_campus' and ".
           "       A.dt_cancelamento is null and ".
           "       ( (A.nota_final >= get_media_final(A.ref_periodo)) or " .
           "         (A.conceito <> '') or " .
           "         (A.fl_liberado = '3') or " .
           "         (A.fl_liberado = '4') )";
    
    $query = $conn->CreateQuery($sql);

    if( $query->MoveNext() )
        list ($creditos_cursados,
              $semestres_cursados) = $query->GetRowValues();

    $sql = " select sum(B.num_creditos), ".
           "        count(distinct A.ref_periodo) " .
           " from matricula A, disciplinas B ".
           " where A.ref_disciplina = B.id and ".
           "       A.ref_pessoa = '$ref_pessoa' and ".
           "       A.ref_curso = '$ref_curso' and ".
         //"       A.ref_campus = '$ref_campus' and ".
           "       A.dt_cancelamento is null and ".
           "       A.nota_final = 0 and ".
           "       A.ref_pessoa = is_matriculado('$ref_periodo', A.ref_pessoa) and ".
           "       A.ref_periodo = '$ref_periodo';";

    $query = $conn->CreateQuery($sql);

    if( $query->MoveNext() )
        list ( $creditos_cursando,
               $semestres_cursando) = $query->GetRowValues();

    $creditos = $creditos_cursados + $creditos_cursando;
    $semestres = $semestres_cursados + $semestres_cursando;

    $obj = $creditos . ':' . $semestres;
    
    return $obj;

}
</script>

</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">

<form method="post" action="">

<?
$conn = new Connection;

$conn->open();

$sql = " select sum(B.num_creditos) ".
       " from cursos_disciplinas A, disciplinas B ".
       " where A.ref_disciplina = B.id and ".
       "       A.ref_curso = $ref_curso and ".
       "       A.ref_campus = $ref_campus and " .
       "       ((A.curriculo_mco = 'C') or (A.curriculo_mco = 'M')) and " .
       "       ((A.dt_final_curriculo>=date(now())) or (A.dt_final_curriculo is null));";

$query = $conn->CreateQuery($sql);

if ( @$query->MoveNext() )
    $creditos_curso = $query->GetValue(1);
else
    SaguAssert(0,"Não foi possível obter o número total de créditos do curso $ref_curso");

$query->Close();

$sql = " select id, " .
       "        ref_pessoa, " .
       "        pessoa_nome(ref_pessoa), " .
       "        ref_curso, " .
       "        curso_desc(ref_curso), " .
       "        pessoa_fone(ref_pessoa), " .
       "        ref_campus " .
       " from contratos " .
       " where ref_curso = '$ref_curso' and " .
       "       ref_campus = '$ref_campus' and " .
       "       ref_last_periodo = '$ref_periodo' and " .
       "       dt_desativacao is null " .
       " order by 3;";

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
     list ( $ref_contrato,
            $ref_pessoa,
            $pessoa_nome,
            $ref_curso, 
            $curso_desc,
    	    $fone,
	        $ref_campus) = $query->GetRowValues();

     $creditos_cursando = 0;
     
     $obj = get_total_creditos($ref_pessoa, $ref_curso, $ref_campus, $ref_periodo, $creditos_cursando);

     list($creditos_aluno, $semestres_aluno) = split(":", $obj, 2); 

     $creditos_restantes = $creditos_curso - $creditos_aluno;

     if ($creditos_cursando != '0')
     {
        $creditos_aluno = sprintf("%02.2f", $creditos_aluno);
    
        if (($creditos_restantes >= $num_creditos1) && ($creditos_restantes <= $num_creditos2))
        {
            if ($creditos_restantes < '0')
            {
                $creditos_restantes = sprintf("%02.2f",($creditos_restantes * -1)) . "+";
            }
            else
            {
                $creditos_restantes = sprintf("%02.2f", $creditos_restantes) . "&nbsp;";
            }
            if ($i == 1)
            {
                echo ("<tr>");
                echo ("<td bgcolor=\"#000099\" colspan=\"8\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de alunos com o seguinte intervalo de número de créditos para cursar ($num_creditos1...$num_creditos2)</b></font></td></tr>");
                
                echo ("<tr>");
                echo ("<td bgcolor=\"#000099\" colspan=\"8\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>(+) Alunos que cursaram créditos a mais.</b></font></td>");
                echo ("<tr>");
                echo ("<td bgcolor=\"#000099\" colspan=\"8\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $ref_curso . " - " . $curso_desc . " / " . $ref_periodo ."</b></font></td></tr>");

                echo ("<tr>");
                echo ("<td bgcolor=\"#000099\" colspan=\"8\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Total de Créditos: " . $creditos_curso ."</b></font></td>");
                echo ("</tr>");
                echo ("<tr bgcolor=\"#000000\">\n");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>&nbsp;</b></font></td>");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Cód</b></font></td>");
                echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Nome</b></font></td>");
                echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Fones</b></font></td>");
                echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Créditos<br>Cursados</b></font></td>");
                echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Créditos<br>Restantes</b></font></td>");
                echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Projeção Semestres Restantes baseado em 12 Cr/Sem</b></font></td>");
                echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"#ffffff\"><b>Projeção Semestres Restantes baseado em Cr/Sem/Aluno</b></font></td>");
                echo ("  </tr>"); 
            }
            
            if ( $i % 2 )
            {
                echo("<tr bgcolor=\"$bg1\">\n");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$i</td>");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$ref_pessoa</td>");
                echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$pessoa_nome</td>");
                echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$fone</td>");
                echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$creditos_aluno</td>");
                echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$creditos_restantes</td>");
                $sem_restantes = @(int)($creditos_restantes/12);
                echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$sem_restantes</td>");
                $sem_restantes1 = round(@$creditos_restantes/@($creditos_aluno/$semestres_aluno));
                echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\">$sem_restantes1</td>");
                echo("  </tr>");
            }
            else
            {
                echo("<tr bgcolor=\"$bg2\">\n");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$i</td>");
                echo ("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_pessoa</td>");
                echo ("<td width=\"28%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$pessoa_nome</td>");
                echo ("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$fone</td>");
                echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$creditos_aluno</td>");
                echo ("<td width=\"8%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$creditos_restantes</td>");
                $sem_restantes = @(int)($creditos_restantes/12);
                echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$sem_restantes</td>");
                $sem_restantes1 = round(@$creditos_restantes/@($creditos_aluno/@$semestres_aluno));
                echo ("<td width=\"10%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$sem_restantes1</td>");
                echo("  </tr>\n");
            }
            $i++;
            $total[$sem_restantes]++;
        }
     } //Fim do If
   }
   
   if ($i == 1)
   {
     echo ("<tr>");
     echo ("<td bgcolor=\"#000099\" colspan=\"8\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Listagem de alunos com o seguinte intervalo de número de créditos para cursar ($num_creditos1...$num_creditos2)</b></font></td></tr>");
     echo ("<tr>");
     echo ("<td bgcolor=\"#000099\" colspan=\"8\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Curso: " . $ref_curso . " - " . $curso_desc . " / " . $ref_periodo ."</b></font></td></tr>");
     echo ("<tr>");
     echo ("<td bgcolor=\"#000099\" colspan=\"8\"> <font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><b>Total de Créditos: " . $creditos_curso ."</b></font></td>");
     echo ("</tr>");
     echo ("<tr>");
     echo ("<td bgcolor=\"#EEEEFF\" colspan=\"8\" align=\"center\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"red\"><b>Nenhum aluno encaixa nesta restrição!!!</b></font></td>");
     echo ("<tr>");

   }
   echo("</table></center>");

   $query->Close();

   $conn->Close();

   @ksort($total);

   foreach($total as $sem => $t_alu)
         echo("Projeção Semestres Restantes baseado em 12 Cr/Sem: $sem = Total Alunos: $t_alu<br>");
?>
 <div align="center"> 
    <input type="button" name="Button" value="  Voltar  " onClick="location='../alunos_creditos.phtml'">
  </div>
</form>
</body>
</html>
