<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "delete from departamentos where id='$id';";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Departamento exclu�do com sucesso",
            "location='../consulta_inclui_departamentos.phtml'");
</script>
