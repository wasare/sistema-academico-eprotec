<?php

if(!$_SESSION['SessionAuth'])
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
$host     = 'dados.cefetbambui.edu.br';
$database = 'sagu';

/**
 * Variaveis de acesso a dados - SA e WebDiario
 */
$param_conn['host']     = $host;
$param_conn['database'] = $database;
$param_conn['user']     = $user;
$param_conn['password'] = $password;
$param_conn['port']     = $port;

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
$BASE_URL       = 'https://'. $_SERVER['SERVER_NAME'] .'/desenvolvimento/sistema_academico/';
$BASE_DIR       = '/var/www/dev.cefetbambui.edu.br/desenvolvimento/sistema_academico/';
$LOGIN_URL      = $BASE_URL .'index.php';
$LOGIN_LOG_FILE = $BASE_DIR .'app/sagu/logs/login.log';
$PATH_IMAGES    = $BASE_URL."public/images/";
$REVISAO 		= @file_get_contents ('../VERSAO.TXT');

/**
 * Arquivos requeridos
 */
require_once($BASE_DIR .'core/data/connection_factory.php');
require_once($BASE_DIR .'core/login/check_login.php');
require_once($BASE_DIR .'configs/config.php');

//$host = '192.168.0.234';$user = 'usrsagu';$password = 'x6S8YzrJBs';

?>
