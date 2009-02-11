<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<script language="PHP">

    CheckFormParameters(array("id","id_prof","ref_disciplina_ofer"));

    $conn = new Connection;
    $conn->Open();
    $conn->Begin();

    $dt_exame = InvData($dt_exame);
    
    $sql = " update disciplinas_ofer_compl set " .
           "    ref_disciplina_ofer = '$ref_disciplina_ofer'," .
           "    dia_semana = '$dia_semana'," .
           "    turno = get_turno_horario('$ref_horario')," .
           "    desconto = '$desconto'," .
           "    num_creditos_desconto = '$num_creditos_desconto'," .
           "    num_sala = '$num_sala'," .
           "    observacao = '$observacao'," .
           "    ref_horario = '$ref_horario', " .
           "    ref_regime = '$ref_regime', " .
           "    ref_professor_aux = '$ref_professor_aux', " .
           "    dia_semana_aux = '$dia_semana_aux', " .
           "    turno_aux = get_turno_horario('$ref_horario_aux'), " .
           "    num_sala_aux = '$num_sala_aux', " .
           "    ref_horario_aux = '$ref_horario_aux', ";
       
           if ( $dt_exame=='')
           { $sql = $sql . " dt_exame = null "; }
           else
           { $sql = $sql . " dt_exame = '$dt_exame' "; }
       
    $sql.= "  where id = '$id';";

    $ok1 = $conn->Execute($sql);

    $sql = " update disciplinas_ofer_prof set " .
           "    ref_professor = '$ref_professor'" .
           "  where id = '$id_prof';";

    $ok2 = $conn->Execute($sql);

    $sql = " update disciplinas_ofer set " .
           "    num_alunos = '$num_alunos'" .
           "  where id = '$ref_disciplina_ofer';";

    $ok3 = $conn->Execute($sql);

    $conn->Finish();
    $conn->Close();
    
    SaguAssert($ok1,"Nao foi possivel de atualizar o registro da tabela disciplinas_ofer_compl!");
    SaguAssert($ok2,"Nao foi possivel de atualizar o registro da tabela disciplinas_ofer_prof!");
    SaguAssert($ok3,"Nao foi possivel de atualizar o registro da tabela disciplinas_ofer!");

    SuccessPage("Registro Atualizado com sucesso","location='../atualiza_disciplina_ofer.phtml?id=$ref_disciplina_ofer'");

</script>
</head>
<body bgcolor="#FFFFFF">
</body>
</html>
