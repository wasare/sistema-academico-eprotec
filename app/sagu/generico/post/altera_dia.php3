<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","nome","abrv"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update dias set " .
       "    id = '$id'," .
       "    nome = '$nome', " .
       "    abrv = '$abrv'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Altera��o de Dias da Semanas",
	        "location='../dias_inclui.phtml'",
	        "As informa��es do dia <b>$nome</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
