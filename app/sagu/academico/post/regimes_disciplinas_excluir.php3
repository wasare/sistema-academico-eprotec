<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from regimes_disciplinas where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o regime!");

SuccessPage("Regime exclu�do do curso com sucesso",
            "location='../regimes_disciplinas.phtml'");
</script>
