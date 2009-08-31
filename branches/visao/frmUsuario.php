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
<title>Controle de Usuários - PRATO</title>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />

<!-- ESTILO DATAGRID-->
<link href="estilo/datagrid/dhtmlxgrid.css"  rel="stylesheet" type="text/css" />
<link href="estilo/datagrid/dhtmlxgrid_skins.css" rel="stylesheet" type="text/css"  />
<link href="estilo/datagrid/style.css" rel="stylesheet" type="text/css" />

<!-- ESTILO JANELA -->
<link href="css/themes/default.css" rel="stylesheet" type="text/css" />	
<link href="css/themes/spread.css" rel="stylesheet" type="text/css" />	
<link href="css/themes/alert.css" rel="stylesheet" type="text/css" />	
<link href="css/themes/alert_lite.css" rel="stylesheet" type="text/css" />	

<link href="css/themes/alphacube.css" rel="stylesheet" type="text/css" />	
<link href="css/themes/debug.css" rel="stylesheet" type="text/css"/>
    
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
  	
  	<!-- DATAGRID -->
<script  type="text/javascript" src="js/datagrid/dhtmlxcommon.js"></script>
<script  type="text/javascript" src="js/datagrid/dhtmlxgrid.js"></script>		
<script  type="text/javascript" src="js/datagrid/dhtmlxgridcell.js"></script>
<script  type="text/javascript" src="js/datagrid/dhtmlxgrid_pgn.js"></script>
<script  type="text/javascript" src="js/datagrid/dhtmlxgrid_drag.js"></script>
    
<!--COMPONENTES AJAX DA PAGINA -->
    <script src="js/ajax/frmUsuario_ajax.js" type="text/javascript"></script>
</head>
 
<body>
<script type="text/javascript"> 
function janelaHelp(textoHTML)
{
  var win = new Window({id: "janelaHelp", className: "alphacube", title: "Ajuda do PRATO", width:300, height:180}); 
  win.getContent().innerHTML = textoHTML;
  win.setDestroyOnClose(); 
  win.showCenter();
  win.setConstraint(true, {left:0, right:0, top: 30, bottom:10})
  win.toFront();
}
</script>
    <table align="center" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td valign="top">
            <form id="frmUsuario" name="frmUsuario" method="post" action="frmUsuario_exec.php" onsubmit="return false">
				<input type="hidden" name="txtMetodo" id="txtMetodo" value=""/>
				<input type="hidden" name="txtCodigo" id="txtCodigo" value=""/>
                <table width="780px" border="0" align="center" cellpadding="0" cellspacing="1">
<tr>
                        <td>
                            <img alt="" src="imagens/banner.jpg" width="100%"/></td>
                    </tr>
                    <tr>
                        <td class="barra">
                            <table cellpadding="0" cellspacing="0" class="style4">
                                <tr>
                                    <td class="style7">
                                        &nbsp;&nbsp; Seja Bem Vindo <?php echo $admin->GetNome(); ?>!&nbsp;</td>
                                    
                                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="25%"><img src="imagens/back.gif" alt="voltar" width="8" height="30" style="width: 17px; height: 16px" /></td>
                                        <td width="75%">&nbsp;<a href="frmAdmin.php"><b>VOLTAR</b></a></td>
                                      </tr>
                                    </table></td><td >&nbsp;&nbsp;&nbsp;</td>
                              <td valign="middle">
                                        <table cellpadding="0" cellspacing="0" class="style5">
                                            <tr>
                                                <td class="style6">
                                        <img alt="" src="imagens/bt_logout.jpg" style="margin-top: 0px" /></td>
                                                <td>
                                                    &nbsp;<b><a href="<?php echo $config->GetPaginaPrincipal() ?>">SAIR</a></b></td>
                                            </tr>
                                        </table>                                    </td><td >&nbsp;&nbsp;&nbsp;</td>
                                </tr>
                            </table>                            </td>
                    </tr>
                    <tr>
                    
                    </tr>
                    <tr>
                        <td>
                        
                        
                        
                                
                                <!-- TABELA GRID-->
                                <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td  height="36"><b>&nbsp;&nbsp;&nbsp;USU&Aacute;RIOS DO PRATO</b></td>
                                  </tr>
                                  <tr>
                                    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td height="178">
                                        
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="3%">&nbsp;</td>
                                            <td width="94%">
 <input type="Text" id="txtFiltro" name="txtFiltro">
			<input name="btnFiltrar" type="Button" id="btnFiltrar" onclick="Filtrar()" value="Filtrar Usuario">
										
														<div id="grid" height="400px" style="background-color:white;overflow:hidden"></div>                                            </td>
                                            <td width="3%">&nbsp;</td>
                                          </tr>
                                        </table>                                        
                                        </td>
                                      </tr>
                                      <tr>
                                        <td height="33" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td bordercolor="1">
                                        <table width="298" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="260">
                                              
                                            </td>
                                            <td width="38">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                          </tr>
                                        </table></td>
                                            <td id="recinfoArea"></td>
                                            <td id="pagingArea"></td>
                                          </tr>
                                        </table></td>
                                      </tr>
                                    </table></td>
                                  </tr>
                                </table>
                                <!-- FIM TABELA COMPROVACAO -->
                                 <script>
									grid = new dhtmlXGridObject('grid');
									grid.setImagePath("imagens/datagrid/");
									grid.setHeader("Código, Nome, Grupo, Saldo, Acesso, Registro");
									grid.setInitWidths("100,*, 90, 70, 100, 100");
									grid.setColAlign("center, right, center, center, center, center");
									grid.setColTypes("ro,ro,ro,ro,ro,ro");
									grid.setColSorting("str,str,str,str,str,str");
									grid.enablePaging(true,30,10,"pagingArea",true,"recinfoArea");
									grid.enableKeyboardSupport(true);											
									grid.enableDragAndDrop("false");
									grid.init();
									grid.setSkin("light");
									grid.loadXML("frmUsuario_exec.php?metodo=carregagrid&codigo=");
								</script>
                        <script>
						
						function Filtrar()
						{
							var valor;
							valor = document.getElementById("txtFiltro").value;
							grid.init();
							grid.loadXML("frmUsuario_exec.php?metodo=filtrar&valor="+valor+"");
						
						}
								
																													
										</script>
                        
                        
                        </td>
                    </tr>
                    
                    <tr>
                        <td>&nbsp;                            </td>
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
		                      <img src="imagens/gti.gif" width="80" height="15">		                    </div>                            </td>
                    </tr>
                </table>
                </form>
          </td>
        </tr>
    </table>

</body>
</html>

