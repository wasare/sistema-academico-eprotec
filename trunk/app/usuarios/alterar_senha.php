<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);

$RsPessoa = $conn->Execute("SELECT nome, email FROM pessoas WHERE id = $sa_ref_pessoa");


$msg = '';

if($_POST) {

    $senha_atual = $_POST['senha_atual'];

    $sqlSenhaAtual = "SELECT senha FROM usuario WHERE id = $sa_usuario_id  AND senha = '".
        hash('sha256',$senha_atual)."';";
    $RsSenhaAtual = $conn->Execute($sqlSenhaAtual);

    if($RsSenhaAtual->RecordCount() != 1) {
        $msg = 'A senha atual n&atilde;o confere!';
    }else {
        $senha = $_POST['senha'];
        $sqlUsuario = "UPDATE usuario SET senha='".hash('sha256',$senha)."' WHERE id = $sa_usuario_id;";

        if($conn->Execute($sqlUsuario)) {

            $msg = '<font color="green">Senha alterada com sucesso!</font>';

			$message = 'SA - Senha do usuário "'.$sa_usuario.'" alterada para: '.$senha;

            if(mail($RsPessoa->fields[1], 'SA - Senha alterada', $message, 'From: SA')) {
                $msg .= '<p><font color=green>A nova senha foi enviada para o seu email.</font></p>';
            }else {
                $msg .= '<p>Erro ao enviar email com a nova senha!</p>';
            }
        }else {
            $msg = 'Ocorreu alguma falha ao alterar a senha!';
        }
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
        <script src="../../lib/SpryAssets/passwordvalidation/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../../lib/SpryAssets/confirmvalidation/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../../lib/SpryAssets/passwordvalidation/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
        <link href="../../lib/SpryAssets/confirmvalidation/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Alterar senha do usu&aacute;rio "<?=$sa_usuario?>"</h2>
        <form id="form1" name="form1" method="post" action="alterar_senha.php">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="60">
                        <div align="center">
                            <label class="bar_menu_texto">
                                <input name="save" 
                                       type="image"
                                       src="../../public/images/icons/save.png" />
                                <br />Salvar
                            </label>
                        </div>
                    </td>
                    <td width="60">
                        <div align="center">
                            <a href="javascript:history.back();"
                               class="bar_menu_texto">
                                <img src="../../public/images/icons/back.png"
                                     alt="Voltar"
                                     width="20"
                                     height="20" />
                                <br />Voltar
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="box_geral">
                <strong>Pessoa:</strong><br />
                <?=$RsPessoa->fields[0]?><br />
                <p>
                    <span id="sprypassword1">
                        <strong>Senha atual:</strong><br />
                        <input type="password" name="senha_atual" id="senha_atual" />
                        <span class="passwordRequiredMsg">Valor obrigat&oacute;rio</span>
                    </span>
                </p>
                <p>
                    <span id="sprypassword2">
                        <strong>Nova senha:</strong><br />
                        <input type="password" name="senha" id="senha" />
                        <span class="passwordRequiredMsg">Valor obrigat&oacute;rio</span>
                    </span><br />
                    <span id="spryconfirm1">
                        <strong>Confirme a nova senha:</strong><br />
                        <input type="password" name="confirm1" id="confirm1" />
                        <span class="confirmRequiredMsg">Valor obrigat&oacute;rio</span>
                        <span class="confirmInvalidMsg">As senhas n&atilde;o conferem.</span>
                    </span>
                </p>
            </div>
            <p>
                <font color="red"><strong><?php echo $msg;?></strong></font>
            </p>
        </form>
        <script type="text/javascript">
            <!--
            var sprypass1 = new Spry.Widget.ValidationPassword("sprypassword1");
            var sprypass2 = new Spry.Widget.ValidationPassword("sprypassword2");
            var spryconf1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "sprypassword2");
            //-->
        </script>
    </body>
</html>
