<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("descricao", "dt_eleicao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$dt_eleicao = InvData($dt_eleicao);

$sql = " insert into eleicoes (descricao, " .
       "                       dt_eleicao)" .
       " values (" .
       "                     '$descricao'," .
       "                     '$dt_eleicao')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel inserir o registro!");

SuccessPage("Inclus�o de Elei��es",
            "location='../eleicoes_inclui.phtml'");
</script>

</head>
<body>
</body>
</html>
