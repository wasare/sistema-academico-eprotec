<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "delete from aux_paises" .
       "  where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel excluir o registro!");
SuccessPage("Exclus�o de Pa�ses",
            "location='../paises_inclui.phtml'");
</script>
</head>
<body>
</body>
</html>
