<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id",
                          "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update tipos_curso set " .
       "        descricao = '$descricao'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel alterar o registro!");

SuccessPage("Altera��o de Tipo de Curso",
            "location='../tipos_curso.phtml'",
            "Tipo de Curso alterado com sucesso.");
</script>
