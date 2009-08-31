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

$valor = $_REQUEST['valor'];

switch ($metodo)
{
	//SELEЧеES DE GRID----------------------------------------------
	
	case 'carregagrid':
		header("Content-Type: text/xml");

		require_once("../controle/gtiXML.class.php");
		require_once("../modelo/clsUsuario.class.php");
		
		$xml = new gtiXML();
		$tipo = new clsUsuario();
		
		$arr = $tipo->ListaUsuarioSaguArray();
		$lista = $xml->ArrayParaXML($arr);
		
		echo $lista;
	break;
	
	case 'filtrar':
		header("Content-Type: text/xml");

		require_once("../controle/gtiXML.class.php");
		require_once("../modelo/clsUsuario.class.php");
		
		$xml = new gtiXML();
		$tipo = new clsUsuario();
		
		$arr = $tipo->FiltraUsuarioSaguArray(strtolower($valor));
		$lista = $xml->ArrayParaXML($arr);
		
		echo $lista;
	break;
	
}
?>