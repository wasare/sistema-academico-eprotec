<?php
	session_start();
	if($_GET['periodo'] != "")// AND is_numeric($_GET['periodo']))
	{
		$_SESSION['periodo'] = '';
		$_SESSION['periodo'] = $_GET['periodo'];
		header("Location: diarios.php?periodo=". $_SESSION['periodo']);		
	}
	else
	{
		echo '<script language="javascript">
		        window.alert("ERRO! Primeiro selecione um periodo!");
		</script>';
		exit;
		// javascript:window.history.back(1);
						 
	}



?>
