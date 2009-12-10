<?php

require_once(dirname(__FILE__) .'/../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');

$conn = new connection_factory($param_conn);

$diario_id = (int) $_GET['id'];
$operacao = $_GET['do'];

// INVERTE A MARCACAO DE ESTADO DO DIARIO
$sql1 = "SELECT
            fl_concluida
		 FROM
			disciplinas_ofer
         WHERE
            id = $diario_id;";

$fl_concluida = $conn->get_one($sql1);

if($fl_concluida === 'f') 
	$flag = 't';
else
	$flag = 'f';


// MARCA/DESMARCA O DIARIO COMO CONCLUIDO
$sql2 = "UPDATE 
			disciplinas_ofer
         SET
            fl_concluida = '$flag' 
         WHERE  
            id = $diario_id;";

$conn->Execute($sql2);

?>
