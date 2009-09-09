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

session::init($param_conn);


/* 
* NAO VERIFICA AUTENTICACAO NO SA CASO TENHA UMA SESSAO ABERTA DO WEB DIARIO OU MODULO DO ALUNO
* NÃO PERMITE LOGIN SIMULTANEO NO SA E NO WEB DIARIO OU MODULO DO ALUNO
*/
if(!isset($_POST['sa_login']) OR empty($_POST['sa_login']))
{
    require_once($BASE_DIR .'core/login/check_login.php');
}


?>

