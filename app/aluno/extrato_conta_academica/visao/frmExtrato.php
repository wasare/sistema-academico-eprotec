<?php

//include_once('../../conexao.php');
	
//include_once('../../verifica.php');

include_once('../../topo.htm');

session_start();

//PEGAR AQUI A SESSÃO DO USUÁRIO
$codigo = @$_SESSION['user'];

//echo $codigo;
?>

<html>
<head>
	<title>Conta Academica</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/prototype.js" type="text/javascript"></script>
<br/>
</head>
<body>
<form id="frmAltera" name="frmAltera" method="post" action="frmExtrato_exec.php">
<input type="hidden" name="txtCodigo" id="txtCodigo" value="<?php echo $codigo; ?>"/>

<table width="416" height="130" border="0" cellpadding="0" cellspacing="0" align="left" >
  <tr>
    <td width="17" valign="top">&nbsp;</td>
    <td width="385" valign="middle" align="center">
    
    <table width="82%" border="0" cellspacing="0" cellpadding="0" class="tabelaDeTipos">
      <tr>
        <td width="71">&nbsp;</td>
        <td width="245">
		CONSULTANDO EXTRATO       </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td>Data Inicial:</td>
        <td><input type="text" name="txtDataInicial" id="txtDataInicial" class="caixaPequena" value="01-01-2000"/> 
          exemplo: dd-mm-aaaa</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Data Final</td>
        <td><input type="text" name="txtDataFinal" id="txtDataFinal" class="caixaPequena" value="01-01-2010"/> 
          exemplo: dd-mm-aaaa</td>
      </tr>
      
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
        <input id="cmdConsultar" type="submit" value="Consultar" class="botao" name="cmdConsultar" />       </td>
      </tr>
    </table></td>
    <td width="14" valign="top">&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
