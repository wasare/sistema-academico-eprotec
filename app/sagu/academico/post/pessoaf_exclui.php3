<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();
$conn->Begin()

$sql = "delete from pessoas where id='$id';";

$ok = $conn->Execute($sql);

$sql = "delete from documentos where ref_pessoa='$id';";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../consulta_inclui_pessoa.phtml'",
            "O aluno foi exclu�do com sucesso.");
</script>
