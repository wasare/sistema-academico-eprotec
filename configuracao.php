<?php

//ACESSO AO BANCO DE DADOS
//$host = '192.168.0.234';
$host = 'dados.cefetbambui.edu.br';
$database = 'sagu';
$port = '';

if(isset($_SESSION['SessionAuth']))
{
    @session_start();

	list($user, $password) = split(":",$_SESSION['SessionAuth'],2);
}
else
{
	$user = '';
	$password = '';
}


// BANCO DE DADOS -  MÓDULO WEB DIÁRIO
$webdiario_host = $host;
$webdiario_database = $database;
$webdiario_user = 'usrsagu';
$webdiario_password = 'x6S8YzrJBs';
$webdiario_port = '';
	

// BANCO DE DADOS -  MÓDULO DE ALUNOS
$aluno_host = $host;
$aluno_database = $database;
$aluno_user = 'aluno';
$aluno_password = '@1srv27';
$aluno_port = '';


?>
