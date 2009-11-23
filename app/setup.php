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
//auth::check_login($BASE_URL, $SESS_TABLE, $LOGIN_LOG_FILE);

/*
 * Dados do usuario autenticado
 */
list( $sa_usuario,
      $sa_senha,
      $sa_usuario_id,
      $sa_ref_pessoa) = explode(":",$_SESSION['sa_auth']);
?>

