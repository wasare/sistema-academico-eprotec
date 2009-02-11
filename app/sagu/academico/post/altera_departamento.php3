<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "descricao_depto"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update departamentos set " .
       "    id = '$id'," .
       "    descricao = '$descricao_depto'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Departamento",
            "location='../consulta_inclui_departamentos.phtml'",
            "Departamento alterado com sucesso.");
</script>
