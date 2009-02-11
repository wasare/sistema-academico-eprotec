<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","nome","ref_pais"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update aux_estados set " .
       "    id = '$id'," .
       "    nome = '$nome'," .
       "    ref_pais = '$ref_pais'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Estado",
  	        "location='../consulta_inclui_estados.phtml'",
	        "As informações do estado <b>$nome</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
