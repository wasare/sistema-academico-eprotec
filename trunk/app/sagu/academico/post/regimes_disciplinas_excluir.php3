<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from regimes_disciplinas where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o regime!");

SuccessPage("Regime excluído do curso com sucesso",
            "location='../regimes_disciplinas.phtml'");
</script>
