<? require("../../../../lib/common.php"); ?>

<?

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "delete from mensagens where id='$id' and ref_periodo='$ref_periodo';";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
            "location='../consulta_inclui_mensagem.phtml'",
            "Mensagem foi excluído com sucesso.");
?>
