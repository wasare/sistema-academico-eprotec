<?php
session_start();

require_once("../config.class.php");
require_once("../modelo/clsOperador.class.php");
require_once("../modelo/clsGrupo.class.php");
require_once("../modelo/clsDesconto.class.php");
$config = new clsConfig();
	
if ((isset($_SESSION['codigo'])))
{
	$admin = new clsOperador();
	$admin->SelecionaPorCodigo(trim($_SESSION['codigo']));
	
	$grupo = new clsGrupo();	
	$codgrupo = $_GET['codgrupo'];
	$grupo->PegaGrupoPorCodigo($codgrupo);
	
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
    <title>&Aacute;rea Administrativa - PRATO</title>
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
    <script src="js/ajax/frmGrupo_ajax.js" type="text/javascript"></script>
    <style type="text/css">
<!--
.style22 {color: #FF0000}
-->
    </style>
</head>
 
<body>
   
    <table align="center" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td valign="top">
            <form id="frmDesconto" name="frmDesconto" method="post" action="frmDesconto_exec.php">
				<input type="hidden" name="txtMetodo" id="txtMetodo" value=""/>
				<input type="hidden" name="txtCodigo" id="txtCodigo" value="<?php echo $grupo->GetCodigo();?>"/>
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
                                        <td width="75%">&nbsp;<a href="frmGrupo.php"><b>VOLTAR</b></a></td>
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
                        
                        
                        
                                
<br />
                                <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                  <tr>
                                    <td  height="36"><p><b>&nbsp;&nbsp;&nbsp;DESCONTOS PARA GRUPOS EM REFEI&Ccedil;&Otilde;ES</b></p>
                                    <p>&nbsp;&nbsp;&nbsp;<b>GRUPO:</b> <?php echo $grupo->GetDescricao();?> <br /> &nbsp;&nbsp;&nbsp;<b>LIMITE:</b> <?php echo $grupo->GetLimite();?></p></td>
                                  </tr>
                                  <tr>
                                    <td  height="36"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <th width="30%" bgcolor="#99CCFF" scope="col"><div align="left">REFEI&Ccedil;&Atilde;O</div></th>
                                        <th width="70%" bgcolor="#99CCFF" scope="col"><div align="left">DESCONTOS (%)</div></th>
                                      </tr>
                                      <tr>
                                        <th width="30%" scope="col"><div align="left">&nbsp;</div></th>
                                        <th width="70%" scope="col"><div align="left">&nbsp;</div></th>
                                      </tr>
                                      
                                      <?php
									  	$ref = new clsDesconto();
										echo $ref->ListaRefeicaoDesconto($codgrupo);									  
									  ?>
                                      
                                      <tr>
                                        <th width="30%" scope="col"><div align="left">&nbsp;</div></th>
                                        <th width="70%" scope="col"><div align="left">&nbsp;</div></th>
                                      </tr>
                                      <tr>
                                        <th width="30%" bgcolor="#99CCFF" scope="col"><div align="left"></div></th>
                                        <th width="70%" bgcolor="#99CCFF" scope="col">&nbsp;</th>
                                      </tr>
                                      <tr>
                                        <th width="30%" scope="col"><div align="left">&nbsp;</div></th>
                                        <th width="70%" scope="col"><div align="left">&nbsp;</div></th>
                                      </tr>
                                      <tr>
                                        <th width="30%" scope="col"><div align="left">&nbsp;
                                          <label>
                                          <input name="txtSalvar" type="submit" class="botao" id="txtSalvar" value="Salvar" />
                                          </label>
                                        </div></th>
                                        <th width="70%" scope="col"><div align="left">&nbsp;</div></th>
                                      </tr>
                                    </table>                                    
                                    <p>&nbsp;</p></td>
                                  </tr>
                                  
                                  
                                </table>

                                 
                        
                        
                        
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

