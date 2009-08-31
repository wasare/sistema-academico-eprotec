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
		require_once("../modelo/clsOperador.class.php");
		
		$xml = new gtiXML();
		$op = new clsOperador();
		
		$arr = $op->ListaOperadorArray();
		$lista = $xml->ArrayParaXML($arr);
		
		echo $lista;
	break;
	
	//SETA ACESSO DO USUARIO
	case 'setaacesso':
		$estado = $_REQUEST['estado'];
		require_once("../modelo/clsOperador.class.php");
		require_once("../config.class.php");
		
		$op = new clsOperador();
		$op->SetaAcesso($estado,$codigo);
		
		$config = new clsConfig();
		$config->ConfirmaOperacao("frmOperador.php","Status de acesso alterado com sucesso!");
	break;
	
	//EXCLUSеES------------------------------------------------
	case 'excluir':
		if ($codigo != 1 && $codigo != 2)
		{
			require_once("../modelo/clsOperador.class.php");
			$op = new clsOperador();
			$op->Excluir($codigo);
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmOperador.php","Registro excluido com sucesso!");
		}
		else
		{
			require_once("../config.class.php");
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmOperador.php","Vocъ nуo pode excluir os operadores Administrador e Operador!");
		}
	break;
	
	//ALTERAЧеES-----------------------------------------------
	
	case 'altera':
		require_once("../controle/gtiValida.class.php");
		
		$codigo = $_POST['txtCodigo'];	
		$nome = $_POST['txtNome'];
		$login = $_POST['txtLogin'];
		$senha = $_POST['txtSenha'];
		$perfil = $_POST['dpdPerfil'];	

		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($nome,'nome');
		$valida->ValidaCampoRequerido($login,'login');
		$valida->ValidaCampoRequerido($senha,'senha');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			//ALTERANDO
			require_once("../modelo/clsOperador.class.php");
			require_once("../config.class.php");
			
			$op = new clsOperador();		
			
			$op->SetCodigo($codigo);
			$op->SetNome($nome);
			$op->SetLogin($login);
			$op->SetSenha($senha);
			$op->SetPerfil($perfil);
		
			$op->Alterar();
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmOperador.php","Registro alterado com sucesso!");
		}
	break;
	
	//INSERЧеES-------------------------------------------------

	case 'novo':
		require_once("../controle/gtiValida.class.php");
		
		$codigo = $_POST['txtCodigo'];	
		$nome = $_POST['txtNome'];
		$login = $_POST['txtLogin'];
		$senha = $_POST['txtSenha'];
		$perfil = $_POST['dpdPerfil'];	

		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($nome,'nome');
		$valida->ValidaCampoRequerido($login,'login');
		$valida->ValidaCampoRequerido($senha,'senha');
		
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			require_once("../modelo/clsOperador.class.php");
			require_once("../config.class.php");
			
			$op = new clsOperador();		
			
			$op->SetCodigo($codigo);
			$op->SetNome($nome);
			$op->SetLogin($login);
			$op->SetSenha($senha);
			$op->SetPerfil($perfil);
		
			$op->Salvar();
			
			$config = new clsConfig();
			$config->ConfirmaOperacao("frmOperador.php","Registro salvo com sucesso!");
		}
	break;
}
?>