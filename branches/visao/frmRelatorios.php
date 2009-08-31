<?php
session_start();

require_once("../config.class.php");
require_once("../modelo/clsOperador.class.php");
$config = new clsConfig();
	
if ((isset($_SESSION['codigo'])))
{
	$admin = new clsOperador();
	$admin->SelecionaPorCodigo(trim($_SESSION['codigo']));
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
    <title>Relatorios do PRATO</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    
    <!-- ESTILO JANELA -->
    <link href="css/themes/default.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/spread.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/alert.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/alert_lite.css" rel="stylesheet" type="text/css" />	

  	<link href="css/themes/alphacube.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/debug.css" rel="stylesheet" type="text/css"/>

    <script src="js/prototype.js" type="text/javascript"></script>
    
    <!-- JANELA -->
    <script type="text/javascript" src="js/janela/effects.js"> </script>
  	<script type="text/javascript" src="js/janela/window.js"> </script>
  	<script type="text/javascript" src="js/janela/window_effects.js"> </script>
  	<script type="text/javascript" src="js/janela/debug.js"> </script>  
    
  	<!--COMPONENTES AJAX DA PAGINA -->
    <script src="js/ajax/frmDocente_ajax.js" type="text/javascript"></script>
    <script src="js/ajax/frmAtividade_ajax.js" type="text/javascript"></script>
	
	<script src="js/menu_direito/milonic_src.js" type="text/javascript"></script>
    <script src="js/menu_direito/mmenudom.js" type="text/javascript"></script>
    <script src="js/menu_direito/dados/mn_dados.js" type="text/javascript"></script>
    <script src="js/menu_direito/contextmenu.js" type="text/javascript"></script>
</head>
<body>
		
    <table align="center" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td valign="top">
                <form id="frmAtividade" name="frmAtividade" method="post" action="frmAtividade_exec.php">
                <table align="center" cellpadding="0" cellspacing="1" width="780px">
                    <tr>
                        <td>
                            <img alt="" src="imagens/banner.jpg" />
                              <input type="hidden" name="txtMetodo" id="txtMetodo" value=""/>
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
                        <td><div align="center">
                          <p><strong>:: RELAT&Oacute;RIOS ::</strong></p>
						  <p><a href="frmMinhasConsultas.php" class="rodape">Minhas Consultas</a></p>
                          <p><a href="frmRelatorios_exec.php?codrel=saldos" class="rodape">Listagem de Saldos</a>						  </p>
						  <p><a href="frmRelatorios_exec.php?codrel=bolsistas" class="rodape">Relação de Bolsistas</a>						  </p>
                          <p><a href="frmHistorico.php" class="rodape">Hist&oacute;rico de Alimenta&ccedil;&otilde;es</a></p>
                          <p>
                         <input id="cmdSomatorio" type="button" value="Somatórios" class="botaoHistSemBorda" name="cmdSomatorio"
        onclick="Dialog.alert({url: 'frmSomatorio.php', options: {method: 'get'}}, {className: 'alphacube', width:450, okLabel: 'Voltar'});" />
                          </p>
                          <p>&nbsp;</p>
                        </div></td>
                  </tr>
                    <tr>
                        <td bgcolor="Silver" valign="middle" align="center" class="rodape">
                            Ponto de Refeit&oacute;rio Automatizado / IFMG - Campus Bambu&iacute; ­ 2008</td>
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