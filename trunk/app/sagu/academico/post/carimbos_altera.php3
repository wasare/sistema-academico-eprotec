<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id",
                          "nome",
                          "texto",
                          "ref_setor"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update carimbos set " .
       "      nome = '$nome'," .
       "      texto = '$texto'," .
       "      ref_setor = '$ref_setor'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel alterar o registro!");

SuccessPage("Altera��o do Carimbo",
            "location='../carimbos.phtml'",
            "Carimbo alterado com sucesso.");
</script>
