<? require("../../../../lib/common.php"); ?>

<?

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "delete from mensagens where id='$id' and ref_periodo='$ref_periodo';";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../consulta_inclui_mensagem.phtml'",
            "Mensagem foi exclu�do com sucesso.");
?>
