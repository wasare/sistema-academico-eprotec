<?php 

require("../../common.php"); 

$id         = $_POST['id'];
$nome       = $_POST['nome'];
$sucinto    = $_POST['sucinto'];
$nome_atual = $_POST['nome_atual'];

CheckFormParameters(array("id","nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update instituicoes set " .
       "    id = '$id'," .
       "    nome = '$nome'," .
       "    sucinto = '$sucinto'," .
       "    nome_atual = '$nome_atual'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel alterar o registro!");
SuccessPage("Altera��o de Institui��o",
            "location='../consulta_inclui_instituicoes.phtml'",
            "Institui��o alterada com sucesso.");
?>
<html>
<head>
</head>
<body></body>
</html>
