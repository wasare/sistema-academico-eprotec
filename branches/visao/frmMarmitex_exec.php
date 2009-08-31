<?php

$quantidade = trim($_POST['txtQuantidade']);
$custo = trim($_POST['txtCusto']);
$codigo = trim($_POST['txtCodigo']);
$nome = trim($_POST['txtNome']);
	
require_once("../modelo/clsUsuario.class.php");
require_once("../controle/gtiValida.class.php");
require_once("../config.class.php");

$valida = new gtiValidacao();
$valida->ValidaCampoRequerido($quantidade,'quantidade');
$valida->ValidaCampoRequerido($custo,'pre�o');
$valida->ValidaCampoNumerico($quantidade, 'quantidade');
$valida->ValidaCampoNumerico($custo, 'pre�o');
	
if ($valida->GetErro() == true)
{
	echo $valida->GetMensagem();
}
else
{
	$usu = new clsUsuario();
	$usu->RegistraMarmitex($codigo.$nome, $quantidade, $custo, ($custo * $quantidade));
	
	$config = new clsConfig();
	$config->ConfirmaOperacao("frmMarmitex.php","A opera��o foi executada com sucesso. Pesquise pela refei��o do tipo 'marmitex' nos relat�rios de hist�rico para visualizar os dados.");
}



?>