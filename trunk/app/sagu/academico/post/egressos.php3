<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Alunos Egressos</title>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<?

    CheckFormParameters(array("dt_inicial", "dt_final"));
 
    $conn = new Connection;
    $conn->Open();
  
    $sql =  " select A.id, " .
            "        A.nome, " .
            "        B.ref_curso, " .
            "        get_curso_abrv(B.ref_curso), " .
            "        B.ref_campus, " .
            "        get_campus(B.ref_campus), " .
            "        B.dt_conclusao " .
            " from pessoas A, contratos B " .
            " where A.id = B.ref_pessoa and " .
            "       B.fl_formando = '1' and " .
            "       B.dt_desativacao is not null and " .
            "       B.dt_conclusao between to_date('$dt_inicial','dd-mm-yyyy') and " .
            "                              to_date('$dt_final','dd-mm-yyyy') and " .
            // O aluno ativou pelo menos um contrato após a data de formatura e 
            // O aluno cursou pelo menos uma disciplina até o final no curso do contrato
            "       B.ref_pessoa in (select distinct CONT.ref_pessoa " .
            "                        from contratos CONT, matricula MAT " .
            "                        where CONT.id = MAT.ref_contrato and " .
            "                              CONT.ref_pessoa = MAT.ref_pessoa and " .
            "                              MAT.dt_cancelamento is null and " .
            "                              CONT.dt_ativacao > B.dt_conclusao and " .
            "                              CONT.ref_pessoa = B.ref_pessoa) ";
  
    if ( $ref_periodo )
    {
        $sql .= " and is_matriculado('$ref_periodo',B.ref_pessoa) = B.ref_pessoa";
    }     
    
    $sql .= " order by get_curso_abrv(B.ref_curso)," .
            "          B.ref_campus, " .
            "          A.nome;";

    $query = $conn->CreateQuery($sql);
  
    // cores fundo
    $bg0 = "#000000";
    $bg1 = "#EEEEFF";
    $bg2 = "#FFFFEE";
 
    // cores fonte
    $fg0 = "#FFFFFF";
    $fg1 = "#000099";
    $fg2 = "#000099";

    if ( $ref_periodo )
    {
        $title = "Alunos Egressos que se formaram entre $dt_inicial e $dt_final, voltaram a estudar após a data da formatura e matricularam-se em $ref_periodo";
    }
    else
    {
        $title = "Alunos Egressos que se formaram entre $dt_inicial e $dt_final e voltaram a estudar após a data da formatura";
    }
    echo("<center><br><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
    echo("<tr><td align=\"center\" bgcolor=\"#cccccc\" colspan=\"6\" height=\"28\"><font color=\"#000000\" size=\"4\"><b>$title</b></font></td></tr>\n");

    $control = 0;
    $i=1;

    while( $query->MoveNext() )
    {
        list ($ref_pessoa,
              $pessoa_nome,
              $ref_curso,
              $curso,
              $ref_campus,
              $campus,
              $dt_conclusao) = $query->GetRowValues();
  
        if ( $control != ($ref_curso . "-" . $ref_campus) )
        {  
            echo("<tr><td>&nbsp;</td></tr><tr><td align=\"center\" bgcolor=\"#777777\" colspan=\"6\" height=\"28\"><font color=\"#ffffff\"><b>[$ref_curso] - {$curso} - $campus</b></font></td></tr>\n");
            $control = $ref_curso . "-" . $ref_campus;

            echo("<tr bgcolor=\"#cccccc\">\n");
            echo("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>&nbsp;</b></td>");
            echo("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Código</b></td>");
            echo("<td width=\"27%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Pessoa</b></td>");
            echo("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Curso</b></td>");
            echo("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Campus</b></td>");
            echo("<td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Data Formatura</b></td>");
            echo("</tr>");
        }

        echo("<tr bgcolor=\"$bg1\">\n");
        echo("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$i</b></td>");
        echo("<td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$ref_pessoa</b></td>");
        echo("<td width=\"27%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$pessoa_nome</b></td>");
        echo("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$ref_curso - $curso</b></td>");
        echo("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$ref_campus - $campus</b></td>");
        echo("<td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>" . InvData($dt_conclusao) . "</b></td>");
        echo("</tr>");

        $sql =  " select A.ref_curso, " .
                "        B.abreviatura, " .
                "        A.ref_campus, " .
                "        get_campus(A.ref_campus), " .
                "        A.dt_ativacao, " .
                "        C.descricao " .
                " from contratos A, cursos B, tipos_curso C" .
                " where A.ref_curso = B.id and" .
                "       B.ref_tipo_curso = C.id and " .
                "       A.dt_ativacao > '$dt_conclusao' and " .
                "       A.ref_pessoa = '$ref_pessoa' and " .
                // O aluno cursou pelo menos uma disciplina até o final no curso do contrato
                "       A.ref_pessoa in (select distinct ref_pessoa " .
                "                        from matricula " .
                "                        where ref_pessoa = A.ref_pessoa and " .
                "                              ref_contrato = A.id and ";
                                               if ( $ref_periodo && $todos_egressos != 't' )
                                               {
                                                   $sql .= " ref_periodo = '$ref_periodo' and ";
                                               }
        $sql .= "                              dt_cancelamento is null) " .
                " order by A.dt_ativacao;";
    
        $query_new = $conn->CreateQuery($sql);
    
        $ii = 1;
    
        while( $query_new->MoveNext() )
        {
            list ($ref_curso,
                  $curso,
                  $ref_campus,
                  $campus,
                  $dt_ativacao,
                  $tipo_curso ) = $query_new->GetRowValues();
  
            $dt_ativacao = InvData($dt_ativacao);

            if ($ii == 1)
            {
                echo("<tr bgcolor=\"#cccccc\">\n");
                echo("<td width=\"23%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Egresso</b></td>");
                echo("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Tipo curso</b></td>");
                echo("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Curso</b></td>");
                echo("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Campus</b></td>");
                echo("<td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Data Ativacao</b></td>");
                echo("</tr>");
            }

            echo("<tr bgcolor=\"$bg2\">\n");
            echo("<td width=\"23%\" colspan=\"2\"><Font face=\"Verdana\" size=\"2\" color=\"red\">Egresso $ii</td>");
            echo("<td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$tipo_curso</td>");
            echo("<td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso - $curso</td>");
            echo("<td width=\"15%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_campus - $campus</td>");
            echo("<td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dt_ativacao</td>");
            echo("</tr>");
            $ii++;
        }

        echo("<tr><td>&nbsp;</td></tr>");
    
        $query_new->Close();
    
        $i++;
    }

    echo("<tr><td colspan=\"6\"><hr></td></tr>");
    echo("</table></center>");

    $query->Close();
    $conn->Close();

?>
<div align="center">
    <input type="button" name="Button" value="  Voltar  " onClick="location='../egressos.phtml'">
</div>
</body>
</html>
