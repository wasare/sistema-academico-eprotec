<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","razao_social","sigla","rua","complemento","bairro","cep","ref_cidade"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update configuracao_empresa set " .
       "    id = '$id'," .
       "    razao_social = '$razao_social', " .
       "    sigla = '$sigla', " .
       "    rua = '$rua', " .
       "    complemento = '$complemento', " .
       "    bairro = '$bairro', " .
       "    cep = '$cep', " .
       "    ref_cidade = '$ref_cidade' " .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração da Empresa",
	        "location='../configuracao_empresa.phtml'",
	        "As informações da empresa <b>$nome</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
