<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update aux_paises set " .
       "    id = '$id'," .
       "    nome = '$nome'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Altera��o de Pa�ses",
	        "location='../paises_inclui.phtml'",
	        "As informa��es do pa�s <b>$nome</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
