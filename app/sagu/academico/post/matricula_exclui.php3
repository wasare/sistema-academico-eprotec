<? require("../../../../lib/common.php"); ?>

<HTML><HEAD><script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from matricula where id='$id';";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
            "history.go(-2)");
</script>
</HEAD>
<BODY></BODY>
</HTML>