<?php

if(!$_SESSION['SessionAuth']){
	@session_start();
}

// POSTGRESQL
// ACESSO AO BANCO DE DADOS -  SA e WEB DIARIO
//$host = '192.168.0.234';
//$user = 'usrsagu';
//$password = 'x6S8YzrJBs';
list($user, $password) = split(":",$_SESSION['SessionAuth'],2);

$host = 'dados.cefetbambui.edu.br';
$database = 'sagu';
$port = '';

$param_conn['host']     = $host;
$param_conn['database'] = $database;
$param_conn['user']     = $user;
$param_conn['password'] = $password;
$param_conn['port']     = $port;

// BANCO DE DADOS -  MODULO DE ALUNOS
$aluno_host = $param_conn['host'];
$aluno_database = $param_conn['database'];
$aluno_user = 'aluno';
$aluno_password = '@1srv27';
$aluno_port = '';

//Inclusao do arquivo de conexao
require_once(dirname(__FILE__).'/../core/data/connection_factory.php');

$path_images = "https://dev.cefetbambui.edu.br/desenvolvimento/sistema_academico/public/images/";

?>
