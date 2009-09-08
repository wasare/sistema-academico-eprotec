<?php

require_once(dirname(__FILE__).'/../../configs/configuracao.php');

/*
  TODO: registrar em log o acesso com sucesso ou falha - gravar em banco de dados
*/

$log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

// fazer a verificacao do login neste ponto
$autenticado = (int) 1;

if($autenticado === 1)
{
	// grava log de acesso
    $log_msg .= $uid .' - *** LOGIN ACEITO (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $uid .',pwd=) ***'."\n";

	$GLOBALS['USERID'] = trim($_POST['uid']);
	$GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','clear_session');
	
	$_SESSION['sa_auth'] = trim($_POST['uid']) .':'. trim($_POST['pwd']);
	$_SESSION['sa_login'] = $_POST['sa_login'];

	error_log($log_msg,3,$LOGIN_LOG_FILE);

	switch ($_POST['sa_login']) {
    case 'sa_login':
        header('Location: '. $BASE_URL .'app/inicio.php');
        break;
    case 'web_diario_login':
        //header('Location: '. $BASE_URL .'app/webdiario/');
		require_once($BASE_DIR .'app/webdiario/principal.php');
        break;
    case 'aluno_login':
        header('Location: '. $BASE_URL .'app/aluno');
        break;
	}

    exit;

}
else
{
	// grava log de acesso
	$log_msg .=  $uid .' - *** LOGIN RECUSADO (host='. $param_conn['host'] .',db='. $param_conn['database'] .',uid='. $uid .',pwd=) ***'."\n";
	error_log($log_msg,3,$LOGIN_LOG_FILE);
	
	// TODO: retornar o erro de tentativa de login na proprio formulario de login 
	if ($_POST['sa_login'] == 'sa_login') header('Location: '. $BASE_URL .'public/login_erro.php');
    if ($_POST['web_diario_login'] == 'web_diario_login') header('Location: '. $BASE_URL .'public/login_erro.php');
    if ($_POST['aluno_login'] == 'aluno_login') header('Location: '. $BASE_URL .'public/login_erro.php');
	
	exit;
}

?>
