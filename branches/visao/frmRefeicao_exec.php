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
	//SELEЧеES DE GRID----------------------------------------------
	
	case 'carregagrid':
		header("Content-Type: text/xml");

		require_once("../controle/gtiXML.class.php");
		require_once("../modelo/clsRefeicao.class.php");
		
		$xml = new gtiXML();
		$tipo = new clsRefeicao();
		
		$arr = $tipo->ListaRefeicaoArray();
		$lista = $xml->ArrayParaXML($arr);
		
		echo $lista;
	break;
	
	//EXCLUSеES------------------------------------------------
	case 'excluir':
		if ($codigo != 1 && $codigo != 2 && $codigo != 3)
		{
			require_once("../modelo/clsRefeicao.class.php");
			$comp = new clsRefeicao();
			$comp->Excluir($codigo);
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmRefeicao.php","Registro excluido com sucesso!");
		}
		else
		{
			require_once("../config.class.php");
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmRefeicao.php","Vocъ nуo pode excluir as refeiчѕes de almoчo, jantar e cafщ da manhу!");
		}
		
	break;
	
	//ALTERAЧеES-----------------------------------------------
	
	case 'altera':
		require_once("../controle/gtiValida.class.php");
		
		$nomeNovo = $_POST['txtNome'];	
		$custoNovo = $_POST['txtCusto'];
		$unidadeNovo = $_POST['dpdUnidade'];	
		
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($nomeNovo,'nome');
		$valida->ValidaCampoRequerido($custoNovo,'custo');
		$valida->ValidaCampoRequerido($unidadeNovo,'unidade');
		$valida->ValidaCampoNumerico($custoNovo, 'custo');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			//ALTERANDO
			require_once("../modelo/clsRefeicao.class.php");
			require_once("../config.class.php");
			
			$comp = new clsRefeicao();		
			
			$comp->SetCodigo($codigo);
			$comp->SetDescricao($nomeNovo);
			$comp->SetCusto($custoNovo);
			$comp->SetUnidade($unidadeNovo);
		
			$comp->Alterar();
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmRefeicao.php","Registro alterado com sucesso!");
		}
	break;
	
	//INSERЧеES-------------------------------------------------

	case 'novo':
		require_once("../controle/gtiValida.class.php");
		
		$nomeNovo = $_POST['txtNome'];	
		$custoNovo = $_POST['txtCusto'];
		$unidadeNovo = $_POST['dpdUnidade'];	
		
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($nomeNovo,'nome');
		$valida->ValidaCampoRequerido($custoNovo,'custo');
		$valida->ValidaCampoRequerido($unidadeNovo,'unidade');
		$valida->ValidaCampoNumerico($custoNovo, 'custo');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			require_once("../modelo/clsRefeicao.class.php");
			require_once("../config.class.php");
			
			$comp = new clsRefeicao();		
			
			$comp->SetDescricao($nomeNovo);
			$comp->SetCusto($custoNovo);
			$comp->SetUnidade($unidadeNovo);
		
			$comp->Salvar();
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmRefeicao.php","Registro salva com sucesso!");
		}
	break;
}
?>