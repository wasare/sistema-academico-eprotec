<?php

$registroaut = trim(@$_POST['chkRegistroAut']);
if ($registroaut != '')
{
	$registroaut = 't';
}
else
{
	$registroaut = 'f';
}

$repetebolsista = trim(@$_POST['chkBolsista']);
if ($repetebolsista != '')
{
	$repetebolsista = 't';
}
else
{
	$repetebolsista = 'f';
}

$permitevale = trim($_POST['dpdPermiteVale']);
$numvale = trim($_POST['txtNumVale']);
$permitecortesia = trim($_POST['dpdPermiteCortesia']);
$numcortesia = trim($_POST['txtNumCortesia']);
$precovale= trim($_POST['txtPrecoVale']);
	
require_once("../modelo/clsConfiguracoes.class.php");
require_once("../config.class.php");

$par = new clsConfiguracoes();			
$par->Altera($registroaut,$permitevale,$numvale,$permitecortesia,$numcortesia, $precovale, $repetebolsista);

$config = new clsConfig();
$config->ConfirmaOperacao("frmConfiguracoes.php","Os dados referentes ao acesso desse usuário foram alterados com sucesso e suas informações já estão atualizadas!");

?>