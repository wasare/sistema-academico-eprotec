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
$valida->ValidaCampoRequerido($custo,'preço');
$valida->ValidaCampoNumerico($quantidade, 'quantidade');
$valida->ValidaCampoNumerico($custo, 'preço');
	
if ($valida->GetErro() == true)
{
	echo $valida->GetMensagem();
}
else
{
	$usu = new clsUsuario();
	$usu->RegistraMarmitex($codigo.$nome, $quantidade, $custo, ($custo * $quantidade));
	
	$config = new clsConfig();
	$config->ConfirmaOperacao("frmMarmitex.php","A operação foi executada com sucesso. Pesquise pela refeição do tipo 'marmitex' nos relatórios de histórico para visualizar os dados.");
}



?>