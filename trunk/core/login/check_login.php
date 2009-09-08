<?php

/*
  TODO: registrar em log quando falhar a verificacao - gravar em banco de dados
*/


// forca eliminacao da sessao do usuario no banco
// TODO: limpar outras sessoes expiradas (?), redirecionar o usuario para uma pagina com aviso de sessao expirada
function clear_session($expireref, $sesskey) {

    if(is_object($GLOBALS['ADODB_SESS_CONN'])){
        $GLOBALS['ADODB_SESS_CONN']->Execute("DELETE FROM sessao WHERE expireref = '". $expireref ."';");
        return TRUE;
    }
    else
        return FALSE;
}

$log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';


// valida autenticacao neste ponto - variaveis de sessao disponiveis

// unset($_SESSION['sa_auth']);
if(empty($_SESSION['sa_auth'])) 
{
	unset($_SESSION['sa_auth']);

	// grava log de falha de acesso 
    $log_msg .= $param_conn['user'] .' - *** FALHA AO VERIFICAR LOGIN (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $param_conn['user'] .',pwd=) ***'."\n";
    
	error_log( $log_msg,3,$LOGIN_LOG_FILE);

	header('Location: '. $BASE_URL .'public/login_erro.php');
    exit;
}
else
{
	list($uid, $pwd) = $_SESSION['sa_auth'];

	$GLOBALS['USERID'] = $uid;
    $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','clear_session');

	// desconectar usuario com duas sessoes simultaneas
	if($GLOBALS['ADODB_SESS_CONN']->getOne("SELECT COUNT(*) FROM $SESS_TABLE WHERE expireref = '". $GLOBALS['USERID']  ."';") != 1)
	{
		unset($_SESSION['sa_auth']);

		// grava log de falha de acesso 
		$log_msg .= $param_conn['user'] .' - *** LOGIN DUPLICADO (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $param_conn['user'] .',pwd=) ***'."\n";

		error_log( $log_msg,3,$LOGIN_LOG_FILE);

		// TODO: redirecionar a uma pagina de aviso de sessao expirada por duplicidade
		header('Location: '. $BASE_URL .'public/login_erro.php');
	    exit;
	}	
}
?>
