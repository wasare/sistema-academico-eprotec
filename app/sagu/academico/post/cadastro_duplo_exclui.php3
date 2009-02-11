<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id_old","id_new"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " delete from cadastro_duplo" .
       " where id_old = '$id_old' and " .
       "       id_new = '$id_new' and " .
       "       tipo = 'pessoas'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel excluir o registro!");
SuccessPage("Exclusão de Cadastro Duplo",
            "location='../cadastro_duplo.phtml'");
</script>
