<? require("../../../../lib/common.php");?>
<? require("../../lib/InvData.php3");?>

<script language="PHP">

CheckFormParameters(array("id",
                          "ref_curso",
                          "ref_disciplina",
                          "tipo"));

$conn = new Connection;
$conn->Open();

$tipo = substr($tipo, 0, 1);

$sql = " update pre_requisitos set " .
       "    ref_curso = '$ref_curso', " .
       "    ref_disciplina = '$ref_disciplina', " .
       "    ref_disciplina_pre = '$ref_disciplina_pre'," .
       "    ref_area = '$ref_area'," .
       "    horas_area = '$horas_area',".
       "    tipo = '$tipo'".
       " where id = '$id'" ;


$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração do Pré-Requisito",
            "location='../consulta_inclui_pre_requisito.phtml'");
</script>
