<?php

require_once("../setup.php");

$conn = new connection_factory($param_conn);

$nome       = $_POST['nome'];
$senha      = hash('sha256', $_POST['senha']);
$ref_pessoa = $_POST['ref_pessoa'];
$ativado    = $_POST['ativado'];
$ref_setor  = $_POST['ref_setor'];


//Cadastrar o usuario
echo $sql_usuario = sprintf('INSERT INTO usuario (
nome, senha, ref_pessoa, ativado, ref_setor )
VALUES (%s, %s, %d, %d, %d );', $nome, $senha, $ref_pessoa, $ativado, $ref_setor);

//$conn->Execute($sql_usuario);

//Cadastrar a permissao
/*
$sql_permissao = '';
$conn->Execute(sprintf($sql_permissao, $args));
*/
?>

<h2><font color="green">Cadastro realizado com sucesso!</font></h2>
<a href="#">Cadastrar novo usu&aacute;rio</a>
<a href="#">Listar usu&aacute;rios</a>