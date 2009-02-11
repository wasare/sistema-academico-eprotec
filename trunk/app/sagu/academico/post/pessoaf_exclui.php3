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

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
            "location='../consulta_inclui_pessoa.phtml'",
            "O aluno foi excluído com sucesso.");
</script>
