<?php

/** 
 * Autentica ou verifica a autenticacao de usuario 
 */
require_once(dirname(__FILE__).'/../../configs/configuracao.php');

$log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

/*
  FIXME: substituir pela classe de conexão 
         A classe não foi utilizada pois limita o controle de erro de conexão a ela mesma, 
        não permitindo o tratamento do erro e/ou redirecionamento pela aplicação.
*/


	// VERIFICA A AUTENTICAÇÃO EM UMA SESSÃO ANTERIOR
if(!empty($_SESSION['SessionAuth'])) 
{
	$conexao = @pg_connect('host='. $param_conn['host'] .' dbname='. $param_conn['database'] .' user='. $param_conn['user'] .' password='. $param_conn['password']);


    if(!$conexao) 
	{
		// grava log de falha de acesso 
		$log_msg .= $param_conn['user'] .' - *** FALHA AO VERIFICAR LOGIN (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $param_conn['user'] .',pwd=) ***'."\n";
		error_log( $log_msg,3,$LOGIN_LOG_FILE);
		require_once($BASE_DIR . 'public/login_erro.php');
		exit;
	}
	else
		pg_close($conexao);

	// ^ VERIFICA A AUTENTICAÇÃO EM UMA SESSÃO ANTERIOR ^ //
}
else
{
	// VERIFICA O LOGIN EM UMA NOVA SESSÃO
	$uid = $_POST['uid'];
	$pwd = $_POST['pwd'];

	$conexao = @pg_connect('host='. $param_conn['host'] .' dbname='. $param_conn['database'] .' user='. $uid .' password='. $pwd);
	if($conexao)
	{
		// grava log de acesso
		$log_msg .= $uid .' - *** LOGIN ACEITO (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $uid .',pwd=) ***'."\n";
		error_log($log_msg,3,$LOGIN_LOG_FILE);

		// registra variavel de acesso na sessão
		$_SESSION['SessionAuth'] = "$uid:$pwd";

		// desconecta do banco
		pg_close($conexao);

		header("Location: $BASE_URL/app/inicio.php?login_id=$uid");
		exit;
	}
	else
	{
		// grava log de acesso
		$log_msg .=  $uid .' - *** LOGIN RECUSADO (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $uid .',pwd=) ***'."\n";
		error_log($log_msg,3,$LOGIN_LOG_FILE);
		require_once($BASE_DIR .'public/login_erro.php');
		exit;
	}
	// ^ VERIFICA O LOGIN EM UMA NOVA SESSÃO ^ //
}

?>
