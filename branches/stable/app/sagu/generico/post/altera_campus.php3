<?php

require("../../../../lib/common.php");

$id            = $_POST['id'];
$ref_empresa   = $_POST['ref_empresa'];
$nome_campus   = $_POST['nome_campus'];
$cidade_campus = $_POST['cidade_campus'];

CheckFormParameters(array("id","ref_empresa","nome_campus","cidade_campus"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update campus set " .
       "    id = '$id'," .
       "    ref_empresa = '$ref_empresa', " .
       "    nome_campus = '$nome_campus', " .
       "    cidade_campus = '$cidade_campus'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Campus",
	        "location='../campus_inclui.phtml'",
	        "As informações do campus <b>$nome</b> foram atualizadas com sucesso.");
?>
<html>
<head>
</head>
<body>
</body>
</html>
