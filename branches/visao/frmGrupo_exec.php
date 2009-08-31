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
		require_once("../modelo/clsGrupo.class.php");
		
		$xml = new gtiXML();
		$tipo = new clsGrupo();
		
		$arr = $tipo->ListaGrupoArray();
		$lista = $xml->ArrayParaXML($arr);
		
		echo $lista;
	break;
	
	//EXCLUS�ES------------------------------------------------
	case 'excluir':
		if($codigo != 1 && $codigo != 2 && $codigo != 4 && $codigo != 5 && $codigo != 6)
		{
		require_once("../modelo/clsGrupo.class.php");
		$comp = new clsGrupo();
		$comp->Excluir($codigo);
		
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmGrupo.php","Registro excluido com sucesso!");
		}
		else
		{
			require_once("../config.class.php");
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmGrupo.php","Voc� n�o pode excluir o grupo Alunos, Funcion�rios e Bolsistas!");
		}
	break;
	
	//ALTERA��ES-----------------------------------------------
	
	case 'altera':
		require_once("../controle/gtiValida.class.php");
		
		$nomeNovo = $_POST['txtNome'];	
		$limiteNovo = $_POST['txtLimite'];	
		
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($nomeNovo,'nome');
		$valida->ValidaCampoRequerido($limiteNovo,'limite');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			//ALTERANDO
			require_once("../modelo/clsGrupo.class.php");
			require_once("../config.class.php");
			
			$comp = new clsGrupo();		
			
			$comp->SetCodigo($codigo);
			$comp->SetDescricao($nomeNovo);
			$comp->SetLimite($limiteNovo);
		
			$comp->Alterar();
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmGrupo.php","Registro alterado com sucesso!");
		}
	break;
	
	//INSER��ES-------------------------------------------------

	case 'novo':
		require_once("../controle/gtiValida.class.php");
		
		$nomeNovo = $_POST['txtNome'];	
		$limiteNovo = $_POST['txtLimite'];	
		
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($nomeNovo,'nome');
		$valida->ValidaCampoRequerido($limiteNovo,'limite');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			require_once("../modelo/clsGrupo.class.php");
			require_once("../config.class.php");
			
			$comp = new clsGrupo();		
			
			$comp->SetDescricao($nomeNovo);
			$comp->SetLimite($limiteNovo);
			
			$comp->Salvar();
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmGrupo.php","Registro salva com sucesso!");
		}
	break;
}
?>