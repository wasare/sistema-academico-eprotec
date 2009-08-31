<?php
session_start();

require_once("../config.class.php");
require_once("../modelo/clsConfiguracoes.class.php");
require_once("../modelo/clsOperador.class.php");

$config = new clsConfig();
	
if ((isset($_SESSION['codigo'])))
{
	$admin = new clsOperador();
	$admin->SelecionaPorCodigo(trim($_SESSION['codigo']));
	
	$par = new clsConfiguracoes();
	$par->Seleciona();
	
	$registroaut = '';	
	if (trim($par->GetRegistroAut()) == 't')
	{
		$registroaut = 'checked="checked"';	
	}
	
	$repetebolsista = '';	
	if (trim($par->GetRepeteBolsista()) == 't')
	{
		$repetebolsista = 'checked="checked"';	
	}
	
	$permitevale = '<option value="t">Habilitado</option>';	
	if (trim($par->GetPermiteVale()) == 'f')
	{
		$permitevale = '<option value="f">Bloqueado</option>';	
	}
	
	$numvale = $par->GetNumVale();
	$precovale = $par->GetPrecoVale();
	
	$permitecortesia = '<option value="t">Habilitado</option>';	
	if (trim($par->GetPermiteCortesia()) == 'f')
	{
		$permitecortesia = '<option value="f">Bloqueado</option>';	
	}
	
	$numcortesia = $par->GetNumCortesia();
	
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
    <title>Controle de Acesso - PRATO</title>
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
.style22 {color: #FF0000}
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
                <form id="frmConfiguracoes" name="frmConfiguracoes" method="post" action="frmConfiguracoes_exec.php">
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
                                         <td>&nbsp;<a href="frmAdmin.php"><b>VOLTAR</b></a></td>
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
                                <td colspan="2"><div align="center"><strong>CONFIGURA&Ccedil;&Otilde;ES DO  PRATO</strong></div></td>
                              </tr>
                              <tr>
                                <td width="34%">&nbsp;</td>
                                <td width="66%">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2"><p align="justify">&nbsp;                                                </p>
                                  <p align="justify">
                                    <input name="chkRegistroAut" type="checkbox" id="chkRegistroAut" <?php echo $registroaut; ?>/>
                                    <strong>REGISTRO AUTOM&Aacute;TICO</strong></p>
                                  <div>
                                    <div align="justify">Habilitando esta opção o PRATO no ato da alimenta&ccedil;&atilde;o registra o usu&aacute;rio automaticamente. Com base no número da carteirinha,  ele pesquisa no SAGU se o usuário é aluno ou funcion&aacute;rio, agrupando-o. Para que o usu&aacute;rio seja registrado dessa forma ele precisa ter saldo na conta acad&ecirc;mica.</div>
                                  </div>                                  <p align="justify">
                                    <input name="chkBolsista" type="checkbox" id="chkBolsista" <?php echo $repetebolsista; ?>/>
                                    <strong>HABILITAR/DESABILITAR CONTROLE DE BOLSISTAS</strong></p>
                                  <div>
                                    <div align="justify">Habilitando esta op&ccedil;&atilde;o o PRATO no ato da alimenta&ccedil;&atilde;o verifica se um aluno que est&aacute; dentro dos grupos de bolsistas est&aacute; se alimentando repetidas vezes por refei&ccedil;&atilde;o. Com esta op&ccedil;&atilde;o habilitada o usu&aacute;rio bolsista s&oacute; pode almo&ccedil;ar uma &uacute;nica vez por refei&ccedil;&atilde;o. </div>
                                  </div>
                                  <p align="justify">&nbsp;</p>
                                  <p align="justify">&nbsp;</p></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>Permitir venda por <strong>vale</strong>:</td>
                                <td><select name="dpdPermiteVale" class="caixaTotal" id="dpdPermiteVale">
                                	<?php                                 
                                		echo $permitevale;                            
                                	?>
                                  <option value="t">Habilitado</option>
                                  <option value="f">Bloqueado</option>
                                    
                                </select></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="45">Vales dispon&iacute;veis:</td>
                              <td><label>
                                <input name="txtNumVale" type="text" class="caixaTotal" id="txtNumVale" value="<?php echo $numvale;?>"/>
                              </label></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>Pre&ccedil;o do Vale: </td>
                                <td><input name="txtPrecoVale" type="text" class="caixaTotal" id="txtPrecoVale" value="<?php echo $precovale;?>"/></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>Permitir venda por <strong>cortesia</strong>:</td>
                                <td><select name="dpdPermiteCortesia" class="caixaTotal" id="dpdPermiteCortesia">
                                  <?php                                 
                                		echo $permitecortesia;                            
                                	?>
                                  <option value="t">Habilitado</option>
                                  <option value="f">Bloqueado</option>
                                </select></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td height="45">Cortesias dispon&iacute;veis:</td>
                              <td><label>
                                <input name="txtNumCortesia" type="text" class="caixaTotal" id="txtNumCortesia" value="<?php echo $numcortesia;?>"/>
                              </label></td>
                              </tr>
                              

                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="right">
                                  <input name="cmdSalvar" type="submit" class="botao" id="cmdSalvar" value="Salvar" onclick="submitForm('frmRegistro')/>
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
