<?php

require_once('../../webdiario.conf.php');
require_once($BASE_DIR_WEBDIARIO .'conf/verifica_acesso.php');

$getofer = $_GET['ofer'];



// MARCA O DIARIO COMO CONCLUIDO
$sql1 = "UPDATE 
			disciplinas_ofer
         SET
            fl_digitada = 'f' 
         WHERE  
            id = $getofer;";

//echo $sql1;die;
$res = consulta_sql($sql1);

if(is_string($res))
{
	echo $res;
	exit;
}
else {

	echo '<script language=javascript>  window.alert(\'Di�rio reaberto com sucesso!\'); javascript:window.history.back(1); </script>';
	
	//echo '<script language=javascript>javascript:window.history.back(1); </script>';
	
	exit;
}

	
?>
