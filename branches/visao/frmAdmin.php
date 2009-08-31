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
	<link rel="shortcut icon" href="https://sistemas.cefetbambui.edu.br/prato/favicon_prato.ico">
    <title>&Aacute;rea Administrativa - PRATO</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/geral.js" type="text/javascript"></script>
    
    <script src="js/menu_direito/milonic_src.js" type="text/javascript"></script>
    <script src="js/menu_direito/mmenudom.js" type="text/javascript"></script>
    <script src="js/menu_direito/dados/mn_dados.js" type="text/javascript"></script>
    <script src="js/menu_direito/contextmenu.js" type="text/javascript"></script>
</head>
<body>
   
    <table align="center" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td valign="top">
                <table align="center" cellpadding="0" cellspacing="1" width="780px">
                    <tr>
                        <td>
                            <img alt="" src="imagens/banner.jpg" /></td>
                    </tr>
                    <tr>
                        <td class="barra">
                            <table cellpadding="0" cellspacing="0" class="style4">
                                <tr>
                                    <td class="style7">
                                        &nbsp;&nbsp; Seja Bem Vindo Administrador &nbsp;<?php echo $admin->GetNome(); ?>!&nbsp;</td>
                                    <td >
                                     
                                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                         <td>&nbsp;</td>
                                         <td>&nbsp;<a href="frmAdmin.php"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></a></td>
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
                        <td>
                            <br />
                            <table cellpadding="0" cellspacing="0" class="style8" align="center">
                                <tr>
                                    <td align="center">
                                         <img alt="" src="imagens/bt_usuario.jpg" id="bt_usuarios" onmouseover="CarregaToolTip('bt_usuarios')" onmouseout="CarregaToolTip('')" /><br />
                                        <a href="frmUsuario.php" onmouseover="CarregaToolTip('bt_usuarios')" onmouseout="CarregaToolTip('')">Usu&aacute;rios</a></td>
                                    <td align="center">
                                         <p><img alt="" src="imagens/bt_configuracoes.jpg" id="bt_configuracoes" onmouseover="CarregaToolTip('bt_configuracoes')" onmouseout="CarregaToolTip('')"/><br />
                                           <a href="frmConfiguracoes.php" onmouseover="CarregaToolTip('bt_configuracoes')" onmouseout="CarregaToolTip('')">Configura&ccedil;&otilde;es</a></p>                                        </td>
                              <td align="center">
                                        <img alt="" src="imagens/bt_grupos.jpg" id="bt_grupos" onmouseover="CarregaToolTip('bt_grupos')" onmouseout="CarregaToolTip('')"/><br />
                                        <a href="frmGrupo.php" onmouseover="CarregaToolTip('bt_grupos')" onmouseout="CarregaToolTip('')"> Grupos</a></td>
                                <td align="center"><img alt="" src="imagens/bt_minhasconsultas.jpg" id="bt_minhasconsultas" onmouseover="CarregaToolTip('bt_minhasconsultas')" onmouseout="CarregaToolTip('')"/><br />
                                        <a href="frmMinhasConsultas.php" onmouseover="CarregaToolTip('bt_minhasconsultas')" onmouseout="CarregaToolTip('')"> Minhas Consultas</a></td>
                                </tr>
                                <tr>
                                    <td align="center">
                                         <img alt="" src="imagens/bt_relatorios.jpg" id="bt_relatorios" onmouseover="CarregaToolTip('bt_relatorios')" onmouseout="CarregaToolTip('')"/><br />
                                        <a href="frmRelatorios.php" onmouseover="CarregaToolTip('bt_relatorios')" onmouseout="CarregaToolTip('')">Relat&oacute;rios</a></td>
                                    <td align="center">
                                        <img alt="" src="imagens/bt_operadores.jpg" id="bt_operadores" onmouseover="CarregaToolTip('bt_operadores')" onmouseout="CarregaToolTip('')"/><br />
                                        <a href="frmOperador.php" onmouseover="CarregaToolTip('bt_operadores')" onmouseout="CarregaToolTip('')"> Operadores</a></td>
                                    <td align="center">
                                    
                                        <img src="imagens/bt_refeicao.jpg" alt="" name="bt_refeicao" id="bt_refeicao" onmouseover="CarregaToolTip('bt_refeicao')" onmouseout="CarregaToolTip('')"/><br />
                                        <a href="frmRefeicao.php" onmouseover="CarregaToolTip('bt_refeicao')" onmouseout="CarregaToolTip('')">Refei&ccedil;&otilde;es</a></td>
                                    <td align="center"><p><img alt="" src="imagens/bt_marmitex.jpg" id="bt_marmitex" onmouseover="CarregaToolTip('bt_marmitex')" onmouseout="CarregaToolTip('')"/><br />
                                      <a href="frmMarmitex.php" onmouseover="CarregaToolTip('bt_marmitex')" onmouseout="CarregaToolTip('')"> Marmitex </a></p>
                                      </td>
                              </tr>
                            </table>
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="style9" align="center">
                                <tr>
                                    <td>                                       
                                      <textarea id="txtInfo" class="semBorda">Clique sobre uma das op&ccedil;&otilde;es acima para acessar as &aacute;reas de configura&ccedil;&otilde;es.</textarea></td>
                                </tr>
                            </table>
                            </td>
                        
                    </tr>
                    <tr>
                        <td>&nbsp;
                            </td>
                    </tr>
                    <tr>
                        <td bgcolor="Silver" valign="middle" align="center" class="rodape">
                            Ponto Automatizado de Refeit&oacute;rio - PRATO / IFMG - Campus Bambu&iacute; ­ 2008</td>
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
            </td>
        </tr>
    </table>

</body>
</html>

