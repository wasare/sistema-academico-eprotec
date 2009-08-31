<?php

if (isset($_POST['txtMetodo']))
{
	$metodo = $_POST['txtMetodo'];
	$codigo = $_POST['txtCodigo'];
}
else
{
	$metodo = $_REQUEST['metodo'];
	$codigo = $_REQUEST['codigo'];
}


switch ($metodo)
{
	//SELEÇÕES DE GRID----------------------------------------------
	
	case 'carregagrid':
		header("Content-Type: text/xml");

		require_once("../controle/gtiXML.class.php");
		require_once("../modelo/clsMinhasConsultas.class.php");
		
		$xml = new gtiXML();
		$tipo = new clsMinhasConsultas();
		
		$arr = $tipo->ListaConsultaArray();
		$lista = $xml->ArrayParaXML($arr);
		
		echo $lista;
	break;
	
	case 'gerar':
		require_once("../modelo/clsRelatorio.class.php");
		require_once("../modelo/clsMinhasConsultas.class.php");
		
		$consulta = new clsMinhasConsultas();
		$consulta->PegaConsultaPorCodigo($codigo);
		
		$rel = new clsRelatorio();
		$rel->RelHistorico($consulta->GetSQL());
	break;
	
	//EXCLUSÕES------------------------------------------------
	case 'excluir':

		require_once("../modelo/clsMinhasConsultas.class.php");
		$comp = new clsMinhasConsultas();
		$comp->Excluir($codigo);
		
		$config = new clsConfig();
		$config->ConfirmaOperacao("frmMinhasConsultas.php","Registro excluido com sucesso!");

	break;
}
?>