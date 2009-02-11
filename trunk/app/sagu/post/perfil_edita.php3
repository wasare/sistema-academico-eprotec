<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<script language="PHP">

CheckFormParameters(array("nome","nome_completo","password1","password2","grupo","ref_setor"));

if (!$password1 || !$password2) 
{
   SaguAssert(0, "Você deve digitar duas vezes a senha.");
   return false;
}
if ($password1 != $password2) 
{
   SaguAssert(0, "As duas senhas não são as mesmas.");
   return false;
}

$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = " ALTER USER $nome with password '$password1';";
$mensagem = "Alteração de Usuário...";

$ok = $conn->Execute($sql);  

// Altera usuário na tabela SAGU_USUARIOS no banco de dados sagu.
$sql2 = " update sagu_usuarios set " .
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

SaguAssert($ok,"Erro ao alterar o usuário no Banco de Dados!");
SaguAssert($ok2,"Erro ao alterar o usuário!");
//<A href="index.php3" target="_top"><font color="#0000CC">Logout</font></a>

SuccessPage("$mensagem",
            "",
            "O login do usuário é <b>$nome</b>.<br> Efetue o login novamente clicando <A href=\"../index.php3\" target=\"_top\"><font color=\"#0000CC\"><b>aqui</b></font></a>");

</script>
</HEAD>
<BODY>
</BODY>
</HTML>
