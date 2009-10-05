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

		function marca_aberto(diario)
		{
			if (! confirm(\'Você deseja marcar como aberto o diário \' + diario + \'?\' + \'\n Com o diário aberto o prefessor poderá fazer alterações!\'))
			{
				javascript:window.history.back(1);
				return false;
			}
			else 
			{
				self.location = "marca_aberto.php?diario_id=" + diario + "&confirma=1";
				return true;
			}
		}
		marca_aberto('. $diario_id .');
     </script>';
	
	exit;
}
else
{

	// MARCA O DIARIO COMO CONCLUIDO
	$sql1 = "UPDATE 
			disciplinas_ofer
         SET
            fl_digitada = 'f' 
         WHERE  
            id = $diario_id;";

	if($conn->Execute($sql1) === FALSE)
	{
		envia_erro($sql1);
		exit;
	}
	else {

		echo '<script language=javascript>  window.alert(\'Diário '. $diario_id .' reaberto com sucesso!\'); javascript:window.history.back(2); </script>';
		exit;
	}
}
	
?>
