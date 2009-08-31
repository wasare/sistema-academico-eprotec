<?php

$codigo = trim($_POST['hdCodigo']);
$acesso = trim($_POST['dpdAcesso']);
$motivo = trim($_POST['txtMotivo']);
	
require_once("../modelo/clsUsuario.class.php");
require_once("../config.class.php");

$usu = new clsUsuario();			
$usu->AlteraAcesso($codigo,$acesso,$motivo);

$config = new clsConfig();
$config->ConfirmaOperacao("frmAcesso.php?codigo=".$codigo,"Os dados referentes ao acesso desse usuário foram alterados com sucesso e suas informações já estão atualizadas!");

?>