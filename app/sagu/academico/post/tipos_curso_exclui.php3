<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from tipos_curso where id='$id';";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../tipos_curso.phtml'");
</script>
