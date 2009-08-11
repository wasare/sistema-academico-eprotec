<?php

if(!$_SESSION['SessionAuth']){
	@session_start();
}

// POSTGRESQL
// ACESSO AO BANCO DE DADOS -  SA e WEB DIARIO
//$host = '192.168.0.234';
//$user = 'usrsagu';
//$password = 'x6S8YzrJBs';
$host = 'dados.cefetbambui.edu.br';
$database = 'sagu';
$port = '';
list($user, $password) = split(":",$_SESSION['SessionAuth'],2);

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

date_default_timezone_set('America/Sao_Paulo');

$versao = @file_get_contents ('../VERSAO.TXT');

$BASE_URL  = 'https://'. $_SERVER['SERVER_NAME'] .'/desenvolvimento/sistema_academico/';
$BASE_DIR  = dirname(__FILE__) . '/../';

$LoginURL    = $BASE_URL .'index.php';

$login_log_file = $BASE_DIR .'app/sagu/logs/login.log';

?>
