<? require("../../../lib/common.php"); ?>
<HTML>
<HEAD>
<script language="PHP">

CheckFormParameters(array("nome","nome_completo","password1","password2","grupo","ref_setor"));

$id_user=GetIdentity('seq_sagu_usuarios');

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

$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = " CREATE USER $nome with password '$password1' in group $grupo";
$mensagem = "Cria��o de Usu�rio...";

$ok = $conn->Execute($sql);  

// Insere usu�rio na tabela SAGU_USUARIOS no banco de dados sagu.
$sql2 = " insert into sagu_usuarios (id,".
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
            "location='../consulta_inclui_usuarios.phtml'",
            "O login do usu�rio � <b>$nome</b>.");

</script>
</HEAD>
<BODY>
</BODY>
</HTML>
