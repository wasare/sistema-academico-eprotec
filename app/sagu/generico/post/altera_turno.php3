<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update turnos set " .
       "    nome = '$nome' " .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Turnos",
	        "location='../turnos_inclui.phtml'",
	        "As informações do turno <b>$nome</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
