<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id",
                          "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update motivos set " .
       "    id = '$id'," .
       "    descricao = '$descricao'," .
       "    ref_tipo_motivo = '$ref_tipo_motivo'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel alterar o registro!");

SuccessPage("Altera��o de Motivo",
            "location='../motivos.phtml'",
            "Motivo alterado com sucesso.");
</script>
