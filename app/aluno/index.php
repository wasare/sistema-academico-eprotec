<?php
require_once(dirname(__FILE__) .'/../../config/configuracao.php');
require_once($BASE_DIR .'core/login/session.php');

// Inicia a sessao
$sessao = new session($param_conn);
$sessao->destroy();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title> - Sistema Acad&ecirc;mico</title>
        <link href="../../public/images/favicon.ico" rel="shortcut icon" />
        <link href="../../public/styles/style.css" rel="stylesheet" type="text/css" />
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
                        <img src="../../public/images/sa_icon.png" alt="logomarca SA" width="80" height="68" style="margin: 10px;" />
                    </td>
                    <td valign="top">
                        <h3>Bem vindo ao Sistema Acad&ecirc;mico - M&oacute;dulo do aluno.</h3>

                        No Registro Escolar utilizar o n&uacute;mero da sua carteira de estudante; <br />
                        Na data de nascimento informe a data no formato DD/MM/AAAA.<br />
                        Se ocorrer algum problema de usu&aacute;rio ou senha inv&aacute;lido, verfique os dados.<br />
                        Caso persista o erro consulte no Setor de Registros Escolares se a sua data de nascimento est&aacute; correta no sistema. <br />

                        Na senha utilize o seu Registro Escolar + zeros a esquerda at&eacute; completar 5 algarismos.<br />
                        <b>Exemplo:</b> para registro n&ordm; 135 a senha ser&aacute; 00135.<br />
                        <b>Qualquer diverg&ecirc;ncia em notas e faltas informe-se com o seu professor.</b><br />

                    </td>
                </tr>
            </table>
            <h2>Entre com sua conta</h2>
            <div id="caixa_login">
                <form name="form1" method="post" action="lista_cursos.php">
                    <table border="0">
                        <tr>
                            <td align="right">
                                Matr&iacute;cula:
                            </td>
                            <td>
                                <input type="text" id="user" name="user" maxlength="20" style="width: 140px;" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">

                                Data de nascimento:
                            </td>
                            <td>
                                <input type="text" id="nasc" name="nasc" maxlength="20" style="width: 140px;" />
                                <span class="style1">(DD/MM/AAAA)</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">

                                Senha:
                            </td>
                            <td>
                                <input type="password" id="senha" name="senha" maxlength="20" style="width: 140px;" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <p>
                                    <input type="image" src="../../public/images/bt_entrar.png" />
                                </p>
                                <a href="esqueci_senha.php">Esqueci minha senha</a>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="btnOK" value="true" />
                    <input type="hidden" id="sa_login" name="sa_login" value="aluno_login" />
                </form>
            </div>

            <table border="0">
                <tr>
                    <td>
                        <img src="../../public/images/logo.jpg" alt="IFMG Campus Bambu&ia&iacute;" style="margin: 10px;" />
                    </td>
                    <td>
                        <strong>Instituto Federal Minas Gerais</strong><br />
                        Campus Bambu&iacute;<br />

                        Ger&ecirc;ncia de Tecnologia da Informa&ccedil;&atilde;o
                    </td>
                </tr>
            </table>
            <p>
                <font color="#999999">&copy;2009 IFMG Campus Bambu&iacute;</font>
            </p>
        </div>

    </body>
</html>

