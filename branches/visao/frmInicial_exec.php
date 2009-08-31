<?php

require_once("../modelo/clsOperador.class.php");
require_once("../config.class.php");

session_start();

$login = trim($_POST['txtLogin']); 
$senha = trim($_POST['txtSenha']);

$op = new clsOperador();
$msg = "Login ou Senha incorretos!";

if ($op->Autentica($login,$senha)==true)
{
	$msg = "Seu usuário foi bloqueado pelo administrador do sistema PRATO. Entre em contato para reativar seu acesso.";
	
	if ($op->PegaAcesso())
	{
		$perfil = $op->GetPerfil();
		
		if ($perfil == 'A')
		{
			$_SESSION['codigo'] = $op->GetCodigo();			
			header ("location: frmAdmin.php");			
			exit;
		}
		else if ($perfil == 'O')
		{
			$_SESSION['codigo'] = $op->GetCodigo();		
			header ("location: frmVenda.php");			
			exit;
		}	
	}
}

$config = new clsConfig();
$config->Logout(false);
$config->ConfirmaOperacao($config->GetPaginaPrincipal(),$msg);




?>
