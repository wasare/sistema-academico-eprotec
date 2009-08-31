<?php
session_start();

require_once("../config.class.php");
require_once("../modelo/clsOperador.class.php");
require_once("../controle/gtiData.class.php");


$config = new clsConfig();
	
if ((isset($_SESSION['codigo'])))
{
	$admin = new clsOperador();
	$admin->SelecionaPorCodigo(trim($_SESSION['codigo']));
	
	$codigo = $_REQUEST['codigo'];
	
	require_once("../modelo/clsGrupo.class.php");
	$gr = new clsGrupo();
	
	require_once("../modelo/clsUsuario.class.php");
	$usu = new clsUsuario();
	$usu->PegaUsuarioPorCodigo($codigo);
	
	$nome = "";
	$grupo = '<option value="0">--Selecione--</option>';
	$dataIngresso = "";
	$saldoatual = "0";
	$status = '<span style="color: #0000FF">REGISTRADO</span>';
	$st = 'alterar';
	$botao = "Registrar";
	
	if (trim($usu->GetNome())!="")
	{
		$nome = trim($usu->GetNome());
		
		$dataIngresso = str_replace("/", "-", trim($usu->GetDataCad()));
		$grupoatual = '<option value="'.$usu->GetCodGrupo().'">'.$usu->GetNomeGrupo().'</option>';
		$saldoatual = $usu->GetSaldo();
		$botao = "Alterar Registro";
	}
	else 
	{
		$formatData = new gtiData();
		$dataIngresso = date('d-m-Y');
		
		$registro = new clsUsuario();
		$registro->PegaUsuarioSagu($codigo);
		$nome = $registro->GetNome();
		
		$st = 'salvar';
		$status = '<span style="color: #FF0000">NÃO REGISTRADO</span>';
	}
}
else
{
	$config->Logout(false);
	$config->ConfirmaOperacao($config->GetPaginaPrincipal(),"Você não tem permissão para acessar essa página!");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Controle de Registro -  PRATO</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    <!-- ESTILO JANELA -->
    <link href="estilo/janela/default.css" rel="stylesheet" type="text/css" />	
  	<link href="estilo/janela/spread.css" rel="stylesheet" type="text/css" />	
  	<link href="estilo/janela/alert.css" rel="stylesheet" type="text/css" />	
  	<link href="estilo/janela/alert_lite.css" rel="stylesheet" type="text/css" />
  	<link href="estilo/janela/alphacube.css" rel="stylesheet" type="text/css" />	
  	<link href="estilo/janela/debug.css" rel="stylesheet" type="text/css"/>	
  	
    <script src="js/validator.js" type="text/javascript"></script>
    
    <!-- MENU ESQUERDO -->
    <script src="js/menu_direito/milonic_src.js" type="text/javascript"></script>
    <script src="js/menu_direito/mmenudom.js" type="text/javascript"></script>
    <script src="js/menu_direito/dados/mn_dados.js" type="text/javascript"></script>
    <script src="js/menu_direito/contextmenu.js" type="text/javascript"></script>  
    
    <script src="js/prototype.js" type="text/javascript"></script>
    
    <!-- JANELA -->
    <script type="text/javascript" src="js/janela/effects.js"> </script>
  	<script type="text/javascript" src="js/janela/window.js"> </script>
  	<script type="text/javascript" src="js/janela/window_effects.js"> </script>
  	<script type="text/javascript" src="js/janela/debug.js"> </script>  
  	
    <style type="text/css">
<!--
.style23 {color: #999999}
-->
    </style>
</head>
<body>
<script language="javascript">
function submitForm(nome)
{
	$(nome).submit();
}
</script>
		
    <table align="center" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td valign="top">
                <form id="frmRegistro" name="frmRegistro" method="post" action="frmRegistro_exec.php">
                <table align="center" cellpadding="0" cellspacing="1" width="780px">
                    <tr>
                        <td>
                            <img alt="" src="imagens/banner.jpg" />
                            </td>
                    </tr>
                    <tr>
                        <td class="barra">
                            <table cellpadding="0" cellspacing="0" class="style4">
                                <tr>
                                    <td class="style7">
                                        &nbsp;&nbsp; Seja Bem Vindo <?php echo $admin->GetNome(); ?>!&nbsp;</td>
                                    <td >
                                     
                                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                         <td><img src="imagens/back.gif" width="8" height="30" style="width: 17px; height: 16px" /></td>
                                         <td>&nbsp;<a href="frmUsuario.php"><b>VOLTAR</b></a></td>
                                       </tr>
                                     </table>
                                     
                                     </td>
                              <td valign="middle">
                                        <table cellpadding="0" cellspacing="0" class="style5">
                                            <tr>
                                                <td class="style6">&nbsp;
                                        <img alt="" src="imagens/bt_logout.jpg" style="margin-top: 0px" /></td>
                                                <td>
                                                    &nbsp;<b><a href="<?php echo $config->GetPaginaPrincipal() ?>">SAIR</a></b></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            </td>
                    </tr>
                    <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center"><table width="61%" border="0" cellspacing="0" cellpadding="0" class="tabelaDeTipos">
                              <tr>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2"><div align="center"><strong>REGISTRO DE USU&Aacute;RIO NO PRATO</strong></div></td>
                              </tr>
                              <tr>
                                <td width="34%">&nbsp;</td>
                                <td width="66%">&nbsp;</td>
                              </tr>
                              <tr>
                                <td>Usu&aacute;rio:</td>
                                <td><label><?php echo $nome; ?></label></td>
                                <input type="hidden" name="hdNome" id="hdNome" value="<?php echo $nome; ?>"/>
                                 <input type="hidden" name="hdCodigo" id="hdCodigo" value="<?php echo $codigo; ?>"/>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>Status:</td>
                                <td><?php echo $status; ?></td>
                                <input type="hidden" name="hdStatus" id="hdStatus" value="<?php echo $st; ?>"/>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>Data de Ingresso:</td>
                                <td><label>
                                  <input type="text" name="txtDataCad" id="txtDataCad" class="caixaPequena" value="<?php echo $dataIngresso;  ?>"/>
                                  <span class="style23">*exemplo: dd-mm-aaaa</span></label></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              
                              <?php 
							  if ($st != 'alterar')
							  {
							  	echo
								'
								<tr>
								<td>Cr&eacute;dito Inicial</td>
								<td><input type="text" name="txtCredito" id="txtCredito" class="caixaPequena" value="0"/> 
								  <span class="style23">*<strong>saldo atual</strong>:'.number_format($saldoatual, 2, ',', '').' R$</span></td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								';
							  }
							  
							  
							  ?>
                              
                              
                              
                              <tr>
                                <td>Grupo:</td>
                                <td><label>
                                  <select name="dpdGrupo" class="caixaTotal" id="dpdGrupo">
                                  
                                <?php                                 
                                echo $grupoatual;
                                echo $gr->ListaGrupo();                            
                                ?>    
                                </select>
                                </label></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              
                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="right">
                                  <input name="cmdSalvar" type="submit" class="botao" id="cmdSalvar" value="<?php echo $botao;  ?>" onclick="submitForm('frmRegistro')/>
                                  </div></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><label>
                                  <div align="right">
                                </div></td>
                              </tr>
                              
                              <tr>
                                <td>&nbsp;</td>
                                <td>
                                  <div align="right">
                                    <label></label>
                                  </div>
                                  </label></td>
                              </tr>
                            </table></td>
                          </tr>
                        </table></td>
                  </tr>
                    <tr>
                        <td bgcolor="Silver" valign="middle" align="center" class="rodape">
                            Ponto Automatizado de Refeit&oacute;rio / IFMG - Campus Bambu&iacute; ­ 2008</td>
                    </tr>
                    <tr>
                        <td bgcolor="Silver" valign="middle" align="center" class="barra">
                            &nbsp;
                            <div align="center">
		                      <img src="imagens/postgres.gif" width="80" height="15">
		                      <img src="imagens/php.png" width="80" height="15">
		                      <img src="imagens/gti.gif" width="80" height="15">
		                    </div>
                            </td>
                    </tr>
                </table>
            </form>
            </td>
        </tr>
    </table>

</body>
</html>
