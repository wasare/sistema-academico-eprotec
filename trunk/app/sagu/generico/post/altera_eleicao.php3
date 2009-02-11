<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","descricao","dt_eleicao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$dt_eleicao = InvData($dt_eleicao);

$sql = " update eleicoes set " .
       "    descricao = '$descricao', " .
       "    dt_eleicao = '$dt_eleicao'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Eleições",
	        "location='../eleicoes_inclui.phtml'",
	        "As informações da eleiçao <b>$descricao</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
