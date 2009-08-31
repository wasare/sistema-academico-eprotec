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
	//SELE��ES DE GRID----------------------------------------------
	
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
	
	//EXCLUS�ES------------------------------------------------
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
			$config->ConfirmaOperacao("frmRefeicao.php","Voc� n�o pode excluir as refei��es de almo�o, jantar e caf� da manh�!");
		}
		
	break;
	
	//ALTERA��ES-----------------------------------------------
	
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
	
	//INSER��ES-------------------------------------------------

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