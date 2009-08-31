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
	//:: EFETUAÇÃO DE VENDA NORMAL :::::::::::::::::::::::::::::::::::::::::::::::
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
						
						//EXECUTA TESTE DE REPETIÇÃO DE BOLSISTA POR REFEIÇÃO (T ou F)
						if ($pam->GetRepeteBolsista() == 't')
						{
							//:: COM TESTE DE REPETIÇÃO DE BOLSISTA POR REFEIÇÃO:::::::::::::::::::::::::::::::::::
						
							if ($usu->RepeteBolsista() == 'f')
							{
							//carteirinha não repetida
							
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
										MostraErro('O registro dessa venda não pode ser feito pois excederia o limite de '.$usu->GetLimite().'. É necessário que ele faça um depósito para continuar a usar o sistema.', $valida);
									}
								}
								else
								{
								//estourou o limite
								MostraErro('O saldo deste usuário já excedeu o limite de '.$usu->GetLimite().'. É necessário que ele faça um depósito para continuar a usar o sistema.', $valida);
								}
								
							}
							else
							{
							//estourou o limite
								MostraErro('Este usuário é bolsista e não pode usar o serviço de bolsa pela segunda vez na mesma refeição.', $valida);
							}
						}
						else
						{
					//:: SEM TESTE DE REPETIÇÃO DE BOLSISTA POR REFEIÇÃO:::::::::::::::::::::::::::::		
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
									MostraErro('O registro dessa venda não pode ser feito pois excederia o limite de '.$usu->GetLimite().'. É necessário que ele faça um depósito para continuar a usar o sistema.', $valida);
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
				//não esta habilitado
				MostraErro('Este usuário foi bloqueado pelo seguinte motivo:' . $usu->GetMotivo(), $valida);
				}
			}
			else
			{
			//usuario não registrado
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
						MostraErro('Este usuário não existe! Encaminhe o aluno/funcionário a secretaria para cadastramento no SAGU!', $valida);
					}
					
				}
				else
				{
					//registro automatico esta desabilitado
					MostraErro('Este usuário não está habilitado para usar o refeitório. Para resolver, entre em contato com o administrador do sistema e faça o pedido do registro. No caso de estar usando o número SIAPE para a alimentação lembre-se também que este usuário deve estar registrado junto ao quadro de funcionários no SAGU.', $valida);
				}
			
			}
		
		}

	break;
	
	//:: EFETUAÇÃO DE VENDA POR VALE :::::::::::::::::::::::::::::::::::
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
				MostraErro('O número de vales disponíveis chegou a zero ou a venda por vales foi bloqueada pelo administrador do sistema!', $valida);
			}
		}
		
	break;
	
	//:: EFETUAÇÃO DE CORTESIA :::::::::::::::::::::::::::::::::::::::::
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
				MostraErro('O número de cortesias disponíveis chegou a zero ou a efetuação de cortesias foi bloqueada pelo administrador do sistema!', $valida);
			}
		}
	break;
}
?>
