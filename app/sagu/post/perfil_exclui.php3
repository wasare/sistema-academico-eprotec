<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<script language="PHP">

CheckFormParameters(array("nome"));

$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = " DROP USER $nome;";
$mensagem = "Exclus�o de Usu�rio...";

$ok = $conn->Execute($sql);  

// Insere usu�rio na tabela SAGU_USUARIOS no banco de dados sagu.
$sql2 = " DELETE FROM sagu_usuarios " .
        " WHERE nome = '$nome';";

$ok2 = $conn->Execute($sql2);  

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Erro ao excluir usu�rio no banco de dados!");

SaguAssert($ok2,"Erro ao excluir o usu�rio!");

SuccessPage("$mensagem",
            "location='../consulta_inclui_usuarios.phtml'",
            "O usu�rio <b>$nome</b> foi exclu�do com sucesso!!!");

</script>
</HEAD>
<BODY>
</BODY>
</HTML>
