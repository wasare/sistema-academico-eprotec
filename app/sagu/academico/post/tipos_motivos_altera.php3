<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id",
                          "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update tipos_motivos set " .
       "        descricao = '$descricao'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");

SuccessPage("Alteração de Tipo de Motivo",
            "location='../tipos_motivos.phtml'",
            "Tipo de Motivo alterado com sucesso.");
</script>
