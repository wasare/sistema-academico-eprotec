<?php

session_start();
setcookie ("us", "0", time( )-9999);
setcookie ("login", "0", time( )-9999);
$_SESSION = array();
session_destroy();
?>

<html>
<head>
<title>Web Di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/geral.css" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 12}
-->
</style>
</head>

<body background="img/bar1.jpg" text="#000000">

<div align="center">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="770" height="60" background="img/top2.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" valign="middle" bgcolor="#FFFFFF">
			<div align="right"><span class="login">Bambu&iacute;, <?php echo gmdate (d.' / '.m. ' / '.Y); ?></span></div>
		</td>
      </tr>
      <tr>
	    <td height="400" bgcolor="#FFFFFF">
		<table width="217" border="0" align="center" height="45">
            <tr>
              <td width="207" height="41" align="center">
			  <p><span class="title_erro style2">Usu&aacute;rio e/ou Senha inv&aacute;lido(s)! </span></p>
			  <p><a href="index.php">Voltar a tela de autentica&ccedil;&atilde;o</a></p>
			  <a href="senha/">Esqueceu a senha ou primeiro acesso?</a></td>
            </tr>
          </table>
	    </td>
	  </tr>
      <tr>
        <td height="50" valign="middle"><div align="center"><img src="img/postgres.gif"> <img src="img/php.png"> <img src="img/linux.png"> <img src="img/gti.gif"></div></td>
      </tr>
    </table>
</div>
</body>
</html>
