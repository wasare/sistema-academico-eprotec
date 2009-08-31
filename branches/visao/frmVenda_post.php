<?php
require_once("../modelo/clsRefeicao.class.php");
require_once("../modelo/clsUsuario.class.php");
require_once("../modelo/clsConfiguracoes.class.php");

$metodo = @$_REQUEST['metodo'];

switch (trim($metodo))
{

	case 'carregarefquant':
		
		$codigo = @$_REQUEST['codigo'];
		$ref = new clsRefeicao();
		$ref->PegaRefeicaoPorCodigo($codigo);
		$unidade = "";
		if (trim($ref->GetUnidade()) == 'PG')
		  {
			$unidade =  'Peso em Gramas';
		  }
		  else
		  {
			$unidade =  'Unidades';
		  };
		  
		echo
		
			 $unidade
		;
				
	break;
	
	case 'carregarefcusto':
		
		$codigo = @$_REQUEST['codigo'];
		$ref = new clsRefeicao();
		$ref->PegaRefeicaoPorCodigo($codigo);
		
		$pam = new clsConfiguracoes();
		$pam->AlteraRefeicaoPadrao($codigo);
		
		echo 
		'
			<div align="left">R$ 
			  '.
			  number_format($ref->GetCusto(), 2, ',', '')
			  .'
			  </div>
		';
				
	break;
	
	case 'carregausuario':
		
		$codigo = @$_REQUEST['codigo'];
		
		if (trim($codigo)=='')
		{
			$codigo = '0';
		}
		
		$usuario = new clsUsuario();
		$usuario->PegaUsuarioPorCodigo($codigo);
		
		$nome = '-- Selecione --';
		if (trim($usuario->GetNome()) != '')
		{
			$nome = $usuario->GetNome();
		}
		
		$grupo = '-- Selecione --';
		if (trim($usuario->GetNomeGrupo()) != '')
		{
			$grupo = $usuario->GetNomeGrupo();
		}
		
		$saldo = '-- Selecione --';
		if (trim($usuario->GetSaldo()) != '0')
		{
			$saldo = $usuario->GetSaldo();
		}
		
		$limite = '-- Selecione --';
		if (trim($usuario->GetLimite()) != '0')
		{
			$limite = $usuario->GetLimite();
		}
		
		
		
		echo 
		'
			<table width="83%" border="0" cellpadding="0" cellspacing="0" class="rodape">
			  <tr>
				<th scope="col"><div align="left" class="rodape style33">Nome:</div></th>
				<th scope="col"><div align="left" class="rodape style33">'.$nome.'</div></th>
			  </tr>
			  <tr>
				<td><div align="left" class="rodape style33">Saldo:</div></td>
				<td><div align="left" class="rodape style33">R$ '.number_format($saldo, 2, ',', '').'</div></td>
			  </tr>
			  <tr>
				<td><div align="left" class="rodape style33">Grupo:</div></td>
				<td><div align="left" class="rodape style33">'.$grupo.'</div></td>
			  </tr>
			  <tr>
				<td><div align="left" class="rodape style33">Limite:</div></td>
				<td><div align="left" class="rodape style33">R$ '.number_format($limite, 2, ',', '').'</div></td>
			  </tr>
			</table>
		';
				
	break;
	
}

?>