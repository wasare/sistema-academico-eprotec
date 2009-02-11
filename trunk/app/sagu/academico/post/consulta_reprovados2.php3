<? require("../../../../lib/common.php"); ?>
<?
CheckFormParameters(array('periodo_id','num_disc','mostrar_professores'));
?>
<html>
<head>
<title>Listagem de Alunos que reprovaram</title>

<script language="PHP">
function Lista_Alunos($periodo_id, $num_disc, $mostrar_professores, $tipos_curso)
{
    $conn = new Connection;

    $conn->open();

    $periodos = str_replace(' ','', $periodo_id);
    $periodos = str_replace(',',"','", $periodos);
    $periodos = "'" . $periodos . "'";

    if ($tipos_curso)
    {
        $where = '(';
        foreach($tipos_curso as $key => $tipo)
        {
            $where .= " get_tipo_curso(B.ref_curso) = '$tipo' or";
        }
        $where = substr($where, 0, (strlen($where)-2));    
        $where .= ') and ';
    }
    else
    {
        $where = '';
    }
    
    $sql = " select A.ref_contrato, " .
           "        B.ref_curso, " .
           "        curso_desc(B.ref_curso), " .
           "        B.ref_campus, " .
           "        get_campus(B.ref_campus), " .
           "        A.ref_pessoa, " .
           "        pessoa_nome(A.ref_pessoa) " .
           " from matricula A, contratos B " .
           " where A.ref_contrato = B.id and " .
           "       A.ref_pessoa = B.ref_pessoa and " .
           "       A.ref_curso = B.ref_curso and " .
           "       A.dt_cancelamento is null and " .
           "       B.dt_desativacao is null and " .
           "       A.nota_final < get_media_final(A.ref_periodo) and " .
           "       (trim(A.fl_liberado) = '' or A.fl_liberado = '1') and " .
           "       trim(A.conceito) = '' and " .
           "       $where " .
           "       B.ref_last_periodo in ($periodos) and " .
           "       A.ref_periodo not in ($periodos) " .
           " group by B.ref_curso, " .
           "          B.ref_campus, " .
           "          A.ref_contrato, " .
           "          A.ref_pessoa " .
           " having count(*) >= '$num_disc'";
  
   $query = $conn->CreateQuery($sql);

   echo("<center><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");

   $i=1;
   $cont = 1;
   $aux_ref_curso = -1;
   $aux_ref_campus = -1;
   $aux_ref_pessoa = -1;
   
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
     list ($ref_contrato,
           $ref_curso,
           $nome_curso,
           $ref_campus,
           $nome_campus,
           $ref_pessoa,
           $nome_pessoa) = $query->GetRowValues();
        
        if ($i == 1)
        {
	        echo("<tr>");
            echo ("<td bgcolor=\"#000099\" height=\"30\" colspan=\"5\"><font size=\"3\" face=\"Verdana\" color=\"#FFFFFF\"><center><b>Listagem de Alunos que já repetiram $num_disc disciplinas ou mais, exceto as disciplinas matriculadas no período $periodo_id</b></center></font></td>");
	        echo("</tr>");
            echo("<tr><td colspan=\"5\" align=\"center\">&nbsp;</td></tr>");

        }

        if (($ref_curso != $aux_ref_curso) || ($ref_campus != $aux_ref_campus))
        {
            $aux_ref_curso = $ref_curso;
            $aux_ref_campus = $ref_campus;

            echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");
            echo("<tr bgcolor=\"#000099\">\n");
            echo ("<td colspan=\"5\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"white\"><b>Curso: $ref_curso - $nome_curso - Campus: $nome_campus</b></td>");
            echo("</tr>");
            echo ("<tr bgcolor=\"#000000\">\n");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Cód.</b></font></td>");
            echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nome do Aluno</b></font></td>");
            echo ("<td width=\"20%\" align=\"center\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Reprovou em</b></font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Nota Final</b></font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"3\" color=\"#ffffff\"><b>Excesso de Faltas</b></font></td>");
            echo ("  </tr>"); 
            
        }

        echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");
        echo("<tr bgcolor=\"$bg\">\n");
        echo ("<td colspan=\"5\"><Font face=\"Verdana\" size=\"2\" color=\"red\"><b>$cont - Aluno: $ref_pessoa - $nome_pessoa</b></td>");
        echo("</tr>");

        $sql2 = " select A.ref_disciplina, " .
                "        descricao_disciplina(A.ref_disciplina), " .
                "        A.ref_periodo, " .
                "        A.nota_final, " .
                "        trim(A.fl_liberado), " .
                "        professor_disciplina_ofer_todos(A.ref_disciplina_ofer) " .
                " from matricula A, contratos B " .
                " where A.ref_contrato = B.id and " .
                "       A.ref_pessoa = B.ref_pessoa and " .
                "       A.ref_curso = B.ref_curso and " .
                "       A.dt_cancelamento is null and " .
                "       B.dt_desativacao is null and " .
                "       A.nota_final < get_media_final(A.ref_periodo) and " .
                "       (trim(A.fl_liberado) = '' or A.fl_liberado = '1') and " .
                "       trim(A.conceito) = '' and " .
                "       B.ref_last_periodo in ($periodos) and " .
                "       A.ref_periodo not in ($periodos) and " .
                "       A.ref_contrato = '$ref_contrato' and " .
                "       B.ref_curso = '$ref_curso' and " .
                "       B.ref_pessoa = '$ref_pessoa' and " .
                "       get_tipo_curso(B.ref_curso) = '1' " .
                " order by A.ref_disciplina";

        $query2 = $conn->CreateQuery($sql2);
        
        while( $query2->MoveNext() )
        {
           list($ref_disciplina,
                $descricao_disciplina,
                $ref_periodo,
                $nota_final,
                $fl_liberado,
                $professor) = $query2->GetRowValues();

            $nota_final = sprintf("%.2f", $nota_final);
            
            if ($fl_liberado == '1')
            {
                $fl_liberado = 'Sim';
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

            if ($mostrar_professores == '1')
            {
                $professor = "<font face=\"Verdana\" size=\"1\" color=\"$fg\"><br><i>Prof. $professor</i></font>";
            }
            else
            {
                $professor = "&nbsp;";
            }
            
            echo("<tr bgcolor=\"$bg\">\n");
            echo ("<td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_disciplina</font></td>");
            echo ("<td width=\"50%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$descricao_disciplina</font>$professor</td>");
            echo ("<td width=\"20%\" align=\"center\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">$ref_periodo</font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"red\">$nota_final</font></td>");
            echo ("<td width=\"10%\" align=\"right\"><Font face=\"Verdana\" size=\"2\" color=\"$fg\">&nbsp;$fl_liberado</font></td>");
            echo("</tr>");
            
            $i++;
        }
        
        $cont++;
        
        $query2->Close();
   }
   
   echo("<tr><td colspan=\"5\" align=\"center\"><hr></td></tr>");

   echo ("<tr><td colspan=\"5\" align=\"center\"><input type=button value=' Voltar ' onClick=\"javascript:history.go(-1)\"></td></tr>\n");
   
   echo("</table></center>");

   @$query->Close();

   @$conn->Close();

}
</script>
</head>

<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<form method="post" action="" name="myform">
  <script language="PHP">
    Lista_Alunos($periodo_id, $num_disc, $mostrar_professores, $tipos_curso);
  </script>
</form>
</body>
</html>
