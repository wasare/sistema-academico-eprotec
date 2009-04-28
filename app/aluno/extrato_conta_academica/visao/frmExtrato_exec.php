<?php

$codusuario = $_POST['txtCodigo'];
$datainicial = $_POST['txtDataInicial'];
$datafinal = $_POST['txtDataFinal'];

require_once("../controle/gtiValida.class.php");

$valida = new gtiValidacao();
$valida->ValidaComparacaoData($datainicial, $datafinal, '<');

if ($valida->GetErro() == true)
{
	echo $valida->GetMensagem();
	exit;
}
		
require_once("../modelo/clsUsuario.class.php");

$usu = new clsUsuario();
$usu->PegaUsuario($codusuario);
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EXTRATO - CONTA ACADEMICA</title>
<style type="text/css">
<!--
.style2 {font-size: small}
.style4 {font-size: small; font-weight: bold; }
-->
</style>
</head>

<body>
<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th colspan="2" scope="col"><form id="form1" name="form1" method="post" action="">
      <label>
        <input type="image" name="logocefet" id="logocefet" src="imagens/logocefetpeq.jpg" />
        </label>
    </form>    </th>
  </tr>
  <tr>
    <td colspan="2">-------------------------------------------------</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><strong>EXTRATO ACAD&Ecirc;MICO</strong></div></td>
  </tr>
  <tr>
    <td width="84">&nbsp;</td>
    <td width="216">&nbsp;</td>
  </tr>
  
  <tr>
    <td><span class="style4">Data:</span></td>
    <td><span class="style2"><?php echo date("d/m/Y"); ?></span></td>
  </tr>
  <tr>
    <td><span class="style4">Hora:</span></td>
    <td><span class="style2"><?php echo date("H:i:s"); ?></span></td>
  </tr>
  
  <tr>
    <td><span class="style4">Usu&aacute;rio:</span></td>
    <td><span class="style2"><?php echo $usu->GetNome(); ?></span></td>
  </tr>
  <tr>
    <td class="style4">Per&iacute;odo:</td>
    <td><span class="style2"><?php echo $datainicial . ' at&eacute; ' . $datafinal; ?></span></td>
  </tr>
  
  <tr>
    <td><span class="style4 style2">Saldo:</span></td>
    <td><span class="style2">R$ <?php echo number_format($usu->GetSaldo(), 2, ',', ''); ?></span></td>
  </tr>
  <tr>
    <td colspan="2">-------------------------------------------------</td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th class="style4" scope="col"><div align="left">data/hora</div></th>
        <th class="style4" scope="col"><div align="left">opera&ccedil;&atilde;o</div></th>
        <th class="style4" scope="col"><div align="left">tipo</div></th>
        <th class="style4" scope="col"><div align="left">valor</div></th>
      </tr>
      <tr>
        <th class="style4" scope="col">&nbsp;</th>
        <th class="style4" scope="col">&nbsp;</th>
        <th class="style4" scope="col">&nbsp;</th>
        <th class="style4" scope="col">&nbsp;</th>
      </tr>
      
      <?php
	  
	  	echo $usu->GeraExtrato($codusuario,$datainicial,$datafinal);
	  
	  ?>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">-------------------------------------------------</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><strong>Conta Acad&ecirc;mica - CEFET Bambu&iacute;</strong></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
	<a href="javascript:history.back(-1);">Voltar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="javascript:window.print();" >Imprimir</a>

    </div></td>
  </tr>
</table>
</body>
</html>
