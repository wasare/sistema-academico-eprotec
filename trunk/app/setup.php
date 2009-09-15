<?php

/**
 * Inclui arquivo com as configuracoes do sistema
 */
require_once(dirname(__FILE__).'/../config/configuracao.php');

/**
 * Arquivos requeridos
 */
require_once($BASE_DIR .'core/data/connection_factory.php');
require_once($BASE_DIR .'core/login/session.php');
require_once($BASE_DIR .'core/login/auth.php');

/*
 * Inicia a sessao
 */
session::init($param_conn);

/* 
 * Verifica a autenticacao do usuario
 */
var_dump($_SESSION['sa_auth']);

$auth = new auth();
$auth->check_login($BASE_URL, $SESS_TABLE, $LOGIN_LOG_FILE);

/*
if(isset($_SESSION['sa_modulo']) AND !empty($_SESSION['sa_modulo']))
{
    auth::check_login();
}
else { 
    header('Location: '. $BASE_URL .'index.php?sa_msg=3');
}
*/

?>

