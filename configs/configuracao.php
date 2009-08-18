<?php

if(!$_SESSION['SessionAuth'] OR !$_SESSION['web_diario_login'])
{
	@session_start();
}

/**
 * Forca o fuso horario da aplicacao
 */
date_default_timezone_set('America/Sao_Paulo');

/**
 * Variaveis de Login
 */
list($user, $password) = split(":",$_SESSION['SessionAuth'],2);

/**
 * Banco de dados
 */
$host     = '192.168.0.234';
$database = 'sa';

/**
 * Variaveis de acesso a dados - SA 
 */
$param_conn['host']     = $host;
$param_conn['database'] = $database;
$param_conn['user']     = $user;
$param_conn['password'] = $password;
$param_conn['port']     = $port;


/**
 * Variaveis de acesso a dados - Web Diario
 */
$webdiario_host		= $param_conn['host'];
$webdiario_database = $param_conn['database'];
$webdiario_user		= 'usrsagu';
$webdiario_password = 'x6S8YzrJBs';
$webdiario_port		= $param_conn['port'];


/**
 * Variaveis de acesso a dados - Modulo do aluno
 */
$aluno_host     = $param_conn['host'];
$aluno_database = $param_conn['database'];
$aluno_user     = 'aluno';
$aluno_password = '@1srv27';
$aluno_port     = '';


/**
 * Variaveis do sistema
 */
$BASE_URL       = 'https://'. $_SERVER['SERVER_NAME'] .'/desenvolvimento/santiago/sistema-academico/';
$BASE_DIR       = '/var/www/dev.cefetbambui.edu.br/desenvolvimento/santiago/sistema-academico/';
$LOGIN_URL      = $BASE_URL .'index.php';
$LOGIN_LOG_FILE = $BASE_DIR .'app/sagu/logs/login.log';
$PATH_IMAGES    = $BASE_URL."public/images/";
$REVISAO 		= @file_get_contents ('../VERSAO.TXT');

/**
 * Arquivos requeridos
 */
require_once($BASE_DIR .'core/data/connection_factory.php');

/* 
* NAO VERIFICA AUTENTICACAO NO SA CASO TENHA UMA SESSAO DO WEB DIARIO ABERTA
* NÃO PERMITE LOGIN SIMULTANEO NO SA E NO WEB DIARIO
*/
if(!isset($_SESSION['web_diario_login']) OR $_SESSION['web_diario_login'] != "1")
{ 
	require_once($BASE_DIR .'core/login/check_login.php');
}

require_once($BASE_DIR .'configs/config.php');

//$host = '192.168.0.234';$user = 'usrsagu';$password = 'x6S8YzrJBs';

//print_r($_SESSION);die;
?>
