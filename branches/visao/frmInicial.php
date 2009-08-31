<?php
	session_start();
	$nav = trim($_SESSION['nav']);
	
	require_once("../controle/gtiBrowser.class.php");
	$browser = new gtiBrowser();
	$arr = $browser->getBrowser();
	$navegador = $arr['nav'];
	$versao = $arr['ver'];
						
	if (($nav == '1') || (trim($navegador) == 'FIREFOX'))
	{
		require_once("../config.class.php");
		$config = new clsConfig();
		$config->Logout(false);
	}
	else 
	{
		header('location:frmFirefox.php');
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link rel="shortcut icon" href="https://sistemas.cefetbambui.edu.br/prato/favicon_prato.ico">
    <title>:: PRATO ::</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
     <script src="js/validator.js" type="text/javascript"></script>
     <style type="text/css">
<!--
.style22 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
     </style>
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
                    <td class="barra">
                            <table cellpadding="0" cellspacing="0" class="style4">
                                <tr>
                                    <td class="style7">
                                        &nbsp;&nbsp; Seja Bem Vindo! Este &eacute; o PRATO, por favor, efetue seu login para iniciar a sess&atilde;o!</td>
                                        <td align="right">&nbsp;<img alt="ajuda" src="imagens/bt_ajuda.jpg" style="margin-top: 0px" /></td>
                                        <td>&nbsp;<a href="ajuda/frmAjuda.html" target="_blank"><b>AJUDA</b></a></td>
                                </tr>
                            </table>
                            </td>
                    <tr>
                        <td class="style2">
                            </td>
                    </tr>
                    <tr>
                        <td background="imagens/prato_fundo.jpg">
                          
						  
						  
						  
						  <form id="frmInicial" name="frmInicial" method="post" action="frmInicial_exec.php" onsubmit="return v.exec()">
                            <table align="center" cellpadding="0" cellspacing="0" class="telaLogin" >
                                <tr>
                                  <td>
                                        <table cellpadding="0" cellspacing="0" class="formLogin" align="center">
                                            
                                            <tr>
                                                <td class="style3">
                                                    Login:</td>
                                                <td style="text-align: left">
                                                    <input id="txtLogin" name="txtLogin" type="text" class="caixaGrande" maxlength="20" value=""/></td>
                                            </tr>
                                            <tr>
                                                <td class="style3">
                                                    Senha:</td>
                                                <td style="text-align: left">
                                                    <input id="txtSenha" name="txtSenha" type="password" class="caixaGrande" maxlength="20" value=""/></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;                                                    </td>
                                                <td>&nbsp;                                                    </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;                                                    </td>
                                                <td style="text-align: right">
                                                    <input id="cmdConfirmar" name="cmdConfirmar" type="submit" value="Confirmar" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                        </table>
                                  </td>
                                </tr>
                            </table>
                          </form>
						  
						  
						  
						  
						  
                        </td>
                  </tr>
                    <tr>
                        <td class="style1">
                            </td>
                    </tr>
                    <tr>
                        <td align="center" valign="middle" bgcolor="#003399" class="rodape"><span class="style22"> Ponto de Refeit&oacute;rio Automatizado- PRATO / Instituto Federal Minas Gerais - Campus Bambu&iacute;</span></td>
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
<script language="javascript">

var campos =
{
    'txtLogin': {'l':'Login','r':true,'mn':1,'mx':20,'t':'txtLogin'},
    'txtSenha': {'l':'Senha','r':true,'mn':1,'mx':20,'t':'txtSenha'},
}

var v = new validator('frmInicial', campos);

</script>
</html>
