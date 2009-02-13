<?php

require("lib/properties.php");
require("lib/config.php"); 

if ( !empty($SessionAuth) ){
  $no_login_check = 1;
  require("lib/common.php");
  list ( $LoginUID, $LoginPWD ) = split(":",$SessionAuth,2);
  LogSQL("*** LOGOUT (uid=$LoginUID,pwd=) ***");
  
  session_destroy();
}

$properties->Cleanup();

$sagu_cookie = '';
SetCookie("SessionAuth","",0,"/","$sagu_cookie",0); 
SetCookie("SessionUsuario","",0,"/","$sagu_cookie",0);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SA</title>
<link href="favicon.ico" rel="shortcut icon" />
<link href="Styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
  <div id="caixa_login">
  <div id="caixa_login2">
    <form method="post" action="lib/login.php" name="myform">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><p><br />Bem vindo ao </p>
          </td>
          <td><img src="images/sa_icon.png" width="80" height="68" /></td>
        </tr>
      </table>
      <h3>Entre com sua conta</h3>
      Nome de usu&aacute;rio:<br />
      <input type="text" name="uid" maxlength="20" style="width:140px;" />
      <br />
      Senha:<br />
      <input type="password" name="pwd" maxlength="20" style="width:140px;" />
      <p>
        <input type="submit" name="Submit" value=" Entrar " />
        <!--<\?php echo($TestCookie);?>-->
        <input type="hidden" name="home" value="https://<?php echo($SAGU_HOST); ?>/inicio.php" />
        <br />
      </p>
    </form>
    <br/>
  </div>
  </div>
  <p>
  <span class="atalhos"> <a href="https://sistemas.cefetbambui.edu.br/webdiario" target="_blank">Ir para o WebDi&aacute;rio</a></span></p>
</div>
</body>
</html>
