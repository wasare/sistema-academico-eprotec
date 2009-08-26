<?php require_once("../common.php"); ?>
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

$id_user = GetIdentity('usuario_id_seq');


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

$sql = " CREATE USER $nome with password '$password1' in group $grupo;";
$mensagem = "Cria��o de Usu�rio...";

$ok = $conn->Execute($sql);  

// Insere usu�rio na tabela SAGU_USUARIOS no banco de dados sagu.
$sql2 = " insert into usuario (id,".
        "                            nome," .
        "                            nome_completo," .
        "                            email," .
        "                            grupo," .
        "                            setor," .
        "                            obs)" . 
        " values (" .
        "                            '$id_user',".
        "                            '$nome'," .
        "                            '$nome_completo'," .
        "                            '$email'," .
        "                            '$grupo'," .
        "                            '$ref_setor'," .
        "                            '$obs')";

$ok2 = $conn->Execute($sql2);  

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Erro ao criar usu�rio!");

SaguAssert($ok2,"Nao foi possivel inserir o registro!");

SuccessPage("$mensagem",
            "location='../usuarios/consulta_inclui_usuarios.phtml'",
            "O login do usu�rio � <b> $nome </b>.");

?>
</HEAD>
<BODY>
</BODY>
</HTML>
