<?php require_once("../common.php"); ?>
<HTML>
<HEAD>
<?php

$nome = $_GET['nome'];

CheckFormParameters(array("nome"));


$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = " DROP USER $nome;";
$mensagem = "Exclus�o de Usu�rio...";

$ok = $conn->Execute($sql);  

// Exclui usu�rio na tabela SAGU_USUARIOS no banco de dados sagu.
$sql2 = " DELETE FROM usuario " .
        " WHERE nome = '$nome';";

$ok2 = $conn->Execute($sql2);  

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Erro ao excluir usu�rio no banco de dados!");

SaguAssert($ok2,"Erro ao excluir o usu�rio!");

SuccessPage("$mensagem",
            "location='../consulta_inclui_usuarios.phtml'",
            "O usu�rio <b>$nome</b> foi exclu�do com sucesso!!!");

?>
</HEAD>
<BODY>
</BODY>
</HTML>
