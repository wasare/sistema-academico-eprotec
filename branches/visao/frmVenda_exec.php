<?php
require_once("../modelo/clsUsuario.class.php");
require_once("../modelo/clsOperador.class.php");
require_once("../modelo/clsConfiguracoes.class.php");
require_once("../controle/gtiValida.class.php");

function MostraErro($mensagem,$valida)
{	
	$valida->SetMensagem('<script type="text/javascript" language="javascript">alert("'.$mensagem);
	echo $valida->GetMensagem();
}

$peso = $_POST['peso'];
$codigo = $_POST['usuario'];
$metodo = $_POST['txtMetodo'];
$codoperador = $_POST['txtCodigo'];


switch ($metodo)
{
	//:: EFETUA��O DE VENDA NORMAL :::::::::::::::::::::::::::::::::::::::::::::::
	case 'registravenda':

		$op = new clsOperador();
		$op->SelecionaPorCodigo($codoperador);
		
		$usu = new clsUsuario();
		$func = $usu->UsandoSiape($codigo);
		$siape = 'f';
		if (trim($func) != '')
		{
			$codigo = $func;
			$siape = 't';
		}
		
		$usu->PegaUsuarioPorCodigo($codigo);
		
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		$valida = new gtiValidacao();
       
		$valida->ValidaCampoRequerido($peso,'peso');
		$valida->ValidaCampoRequerido($codigo,'usuario');
		$valida->ValidaCampoNumerico($peso, 'peso');
		$valida->ValidaCampoNumerico($codigo, 'usuario');
			
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			if (trim($usu->GetNome()) != '')
			{
			//usuario registrado
				if (trim($usu->GetHabilitado()) == 't')
				{	
					//esta habilitado	
	
					if ($usu->RepeteRefeicao() == 'f')
					{	
						
						//EXECUTA TESTE DE REPETI��O DE BOLSISTA POR REFEI��O (T ou F)
						if ($pam->GetRepeteBolsista() == 't')
						{
							//:: COM TESTE DE REPETI��O DE BOLSISTA POR REFEI��O:::::::::::::::::::::::::::::::::::
						
							if ($usu->RepeteBolsista() == 'f')
							{
							//carteirinha n�o repetida
							
								if ($usu->GetSaldo() > $usu->GetLimite())
								{
								//esta dentro do limite
									$preco = $usu->CalculaPreco($peso);
									
									if (($usu->GetSaldo() - $preco) > $usu->GetLimite())
									{
										//:::::::::: EXECUTA A VENDA NESTE  INSTANTE
										$usu->RegistraVenda($peso, $codoperador . $op->GetNome());
										header('location:frmVenda.php');
									}
									else
									{
										//estourou o limite ajustado
										MostraErro('O registro dessa venda n�o pode ser feito pois excederia o limite de '.$usu->GetLimite().'. � necess�rio que ele fa�a um dep�sito para continuar a usar o sistema.', $valida);
									}
								}
								else
								{
								//estourou o limite
								MostraErro('O saldo deste usu�rio j� excedeu o limite de '.$usu->GetLimite().'. � necess�rio que ele fa�a um dep�sito para continuar a usar o sistema.', $valida);
								}
								
							}
							else
							{
							//estourou o limite
								MostraErro('Este usu�rio � bolsista e n�o pode usar o servi�o de bolsa pela segunda vez na mesma refei��o.', $valida);
							}
						}
						else
						{
					//:: SEM TESTE DE REPETI��O DE BOLSISTA POR REFEI��O:::::::::::::::::::::::::::::		
							if ($usu->GetSaldo() > $usu->GetLimite())
							{
							//esta dentro do limite
								$preco = $usu->CalculaPreco($peso);
								
								if (($usu->GetSaldo() - $preco) > $usu->GetLimite())
								{
									//:::::::::: EXECUTA A VENDA NESTE  INSTANTE
									$usu->RegistraVenda($peso, $codoperador . $op->GetNome());
									header('location:frmVenda.php');
								}
								else
								{
									//estourou o limite ajustado
									MostraErro('O registro dessa venda n�o pode ser feito pois excederia o limite de '.$usu->GetLimite().'. � necess�rio que ele fa�a um dep�sito para continuar a usar o sistema.', $valida);
								}
							}							
						}				
						
					}
					else
					{
						header('location:frmVenda.php');
					}
				}
				else
				{
				//n�o esta habilitado
				MostraErro('Este usu�rio foi bloqueado pelo seguinte motivo:' . $usu->GetMotivo(), $valida);
				}
			}
			else
			{
			//usuario n�o registrado
				if ($pam->GetRegistroAut() == 't')
				{
					//registro automatico habilitado
					
					$usu->PegaUsuarioSagu($codigo);
					if (trim($usu->GetNome()) != '')
					{
						//:::::::::: EXECUTA A VENDA NESTE  INSTANTE
						$usu->RegistroAutomatico($codigo, $siape, $peso, $codoperador . $op->GetNome());
						header('location:frmVenda.php');
					}
					else
					{
						MostraErro('Este usu�rio n�o existe! Encaminhe o aluno/funcion�rio a secretaria para cadastramento no SAGU!', $valida);
					}
					
				}
				else
				{
					//registro automatico esta desabilitado
					MostraErro('Este usu�rio n�o est� habilitado para usar o refeit�rio. Para resolver, entre em contato com o administrador do sistema e fa�a o pedido do registro. No caso de estar usando o n�mero SIAPE para a alimenta��o lembre-se tamb�m que este usu�rio deve estar registrado junto ao quadro de funcion�rios no SAGU.', $valida);
				}
			
			}
		
		}

	break;
	
	//:: EFETUA��O DE VENDA POR VALE :::::::::::::::::::::::::::::::::::
	case 'vendacomvale':
	
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($peso,'peso');
		$valida->ValidaCampoNumerico($peso, 'peso');
		
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			$op = new clsOperador();
			$op->SelecionaPorCodigo($codoperador);
			
			$usu = new clsUsuario();
			if ($usu->RegistraVendaVale($peso, $codoperador . $op->GetNome()) == 't')
			{
				header('location:frmVenda.php');
			}
			else
			{
				MostraErro('O n�mero de vales dispon�veis chegou a zero ou a venda por vales foi bloqueada pelo administrador do sistema!', $valida);
			}
		}
		
	break;
	
	//:: EFETUA��O DE CORTESIA :::::::::::::::::::::::::::::::::::::::::
	case 'efetuacortesia':
	
		$valida = new gtiValidacao();
		$valida->ValidaCampoRequerido($peso,'peso');
		$valida->ValidaCampoNumerico($peso, 'peso');
		
		if ($valida->GetErro() == true)
		{
			echo $valida->GetMensagem();
		}
		else
		{
			$op = new clsOperador();
			$op->SelecionaPorCodigo($codoperador);
			
			$usu = new clsUsuario();
			if ($usu->RegistraVendaCortesia($peso, $codoperador . $op->GetNome()) == 't')
			{
				header('location:frmVenda.php');
			}
			else
			{
				MostraErro('O n�mero de cortesias dispon�veis chegou a zero ou a efetua��o de cortesias foi bloqueada pelo administrador do sistema!', $valida);
			}
		}
	break;
}
?>
