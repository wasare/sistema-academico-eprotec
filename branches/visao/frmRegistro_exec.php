<?php

$codigo = trim($_POST['hdCodigo']);
$status = $_POST['hdStatus'];
$datacad = trim($_POST['txtDataCad']);
$credito = trim(@$_POST['txtCredito']);
$grupo = $_POST['dpdGrupo'];
$nome =  trim($_POST['hdNome']);

switch (trim($status))
{	
	case 'alterar': //alterando
		require_once("../controle/gtiValida.class.php");	
		
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($datacad,'data de ingresso');
		$valida->ValidaData($datacad);
		$valida->ValidaComparacao($grupo,'0','grupo','==');
		
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			require_once("../modelo/clsUsuario.class.php");
			require_once("../config.class.php");
			
			$usu = new clsUsuario();			
			$usu->AlteraRegistro($codigo,$datacad,$grupo);
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmUsuario.php","Os dados referentes ao ingresso desse usuсrio foram alterados com sucesso e suas informaчѕes jс estуo atualizadas!");
		}
	break;
	
	//INSERЧеES-------------------------------------------------
	case 'salvar': //salvando 
		require_once("../controle/gtiValida.class.php");	
		
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($datacad,'data de ingresso');
		$valida->ValidaData($datacad);
		$valida->ValidaComparacao($grupo,'0','grupo','==');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			require_once("../modelo/clsUsuario.class.php");
			require_once("../config.class.php");
			
			$usu = new clsUsuario();			
			$usu->Registra($codigo,$datacad,$grupo, $credito);
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmUsuario.php","O usuсrio foi registrado corretamente no PRATO e agora estс habilidato a utilizar o sistema!");
		}
	break;
	
}

?>