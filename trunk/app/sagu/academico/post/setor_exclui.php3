<? require("../../../../lib/common.php"); ?>

<script language="PHP">

$conn = new Connection;

$conn->Open();

$sql = "delete from sagu_setores where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Close();

saguassert($ok,"N�o foi poss�vel de excluir o setor!");

SuccessPage("Setor exclu�do com sucesso",
            "location='../setores.phtml'");
</script>
