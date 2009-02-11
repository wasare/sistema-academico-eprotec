<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<title>Alunos Egressos</title>
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<?

    CheckFormParameters(array("dt_inicial", "dt_final", "tipo_egresso"));
 
    $conn = new Connection;
    $conn->open();
  
    $sql =  " select a.ref_curso," .
            "        get_curso_abrv(a.ref_curso)," .
            "        a.ref_campus," .
            "        get_campus(a.ref_campus)," .
            "        a.ref_pessoa," .
            "        pessoa_nome(a.ref_pessoa)," .
            "        to_char(a.dt_formatura,'dd-mm-yyyy')," .
            "        c.descricao," .
            "        b.ref_curso," .
            "        get_curso_abrv(b.ref_curso)," .
            "        b.ref_campus," .
            "        get_campus(b.ref_campus)," .
            "        to_char(b.dt_ativacao,'dd-mm-yyyy')," .
            "        d.descricao" .
            " from contratos a, contratos b, tipos_curso c, tipos_curso d" .
            " where a.fl_formando = '1' and " .
            // Aparece somente o curso em que se formou por último...
            "       a.dt_formatura = (select max(dt_formatura) " .
            "                         from contratos E " .
            "                         where E.ref_pessoa = a.ref_pessoa and " .
            "                               E.fl_formando = '1' and " .
            "                               E.dt_desativacao is not null) and " .
            "       a.dt_desativacao is not null and " .
            "       b.ref_pessoa = a.ref_pessoa and ";
            // Somente Egressos após a formatura
            if ($tipo_egresso == 2)
            {
                $sql .= " b.dt_ativacao >= a.dt_formatura and ";
            }
            
    $sql .= "       b.dt_ativacao between to_date('$dt_inicial','dd-mm-yyyy') and " .
            "                             to_date('$dt_final','dd-mm-yyyy') and " .
            // O aluno cursou pelo menos uma disciplina até o final no curso do contrato
            "       b.id in (select distinct ref_contrato " .
            "                        from matricula " .
            "                        where ref_pessoa = b.ref_pessoa and " .
            "                              ref_contrato = b.id and " .
            "                              dt_cancelamento is null) and " .
            "       b.id != a.id and " .
            "       c.id = get_tipo_curso(a.ref_curso) and " .
            "       d.id = get_tipo_curso(b.ref_curso) " .
            " order by get_curso_abrv(a.ref_curso)," .
            "          pessoa_nome(a.ref_pessoa)," .
            "          b.dt_ativacao"; 

    $query = $conn->CreateQuery($sql);
  
    // cores fundo
    $bg0 = "#000000";
    $bg1 = "#EEEEFF";
    $bg2 = "#FFFFEE";
 
    // cores fonte
    $fg0 = "#FFFFFF";
    $fg1 = "#000099";
    $fg2 = "#000099";

    $title = "Egressos que voltaram a estudar entre $dt_inicial e $dt_final";
    echo "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"center\" bgcolor=\"#cccccc\" colspan=\"6\" height=\"28\"><font color=\"#000000\" size=\"4\"><b>$title</b></font></td>\n";
    echo "  </tr>\n";

    $old_ref_curso_form = "";

    for ( $i=1; $query->MoveNext(); $i++ )
    {
        list ( $ref_curso_form,
               $curso_form,
               $ref_campus_form,
               $campus_form,
               $ref_pessoa,
               $pessoa,
               $dt_formatura,
               $tipo_curso_form,
               $ref_curso,
               $curso,
               $ref_campus,
               $campus,
               $dt_ativacao,
               $tipo_curso ) = $query->GetRowValues();
  
        if ( $old_ref_curso_form != $ref_curso_form )
        {  
            echo "  <tr>\n";
            echo "    <td colspan=\"6\">&nbsp;</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "    <td align=\"center\" bgcolor=\"#777777\" colspan=\"6\" height=\"28\"><font color=\"#ffffff\"><b>[$ref_curso_form] - $curso_form - $campus_form</b></font></td>\n";
            echo "  </tr>\n";

            $old_ref_curso_form = $ref_curso_form;
            $espaco = false; // nao da espaco se for a primeira do curso
        }
        else
        {
            $espaco = true; // da espaco em branco se nao for a primeira ocorrencia do curso
        }

        if ( $old_ref_pessoa != $ref_pessoa )
        {
            if ( $espaco )
            {
                echo "  <tr>\n";
                echo "    <td colspan=\"6\">&nbsp;</td>\n";
                echo "  </tr>\n";
            }

            echo "  <tr bgcolor=\"#cccccc\">\n";
            echo "    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>&nbsp;</b></td>\n";
            echo "    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Código</b></td>\n";
            echo "    <td colspan=\"3\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Pessoa</b></td>\n";
            echo "    <td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Data Formatura</b></td>\n";
            echo "  </tr>\n";

            echo "  <tr bgcolor=\"$bg1\">\n";
            echo "    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$i</b></td>\n";
            echo "    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$ref_pessoa</b></td>\n";
            echo "    <td colspan=\"3\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$pessoa</b></td>\n";
            echo "    <td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg1\"><b>$dt_formatura</b></td>\n";
            echo "  </tr>\n";

            echo "  <tr bgcolor=\"#cccccc\">\n";
            echo "    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Egresso</b></td>\n";
            echo "    <td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Tipo curso</b></td>\n";
            echo "    <td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Cód.</b></td>\n";
            echo "    <td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Curso</b></td>\n";
            echo "    <td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Campus</b></td>\n";
            echo "    <td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"#000000\"><b>Data Ativação</b></td>\n";
            echo "  </tr>\n";

            $old_ref_pessoa = $ref_pessoa;
            $cnt_contratos = 0;
        }

        $cnt_contratos++;

        echo "  <tr bgcolor=\"$bg2\">\n";
        echo "    <td width=\"8%\"><Font face=\"Verdana\" size=\"2\" color=\"red\">Egresso $cnt_contratos</td>\n";
        echo "    <td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$tipo_curso</td>\n";
        echo "    <td width=\"10%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_curso</td>\n";
        echo "    <td width=\"30%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$curso</td>\n";
        echo "    <td width=\"20%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$ref_campus - $campus</td>\n";
        echo "    <td width=\"12%\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$dt_ativacao</td>\n";
        echo "  </tr>\n";

    }

    echo "  <tr>\n";
    echo "    <td colspan=\"6\"><hr></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

    $query->Close();
    $conn->Close();

?>
<div align="center">
    <input type="button" name="Button" value="  Voltar  " onClick="location='../egressos_que_retornaram.phtml'">
</div>
</body>
</html>
