<?php

require_once('../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'app/matricula/atualiza_diario_matricula.php');

$conn = new connection_factory($param_conn);

$diario_id = $_GET['diario_id'];

if(!is_numeric($diario_id))
{

    echo '<script language="javascript">
                window.alert("ERRO! Diario invalido!");
                window.close();
    </script>';
    exit;
}


// MARCA O DIARIO COMO CONCLUIDO
$sql1 = "UPDATE 
			disciplinas_ofer
         SET
            fl_digitada = 't' 
         WHERE  
            id = $diario_id;";

if($conn->Execute($sql1) === FALSE)
{
	envia_erro($sql1);
	exit;
}
else {

	echo '<script language=javascript>  window.alert(\'Diário finalizado com sucesso!\'); javascript:window.history.back(1); </script>';
	exit;
}

	
?>
