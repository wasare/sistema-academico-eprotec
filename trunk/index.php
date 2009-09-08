<?php


// controle para evitar a verificacao do login antes da autenticacao
$_POST['sa_login'] = 'sa_login';

require_once(dirname(__FILE__).'/configs/configuracao.php');

session::destroy();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SA</title>
	<link href="public/images/favicon.ico" rel="shortcut icon" />
	<link href="public/styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div align="center">
		<div id="caixa_login">
			<div id="caixa_login2">
				<form method="post" action="core/login/login.php" name="myform">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top">
								<p>
									<br />
									Bem vindo ao
								</p>
							</td>
							<td>
								<img src="public/images/sa_icon.png" width="80" height="68" />
							</td>
						</tr>
					</table>
					<h3>Entre com sua conta</h3>
					Nome de usu&aacute;rio:
					<br />
					<input type="text" name="uid" maxlength="20" style="width: 140px;" /> 
					<br />
					Senha:<br />
					<input type="password" name="pwd" maxlength="20" style="width: 140px;" />
					<input type="hidden" name="sa_login" id="sa_login" value="sa_login" />
					<p>
						<input type="submit" name="Submit" value=" Entrar " />
					</p>
				</form>
				<br />
			</div>
		</div>
	</div>
</body>
</html>
