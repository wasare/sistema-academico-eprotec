<?php

require_once('config/configuracao.php');
require_once('core/data/connection_factory.php');
require_once('core/login/session.php');
require_once('core/login/auth.php');

session::init($param_conn);

if($_POST) {
    $conn = new connection_factory($param_conn);
    exit(auth::login($_POST['uid'],$_POST['pwd'],$_POST['modulo'], $conn));
}

session::destroy();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>SA</title>
        <link href="public/images/favicon.ico" rel="shortcut icon" />
        <link href="public/styles/style.css" rel="stylesheet" type="text/css" />
        <style>
            #alert_login{
                font-family:verdana,arial;
                font-size:14;
                font-weight:bold;
                color: red;
                position:absolute;
                top: 50%;
                left: 50%;
                margin-left:-170px;
                margin-top:-120px;
                width:300px;
                height:180px;
                z-index:1;
                background-color:#FFF6D5;
                padding: 4px;
                border: 4px solid orange;
            }
            #alert_login a{
                text-align:right;
            }
            #caixa_login {
                background-color: #CEE7FF;
                width:300px;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
                border: 4px solid #3399FF;
                padding: 10px 5px 10px 5px;
                margin: 10px 5px 10px 5px;
            }
        </style>
    </head>
    <body>
        <div align="center">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <img src="public/images/sa_icon.png" alt="logomarca SA" width="80" height="68" style="margin: 10px;" />
                    </td>
                    <td valign="top">
                        <h3>Bem vindo ao Sistema Acad&ecirc;mico.</h3>
                        Para iniciar entre com os dados de sua conta e selecione um m&oacute;dulo.<br />
                        Caso n&atilde;o possua uma conta cadastre-se junto a secretaria do campus.
                        <br />
                    </td>
                </tr>
            </table>
            <h2>Entre com sua conta</h2>
            <div id="caixa_login">
                <form method="post" action="" name="myform">
                    <table border="0">
                        <tr>
                            <td align="right">
                                Usu&aacute;rio:
                            </td>
                            <td>
                                <input type="text" name="uid" maxlength="20" style="width: 140px;" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                Senha:
                            </td>
                            <td>
                                <input type="password" name="pwd" maxlength="20" style="width: 140px;" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                M&oacute;dulo:
                            </td>
                            <td>
                                <select id="modulo" name="modulo" style="width: 145px;">
									<option value="web_diario_login">Coordenador</option>
                                    <option value="web_diario_login">Professor</option>
                                    <option value="sa_login" selected>Secretaria</option>
                                    <option value="aluno_login">Aluno</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <p>
                                    <input type="image" src="public/images/bt_entrar.png" />
                                </p>
                                <a href="#">Esqueci minha senha.</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <table border="0">
                <tr>
                    <td>
                        <img src="public/images/logo.jpg" alt="IFMG Campus Bambu&ia&iacute;" style="margin: 10px;" />
                    </td>
                    <td>
                        <strong>Instituto Federal Minas Gerais</strong><br />
                        Campus Bambu&iacute;<br />
                        Ger&ecirc;ncia de Tecnologia da Informa&ccedil;&atilde;o
                    </td>
                </tr>
            </table>
        </div>
        <!-- Mensagens -->
        <?php if($_GET['sa_msg']) { ?>
        <div id="alert_login">
            <table border="0">
                <tr>
                    <td colspan="2" align="right">
                        <a href="#" onclick="document.getElementById('alert_login').style.display = 'none'">Fechar</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="public/images/alert.png" alt="Aten&c&ccedil;&ati&atilde;o" />
                    </td>
                    <td>
                        <?php echo $_GET['sa_msg']; ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php } ?>
    </body>
</html>
