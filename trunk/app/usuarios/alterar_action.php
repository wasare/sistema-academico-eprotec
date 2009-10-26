<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);


$id_usuario  = $_POST['id_usuario'];
$senha       = $_POST['senha'];
$permissoes  = $_POST['permissao'];
$ref_setor   = $_POST['setor'];

if($_POST['ativado']) {
    $ativado = 't';
}else {
    $ativado = 'f';
}


//Atualiza os dados do usuario
if($_POST['senha_atual']) {
    $sql_senha = ' ';
}else {
    $sql_senha = " senha='".hash('sha256',$senha)."', ";
}

$sqlUsuario = "
UPDATE usuario 
SET 
    $sql_senha
	ativado='$ativado', 
	ref_setor=$ref_setor 
WHERE 
	id = $id_usuario;";

if($conn->Execute($sqlUsuario)) {
    $msg = '<font color="green">Usu&aacute;rio alterado com sucesso!</font>';
}else {
    $msg = 'Erro ao atualizar usu&aacute;rio!';
}

//Remove as antigas permissoes
$conn->Execute("DELETE FROM usuario_papel WHERE ref_usuario=$id_usuario");

if(!empty($permissoes)) {
//Cria as novas permissoes
    foreach($permissoes as $permissao) {
        if(!$conn->Execute("INSERT INTO usuario_papel(ref_usuario, ref_papel) VALUES($id_usuario, $permissao); "))
            $msg = 'Erro ao criar permiss&otilde;oes do usu&aacute;rio!';
    }
}

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Alterar usu&aacute;rio</h2>
        <font color="red"><?php echo $msg;?></font>
        <p>
            <a href="index.php">Voltar para o controle de usu&aacute;rios</a>
        </p>
    </body>
</html>
