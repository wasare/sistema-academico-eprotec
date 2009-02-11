<? require("../../../../lib/common.php"); ?>
<?
CheckFormParameters(array('periodo_id'));
?>
<html>
<head>
<title>Lista de Alunos que estão repetindo disciplinas - Ordem de Curso e Disciplina</title>

<script language="PHP">
function Lista_Alunos($periodo_id)
{
    $conn = new Connection;

    $conn->open();

    $sql = " select A.ref_pessoa, " .
           "        pessoa_nome(A.ref_pessoa), " .
           "        A.ref_curso, " .
           "        curso_desc(A.ref_curso), " .
           "        get_campus_contrato(A.ref_contrato), " .
           "        get_campus(get_campus_contrato(A.ref_contrato)), " .
           "        A.ref_disciplina, " .
	       "        descricao_disciplina(A.ref_disciplina), " .
           "        A.ref_periodo, " .
           "        A.nota_final " .
           " from matricula A, " .
           "      (select ref_pessoa, ref_disciplina " .
           "       from matricula " .
           "       where ref_periodo = '$periodo_id' and " .
           "             dt_cancelamento is null and " .
           "             (fl_liberado = '' or fl_liberado = '1')) as B " .
           " where A.ref_pessoa = B.ref_pessoa and " .
           "       A.ref_disciplina = B.ref_disciplina and " .
           "       A.nota_final > 0 and " .
           "       nota_final < get_media_final(ref_periodo) and " .
           "       A.fl_liberado <> '2' and " .
           "       A.ref_periodo <> '$periodo_id' and " .
           "       A.dt_cancelamento is null " .
           " order by A.ref_curso, get_campus_contrato(A.ref_contrato), descricao_disciplina(A.ref_disciplina), pessoa_nome(A.ref_pessoa);";
   
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;
   $total_alunos = 0;
   $total_cursos = 0;
   
   $aux_ref_curso = 0;
   $aux_ref_campus = 0;
   $aux_ref_disciplina = 0;

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
     list ($ref_pessoa,
           $nome_pessoa,
           $ref_curso,
           $nome_curso,
           $ref_campus,
           $campus,
           $ref_disciplina,
           $nome_disciplina,
           $ref_periodo,
           $nota_final) = $query->GetRowValues();
        
        $nota_final = sprintf("%.2f", $nota_final);

        if ($i == 1)
        {
	        echo("<tr>");
            echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"4\"><font size=\"3\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#FFFFFF\"><center><b>Listagem de Alunos que estão repetindo disciplinas em $periodo_id</b></center></font></td>");
	        echo("</tr>");
            echo ("<tr bgcolor=\"#000000\">\n");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
            echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nome do Aluno</b></font></td>");
            echo ("<td width=\"20%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Reprovou em</b></font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nota Final</b></font></td>");
            echo ("  </tr>"); 

        }

        if (($ref_curso != $aux_ref_curso) || ($ref_campus != $aux_ref_campus))
        {
            $aux_ref_curso = $ref_curso;
            $aux_ref_campus = $ref_campus;

            echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");
            echo("<tr bgcolor=\"$bg\">\n");
            echo ("<td colspan=\"4\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"blue\"><b>Curso: $ref_curso - $nome_curso - Campus: $campus</b></td>");
            echo("</tr>");
            
            if ($ref_disciplina == $aux_ref_disciplina)
            {
                echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");
                echo("<tr bgcolor=\"$bg\">\n");
                echo ("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"red\"><b>Disciplina: $ref_disciplina - $nome_disciplina</b></td>");
                echo("</tr>");
            }
            $total_cursos++;
        }

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

        if ($ref_disciplina != $aux_ref_disciplina)
        {
            $aux_ref_disciplina = $ref_disciplina;

            echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");
            echo("<tr bgcolor=\"$bg\">\n");
            echo ("<td colspan=\"4\"><Font face=\"Verdana\" size=\"2\" color=\"red\"><b>Disciplina: $ref_disciplina - $nome_disciplina</b></td>");
            echo("</tr>");
        }

        echo("<tr bgcolor=\"$bg\">\n");
        echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_pessoa</td>");
        echo ("<td width=\"60%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$nome_pessoa</td>");
        echo ("<td width=\"20%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_periodo</td>");
        echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$nota_final</td>");
        echo("</tr>");
        
        $i++;
   }
   
   echo("<tr><td colspan=\"4\" align=\"center\"><hr></td></tr>");

   echo ("<tr bgcolor=\"#000000\">\n");
   echo ("<td colspan=\"2\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Total de Cursos/Campus:</b></font></td>");
   echo ("<td colspan=\"2\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>$total_cursos</b></font></td>");
   echo ("</tr>"); 

   echo ("<tr><td colspan=\"4\" align=center>");
   echo ("<hr size=1>");
   echo ("<input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\">");
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
    Lista_Alunos($periodo_id);
  </script>
</form>
</body>
</html>
