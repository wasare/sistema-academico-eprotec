<?php
session_start();

require_once("../config.class.php");

$config = new clsConfig();
	
if ((isset($_SESSION['codigo'])))
{
	require_once("../modelo/clsOperador.class.php");
	require_once("../modelo/clsConfiguracoes.class.php");
	require_once("../modelo/clsRefeicao.class.php");

	$operador = new clsOperador();
	$operador->SelecionaPorCodigo(trim($_SESSION['codigo']));
	
	$pam = new clsConfiguracoes();
	$pam->Seleciona();
	
	$ref = new clsRefeicao();
	$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
	
	//se venda por unidade o foco trabalha de um jeito
	//se venda por peso o foco fica sempre na caixa de quantidade
	$tipofoco = 'document.frmVenda.usuario.focus();';
	if (trim($ref->GetUnidade()) == 'UN')
	{
		$tipofoco = 'document.frmVenda.peso.focus();';
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
	<link rel="shortcut icon" href="https://sistemas.cefetbambui.edu.br/prato/favicon_prato.ico">
    <title>&Aacute;rea de Venda - PRATO</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    
    <!-- ESTILO JANELA -->
    <link href="css/themes/default.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/spread.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/alert.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/alert_lite.css" rel="stylesheet" type="text/css" />	

  	<link href="css/themes/alphacube.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/debug.css" rel="stylesheet" type="text/css"/>
    
	<script src="js/prototype.js" type="text/javascript"></script>
    <script src="js/ajax/frmVenda_ajax.js" type="text/javascript"></script>
        
        <!-- JANELA -->
    <script type="text/javascript" src="js/janela/effects.js"> </script>
    <script type="text/javascript" src="js/janela/window.js"> </script>
    <script type="text/javascript" src="js/janela/window_effects.js"> </script>
    <script type="text/javascript" src="js/janela/debug.js"> </script>      
    
	<script type="text/javascript" src="js/mascara.js"> </script> 


    <style type="text/css">
<!--
.style22 {
	color: #FFFFFF;
	font-weight: bold;
}
.style25 {
	font-size: x-large;
	font-weight: bold;
	color: #FFFFFF;
}
.style26 {
	font-size: xx-large;
	font-weight: bold;
	color: #FF0000;
}
.style33 {font-size: medium}
-->
    </style>
   
</head>
<body onload="captura(); <?php echo $tipofoco; ?>">
 <form id="frmVenda" name="frmVenda" method="post" action="frmVenda_exec.php">  
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td align="center" valign="top" bgcolor="#0000FF" ><span class="style22">:: PRATO - Ponto de Refeit&oacute;rio Automatizado ::</span></td>
        </tr>
        <tr>
          <td align="center" valign="top" bgcolor="#3366FF" ><span class="style25">VENDA DE REFEI&Ccedil;&Atilde;O</span></td>
        </tr>
        <tr>
          <td align="center" valign="top" bgcolor="#0000FF" >&nbsp;</td>
        </tr>
        <tr>
          <td valign="top" align="center" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <th width="572" valign="top" scope="col"><table width="100%" height="392" border="0" cellpadding="0" cellspacing="0" background="imagens/fundovenda.jpg">
                <tr>
                  <th width="4%" scope="col">&nbsp;</th>
                  <th width="93%" scope="col">&nbsp;</th>
                  <th width="3%" scope="col">&nbsp;</th>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="center"><table width="63%" border="0" cellspacing="0" cellpadding="0">
				  	<!-- SPAN QUANTIDADE -->
                      
                    <tr>
                      <th colspan="2" scope="col">
Quantidade <span id="spanQuant"> </span></th>
                    </tr>
                    <tr>
					
                      <!-- fim SPAN QUANTIDADE -->
                      <td colspan="2">
                        <label>
                          <input name="txtMetodo" id="txtMetodo" type="hidden" value="" />
                          <input name="txtCodigo" id="txtCodigo" type="hidden" value="" />
                          <div align="center">
                            <input name="peso" type="text" class="style26" id="peso"  tabindex="2" 
                        
						onkeydown=
                        "
                        if(event.keyCode==13) 
                        {
                           document.frmVenda.usuario.focus(); 
                        }
                        
                        "
                        />
</div>
                        </label>                        </td>
                    </tr>
                    <tr>
                      <td width="12%">&nbsp;</td>
                      <td width="88%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">
                      
                      
                      <div align="center">Usu&aacute;rio:</div>
                     </td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="center">
                        <input name="usuario" type="text" class="style26" id="usuario" onkeypress="mascara(this,soNumeros)" tabindex="1" 
                            
							
						onkeydown=
                        "
                        if(event.keyCode==13) 
                        {
                            if ($('peso').value != $('usuario').value)
                            {
                                $('txtMetodo').value = 'registravenda';
                                $('txtCodigo').value = <?php echo $operador->GetCodigo(); ?>;
                                $('frmVenda').submit();
                            }
                            else
                            {
                                document.frmVenda.usuario.value = '';
                                document.frmVenda.usuario.focus();
                            }
                        }
                        
                        "
                            />
                      </div>                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right">
                        <input name="cmdConfirmar" type="button" class="botaoPrincipal" id="cmdConfirmar" value="Confirmar Venda"  tabindex="3"  onclick="if ($('peso').value != $('usuario').value)
                            {
                                $('txtMetodo').value = 'registravenda';
                                $('txtCodigo').value = <?php echo $operador->GetCodigo(); ?>;
                                $('frmVenda').submit();
                            }
                            else
                            {
                                document.frmVenda.peso.value = '';
                                document.frmVenda.peso.focus();
                            }" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="center">
                      
                      
                      <!-- SPAN FORMULARIO 
                      <span id="spanUsuario">
                        <table width="83%" border="0" cellpadding="0" cellspacing="0" class="rodape">
                          <tr>
                            <th scope="col"><div align="left" class="rodape style33">Nome:</div></th>
                            <th scope="col"><div align="left" class="rodape style33">-- Selecione --</div></th>
                          </tr>
                          <tr>
                            <td><div align="left" class="rodape style33">Saldo:</div></td>
                            <td><div align="left" class="rodape style33">R$ 0,00</div></td>
                          </tr>
                          <tr>
                            <td><div align="left" class="rodape style33">Grupo:</div></td>
                            <td><div align="left" class="rodape style33">-- Selecione --</div></td>
                          </tr>
                          
                          <tr>
                            <td><div align="left" class="rodape style33">Limite:</div></td>
                            <td><div align="left" class="rodape style33">R$ 0,00</div></td>
                          </tr>
                        </table>
                        </span>
                         FIM SPAN FORMULARIO -->
                        
                        
                      </div>
					  <span id="peso_erro_msg"></span>
					 
					  </td>
                    </tr>
                    
                  </table>
                    <div align="center"></div>
                  <div align="center"></div></td>
                  <td>&nbsp;</td>
                </tr>
                
                
                <tr>
                  <td>&nbsp;</td>
                  <td> <?php
					  if ($pam->GetRegistroAut() == 't')
						{
							echo 'REGISTRO AUTOMÁTICO HABILITADO';
						}
						?></td>
                  <td>&nbsp;</td>
                </tr>
              </table></th>
              <th width="196" scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                <tr>
                  <th bgcolor="#00CCFF" scope="col"><span class="rodape"><strong>:: VALE ::</strong></span></th>
                </tr>
                <tr>
                  <th scope="col"><table width="100%" height="100px" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC">
                    <tr>
                      <th width="34%" scope="col"><div align="left"><span class="rodape">&nbsp;&nbsp;&nbsp;Dispon&iacute;veis:</span></div></th>
                      <th width="66%" scope="col"><div align="left"><?php echo $pam->GetNumVale();?></div></th>
                    </tr>
                    <tr>
                      <td><div align="left"><span class="rodape">&nbsp;&nbsp;&nbsp;Habilitado:</span></div></td>
                      <td><div align="left">
					  <?php 
					  
					  if (trim($pam->GetPermiteVale()) == 't')
					  {
					  	echo 'SIM';
					  }
					  else
					  {
					  	echo 'NÂO';
					  }
					  
					  ?></div></td>
                    </tr>
                    
                    <tr>
                      <td colspan="2"><div align="right">
                        <input name="cmdVale" type="button" class="botaoHist" id="cmdVale" value="Vender com Vale"/ onclick="Dialog.confirm('<center><b>Confirmar venda utilizando VALE REFEIÇÃO?</b></center>', {width:300, okLabel: 'Sim', cancelLabel:'N&atilde;o', buttonClass: 'myButtonClass', id: 'frmMessagem', cancel:function(win) {debug('cancel confirm panel')}, ok:function(win) {$('txtMetodo').value = 'vendacomvale';
                                $('txtCodigo').value = <?php echo $operador->GetCodigo(); ?>;
                                $('frmVenda').submit(); return true;} });">&nbsp;
                      </div></td>
                    </tr>
                    
                  </table></th>
                </tr>
                <tr>
                  <td bgcolor="#00CCFF"><div align="center" class="rodape"><strong>:: CORTESIA ::</strong></div></td>
                </tr>
                <tr>
                  <td><table width="100%" height="100px" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC">
                    <tr>
                      <th width="34%" scope="col"><div align="left"><span class="rodape">&nbsp;&nbsp;&nbsp;Dispon&iacute;veis:</span></div></th>
                      <th width="66%" scope="col"><div align="left"><?php echo $pam->GetNumCortesia();?></div></th>
                    </tr>
                    <tr>
                      <td><div align="left"><span class="rodape">&nbsp;&nbsp;&nbsp;Habilitado:</span></div></td>
                      <td><div align="left">
                      <?php
                      if (trim($pam->GetPermiteCortesia()) == 't')
					  {
					  	echo 'SIM';
					  }
					  else
					  {
					  	echo 'NÂO';
					  }
					  ?>
                      </div></td>
                    </tr>
                    
                     <tr>
                      <td colspan="2"><div align="right">
                        <input name="cmdCortesia" type="button" class="botaoHist" id="cmdCortesia" value="Efetuar Cortesia" tabindex="5" onclick="Dialog.confirm('<center><b>Confirmar um refeição por CORTESIA?</b></center>', {width:300, okLabel: 'Sim', cancelLabel:'N&atilde;o', buttonClass: 'myButtonClass', id: 'frmMessagem', cancel:function(win) {debug('cancel confirm panel')}, ok:function(win) {$('txtMetodo').value = 'efetuacortesia';
                                $('txtCodigo').value = <?php echo $operador->GetCodigo(); ?>;
                                $('frmVenda').submit(); return true;} });"/>&nbsp;
                      </div></td>
                    </tr>
                    
                  </table></td>
                </tr>
                <tr>
                  <td bgcolor="#00CCFF"><div align="center" class="rodape"><strong>:: REFEI&Ccedil;&Atilde;O ::</strong></div></td>
                </tr>
                <tr>
                  <td><table width="100%" height="60px" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC">
                    <tr>
                      <th colspan="2" scope="col">
                        <label>
                          <select name="dpdRefeicao" class="caixaDrop" id="dpdRefeicao" onchange="CarregaDadosRefeicao()">
                            
                            <?php 
							echo '<option value="'.$ref->GetCodigo().'">'.$ref->GetDescricao().'</option>';
							echo $ref->ListaRefeicao();						
							?>
                          </select>
                          </label>
                    
                      </th>
                    </tr>
                    <tr>
                      <td width="34%"><div align="left"><span class="rodape">&nbsp;&nbsp;&nbsp;Custo:</span></div></td>
                      <td width="66%">
                      
                      <!-- SPAN CUSTO -->
                      <span id="spanCusto">
                      <div align="left">R$ 
					  <?php 
					  echo number_format($ref->GetCusto(), 2, ',', '');
					  ?>
                      </div>
                      </span>
                      <!-- fim SPAN CUSTO -->
                      
                      </td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td bgcolor="#00CCFF"><div align="center"><span class="rodape"><strong>:: OPERADOR ::</strong></span></div></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFCC"><div align="center" class="style26"><?php echo $operador->GetNome(); ?></div>
                    &nbsp;<a href="frmInicial.php">Sair do Sistema</a></td>
                </tr>
                <tr>
                  <td></td>
                </tr>
              </table></th>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td valign="top" align="center" ></td>
        </tr>
        <tr>
            <td align="center" valign="top" bgcolor="#0000FF" ><span class="style22">Ponto de Refeit&oacute;rio Automatizado - PRATO / IFMG - Campus Bambu&iacute; 2008</span></td>
        </tr>
    </table>
</form>
</body>
</html>

