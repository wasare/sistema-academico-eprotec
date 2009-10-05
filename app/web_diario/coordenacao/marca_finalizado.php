<?php

require_once('../../setup.php');
require_once($BASE_DIR .'core/web_diario.php');
require_once($BASE_DIR .'app/matricula/atualiza_diario_matricula.php');

$conn = new connection_factory($param_conn);

$diario_id = $_GET['diario_id'];

$confirma = '';
if(isset($_GET['confirma']))
    $confirma = $_GET['confirma'];


if(!is_numeric($diario_id))
{

    echo '<script language="javascript">
                window.alert("ERRO! Diario invalido!");
                window.close();
    </script>';
    exit;
}

if(!is_numeric($confirma) || empty($confirma))
{

    echo '<script language="javascript">

        function marca_finalizado(diario)
        {
			if (! confirm(\'Você deseja realmente finalizar o diário \' + diario + \'?\' + \'\n Depois de finalizado o professor não poderá fazer alterações!\'))
            {
                javascript:window.history.back(1);
                return false;
            }
            else 
            {
                self.location = "marca_finalizado.php?diario_id=" + diario + "&confirma=1";
                return true;
            }
        }
        marca_finalizado('. $diario_id .');
     </script>';

    exit;
}
else
{

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

		echo '<script language=javascript>  window.alert(\'Diário '. $diario_id .' finalizado com sucesso!\'); javascript:window.history.back(1); </script>';
		exit;
	}
}
	
?>
