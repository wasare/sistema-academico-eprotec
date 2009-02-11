<? require("../../../../lib/common.php"); ?>

<HTML><HEAD><script language="PHP">

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "delete from instituicoes where id='$id';";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
            "location='../consulta_inclui_instituicoes.phtml'");
</script>
</HEAD>
<BODY></BODY>
</HTML>
