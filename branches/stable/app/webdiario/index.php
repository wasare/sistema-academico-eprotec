<?php
//include_once('manutencao.php');
	
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
</head>

<body background="img/bar1.jpg" text="#000000" onLoad="document.form1.user.focus()">

<div align="center">
  <form name="form1" method="post" action="principal.php">
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
		<table width="217" border="0" align="center" height="187">
           <tr>
              <td height="50" colspan="2" align="center" valign="top"><span class="title">Entre com seu us&aacute;rio e senha do Web Di&aacute;rio</span></td>
            </tr>
            <tr>
              <td width="60" height="16"><span class="login">Login:</span></td>
              <td width="147" height="16"><input type="text" name="user"></td>
            </tr>
            <tr>
              <td height="6"><span class="login">Senha:</span></td>
              <td height="6"><span class="login"><input type="password" name="senha"></span></td>
            </tr>
            <tr>
              <td height="6"><span class="login">Per&iacute;odo:</span></td>
              <td height="6"><select id="speriodo" name="speriodo">
				<option value="20092" selected="selected">2009 / 2&ordm; Semestre</option>
                <option value="20091" >2009 / 1&ordm; Semestre</option>
                <option value="20082" >2008 / 2&ordm; Semestre</option>
                <option value="20081" >2008 / 1&ordm; Semestre</option>
                <option value="20072" >2007 / 2&ordm; Semestre</option>
                <option value="20071" >2007 / 1&ordm; Semestre</option>
                <option value="20062" >2006 / 2&ordm; Semestre</option>
                <option value="20061" >2006 / 1&ordm; Semestre</option>
              </select></td>
            </tr>
            <tr>
              <td colspan="2" height="41" align="center">
			  <p> <br>
			    <input type="submit" name="submit" target="_parent" value="Entrar">
			    </p>
				<p align="center"><a href="senha/">Esqueceu sua senha?<br>Primeiro acesso?</a> <br />  <br /> <a href="../../docs/usuario/manual_webdiario_professor.pdf">Manual do Webdi&aacute;rio (M&oacute;dulo Professor)</a></p></td>
            </tr>
          </table>
	    </td>
	  </tr>
      <tr>
        <td height="50" valign="middle"><div align="center"><img src="img/postgres.gif"> <img src="img/php.png"> <img src="img/linux.png"> <img src="img/gti.gif"></div></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
