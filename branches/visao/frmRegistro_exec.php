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
			$config->ConfirmaOperacao("frmUsuario.php","Os dados referentes ao ingresso desse usu�rio foram alterados com sucesso e suas informa��es j� est�o atualizadas!");
		}
	break;
	
	//INSER��ES-------------------------------------------------
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
			$config->ConfirmaOperacao("frmUsuario.php","O usu�rio foi registrado corretamente no PRATO e agora est� habilidato a utilizar o sistema!");
		}
	break;
	
}

?>