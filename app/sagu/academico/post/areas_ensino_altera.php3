<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id",
                          "area"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update areas_ensino set " .
       "    id = '$id'," .
       "    area = '$area'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Área de Ensino",
            "location='../areas_ensino.phtml'",
            "Área de Ensino alterada com sucesso.");
</script>
