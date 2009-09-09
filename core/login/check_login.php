<?php

/*
  TODO: registrar em log quando falhar a verificacao - gravar em banco de dados
*/

$log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

// recupera variareis de autenticacao da sessao
list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

// verifica e desconecta usuario com duas sessoes simultaneas
$cont_sess = $GLOBALS['ADODB_SESS_CONN']->getOne("SELECT COUNT(*) FROM $SESS_TABLE WHERE expireref = '". $uid ."';");
if($cont_sess > 1) {
    // exclui a sessao do usuario no banco de dados
    session::clear_session($uid, NULL);
    session::destroy();

    // grava log de falha de acesso 
    $log_msg .= $param_conn['user'] .' - *** LOGIN DUPLICADO (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $param_conn['user'] .',pwd=) ***'."\n";

    error_log( $log_msg,3,$LOGIN_LOG_FILE);

    // TODO: redirecionar a uma pagina de aviso de sessao expirada por duplicidade
    header('Location: '. $BASE_URL .'public/login_erro.php');
    exit;
}

// TODO: valida autenticacao neste ponto - variaveis de sessao disponiveis

// unset($_SESSION['sa_auth']);
if(empty($uid) && empty($pwd)) {

	unset($_SESSION['sa_auth']);

	// grava log de falha de acesso 
    $log_msg .= $param_conn['user'] .' - *** FALHA AO VERIFICAR LOGIN (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $param_conn['user'] .',pwd=) ***'."\n";
    
	error_log( $log_msg,3,$LOGIN_LOG_FILE);

	header('Location: '. $BASE_URL .'public/login_erro.php');
    exit;
}
else {
	$GLOBALS['USERID'] = $uid;
	$GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');
}
?>
