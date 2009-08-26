<?php require("../common.php"); ?>
<HTML>
<HEAD>
<?php

$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$nome = $_POST['nome'];
$nome_completo = $_POST['nome_completo'];
$email = $_POST['email'];
$ref_setor = $_POST['ref_setor'];
$obs = $_POST['obs'];
$grupo = $_POST['grupo'];


CheckFormParameters(array("nome","nome_completo","password1","password2","grupo","ref_setor"));

if (!$password1 || !$password2) 
{
   SaguAssert(0, "Voc� deve digitar duas vezes a senha.");
   return false;
}
if ($password1 != $password2) 
{
   SaguAssert(0, "As duas senhas n�o s�o as mesmas.");
   return false;
}

// FIXME: migrar conex�o para adodb
$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = " ALTER USER $nome with password '$password1';";
$mensagem = "Altera��o de Usu�rio...";

$ok = $conn->Execute($sql);  

// Altera usu�rio na tabela SAGU_USUARIOS no banco de dados sagu.
$sql2 = " update usuario set " .
        "        nome = '$nome', " .
        "        nome_completo = '$nome_completo', " .
        "        email = '$email', " .
      //"        grupo = '$grupo', " .
        "        setor = '$ref_setor', " .
        "        obs = '$obs' " . 
        " where nome = '$nome';";

$ok2 = $conn->Execute($sql2);  

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Erro ao alterar o usu�rio no Banco de Dados!");
SaguAssert($ok2,"Erro ao alterar o usu�rio!");

SuccessPage("$mensagem",
            "",
            "O login do usu�rio � <b>$nome</b>.<br> Efetue o login novamente clicando <A href=\"../../../index.php\" target=\"_top\"><font color=\"#0000CC\"><b>aqui</b></font></a>");

?>
</HEAD>
<BODY>
</BODY>
</HTML>
