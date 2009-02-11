<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id",
                          "ref_grupo",
                          "ref_departamento",
                          "descricao_disciplina",
                          "descricao_extenso",
                          "num_creditos",
                          "carga_horaria"));

$conn = new Connection;

$conn->Open();

$sql = "update disciplinas set " .
       "    id = '$id'," .
       "    ref_grupo = '$ref_grupo'," .
       "    ref_departamento = '$ref_departamento'," .
       "    descricao_disciplina = '$descricao_disciplina'," .
       "    descricao_extenso = '$descricao_extenso'," .
       "    num_creditos = '$num_creditos'," .
       "    carga_horaria = '$carga_horaria'" .
       "  where id = '$id'";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Disciplinas",
            "location='../consulta_disciplinas.phtml'",
            "Disciplina alterada com sucesso.");
</script>
