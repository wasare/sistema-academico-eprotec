<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<HEAD><script language="PHP">

CheckFormParameters(array("ref_contrato",
                          "ref_pessoa",
                          "ref_campus",
                          "ref_curso",
                          "ref_periodo",
                          "ref_disciplina",
                          "dt_matricula"));

$conn = new Connection;
$id_matr = GetIdentity('seq_matricula');

$conn->Open();
$conn->Begin();

if ($ref_disciplina_ofer)
{
    $sql = " select count(*) " .
           " from disciplinas_ofer " .
           " where id = '$ref_disciplina_ofer' and " .
           "       ref_periodo = '$ref_periodo' and " .
           "       ref_disciplina in ('$ref_disciplina','$ref_disciplina_subst')"; 

    $query = $conn->CreateQuery($sql);
    
    while ( $query->MoveNext() )
    {
        $qtde_resultado = $query->GetValue(1);
    }
    $query->Close();

    $step_by_resultado = ($qtde_resultado!=0);
    SaguAssert($step_by_resultado, "Inconsistência: Possíveis causas: <li>A disciplina do currículo $ref_disciplina ou a disciplina substituída $ref_disciplina_subst não equivale a oferta <b>$ref_disciplina_ofer</b>.<li>A disciplina oferecida <b>$ref_disciplina_ofer</b> não está sendo oferecida no período <b>$ref_periodo</b>!!!");
}

$dt_matricula = InvData($dt_matricula);

$sql = "insert into matricula" .
       "  (" .
       "    id," .
       "    ref_contrato," .
       "    ref_pessoa," .
       "    ref_campus," .
       "    ref_curso," .
       "    ref_curso_subst," .
       "    ref_periodo," .
       "    ref_disciplina," .
       "    ref_disciplina_subst," .
       "    ref_disciplina_ofer," .
       "    complemento_disc, " .
       "    nota_final," .
       "    conceito," .
       "    dt_matricula," .
       "    hora_matricula, " .
       "    carga_horaria_aprov, " .
       "    creditos_aprov, " .
       "    obs_aproveitamento, " .
       "    fl_exibe_displ_hist, " .
       "    ref_instituicao " .
       "  )" .
       "  values" .
       "  (" .
       "    '$id_matr'," .
       "    '$ref_contrato'," .
       "    '$ref_pessoa'," .
       "    '$ref_campus'," .
       "    '$ref_curso'," .
       "    '$ref_curso_subst'," .
       "    '$ref_periodo'," .
       "    '$ref_disciplina'," .
       "    '$ref_disciplina_subst'," .
       "    '$ref_disciplina_ofer'," .
       "    get_complemento_ofer('$ref_disciplina_ofer'), " .
       "    '$nota_final'," .
       "    '$conceito_final'," .
       "    '$dt_matricula'," .
       "    date(now())," .
       "    '$carga_horaria_aprov', " .
       "    '$creditos_aprov', " .
       "    '$obs_aproveitamento', " .
       "    'S', " .
       "    '$ref_instituicao' " .
       "  )";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Novo Aproveitamento",
            "location='../aproveitamento_int.phtml'",
            "Aproveitamento efetuado com sucesso!!!",
            "location='../consultas_diversas.phtml?periodo=$ref_periodo&pessoa=$ref_pessoa'");
            
</script>
</HEAD>
<BODY></BODY>
</html>
