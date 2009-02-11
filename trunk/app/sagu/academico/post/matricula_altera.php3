<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "ref_disciplina_ofer"));
//                        "ref_contrato",
//                        "ref_pessoa",
//                        "ref_campus",
//                        "ref_curso",
//                        "ref_periodo",
//                        "ref_disciplina",
//                        "ref_curso_subst",
//                        "ref_disciplina_subst",
//                        "nota1",
//                        "nota2",
//                        "nota",
//                        "nota_exame",
//                        "nota_final",
//                        "conceito",
//                        "conceito_exame",
//                        "conceito_final",
//                        "num_faltas",
//                        "obs_aproveitamento",
//                        "ref_motivo_matricula",
//                        "dt_matricula",
//                        "hora_matricula",
//                        "fl_liberado",
//                        "ref_liberacao_ed_fisica",
//                        "ref_motivo_cancelamento",
//                        "dt_cancelamento",
//                        "carga_horaria_aprov",
//                        "fl_exibe_displ_hist",
//                        "creditos_aprov",
//                        "ref_instituicao"));

$conn = new Connection;

$conn->Open();

$dt_matricula = InvData($dt_matricula);
$dt_cancelamento = InvData($dt_cancelamento);

$sql = "update matricula set " .
//     "    id = '$id'," .
       "    ref_disciplina_ofer = '$ref_disciplina_ofer'," .
       "    ref_contrato = '$ref_contrato'," .
//     "    ref_pessoa = '$ref_pessoa'," .
       "    ref_campus = '$ref_campus'," .
       "    ref_curso = '$ref_curso'," .
       "    ref_periodo = '$ref_periodo'," .
       "    ref_disciplina = '$ref_disciplina'," .
       "    ref_curso_subst = '$ref_curso_subst'," .
       "    ref_disciplina_subst = '$ref_disciplina_subst'," .
       "    nota1 = '$nota1'," .
       "    nota2 = '$nota2'," .
       "    nota = '$nota'," .
       "    nota_exame = '$nota_exame'," .
       "    nota_final = '$nota_final'," .
       "    conceito = '$conceito'," .
       "    conceito_exame = '$conceito_exame'," .
       "    conceito_final = '$conceito_final'," .
       "    num_faltas = '$num_faltas'," .
       "    obs_aproveitamento = '$obs_aproveitamento'," .
//     "    ref_motivo_matricula = '$ref_motivo_matricula'," .
//     "    dt_matricula = '$dt_matricula'," .
//     "    hora_matricula = '$hora_matricula'," .
       "    fl_liberado = '$fl_liberado'," .
       "    ref_liberacao_ed_fisica = '$ref_liberacao_ed_fisica'," .
//     "    ref_motivo_cancelamento = '$ref_motivo_cancelamento'," .
       "    complemento_disc = '$complemento_disc'," .
       "    carga_horaria_aprov = '$carga_horaria_aprov'," .
       "    turma = '$turma'," .
       "    processo = '$processo',";
       
       if ( ($fl_exibe_displ_hist == 'S') || ($fl_exibe_displ_hist == 'Sim') )
          { $sql = $sql . " fl_exibe_displ_hist = 'S', "; }
       else
          { $sql = $sql . " fl_exibe_displ_hist = 'N', ";  }
       
       $sql = $sql . "    creditos_aprov = '$creditos_aprov'," .
       
       "    ref_instituicao = '$ref_instituicao',";

       if( empty($dt_cancelamento) )
       {  $sql .= "    dt_cancelamento = null " .
       
       "    where id = '$id'"; }
       else
       {  $sql .= "    dt_cancelamento = '$dt_cancelamento'" .
       
       "    where id = '$id'"; }

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Matrícula",
            "javascript:history.go(-1)",
            "A matrícula do aluno <b>$ref_pessoa</b> foi atualizada com sucesso.");

</script>
</head>
<BODY></BODY>
</html>
