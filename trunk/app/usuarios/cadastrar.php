<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);

$RsSetor = $conn->Execute('SELECT id, nome_setor FROM setor;');
$RsPapel = $conn->Execute('SELECT papel_id, descricao, nome FROM papel');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
        <script src="../../lib/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../../lib/Spry/widgets/passwordvalidation/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../../lib/Spry/widgets/confirmvalidation/SpryValidationConfirm.js" type="text/javascript"></script>
        <link href="../../lib/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
        <link href="../../lib/Spry/widgets/passwordvalidation/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
        <link href="../../lib/Spry/widgets/confirmvalidation/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Cadastrar usu&aacute;rio</h2>

        <form id="form1" name="form1" method="post" action="cadastrar_action.php" >

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

            <div class="panel">
                <strong>C&oacute;digo de pessoa:</strong><br />
                <span id="sprytextfield1">
                    <input name="ref_pessoa" id="ref_pessoa" type="text" maxlenght="8" size="8" value="" />
                    <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio</span>
                </span>
                <br />
                <strong>Setor:</strong><br />
                <select name="setor" id="setor">
                    <?php
                    while(!$RsSetor->EOF) {
                        echo '<option value="'.$RsSetor->fields[0].'" >';
                        echo $RsSetor->fields[1]."</option>";
                        $RsSetor->MoveNext();
                    }
                    ?>
                </select>
                <p>
                    <strong>Usu&aacute;rio:</strong><br />
                    <span id="sprytextfield2">
                        <input type="text" name="usuario" id="usuario" />
                        <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio</span>
                    </span>
                </p>
                <p>
                    <span id="sprypassword1">
                        <strong>Senha:</strong><br />
                        <input type="password" name="senha" id="senha" />
                        <span class="passwordRequiredMsg">Valor obrigat&oacute;rio</span>
                    </span><br />
                    <span id="spryconfirm1">
                        <strong>Confirme a senha:</strong><br />
                        <input type="password" name="confirm1" id="confirm1" />
                        <span class="confirmRequiredMsg">Valor obrigat&oacute;rio</span>
                        <span class="confirmInvalidMsg">As senhas n&atilde;o conferem.</span>
                    </span>
                </p>

                <p>
                    <strong>Permiss&otilde;es:</strong><br />
                    <select name="permissao[]" id="permissao[]" multiple="multiple" size="4">
                        <?php
                        while(!$RsPapel->EOF) {
                            echo '<option value="'.$RsPapel->fields[0].'" >';
                            echo $RsPapel->fields[2]."</option>";
                            $RsPapel->MoveNext();
                        }
                        ?>
                    </select>
                </p>
                <p>
					Usu&aacute;rio ativado? 
                    <input type="checkbox" checked="checked" name="ativado" id="ativado" />
                    <span class="comentario">Marcado para sim.</span>
                </p>
            </div>
        </form>
        <script type="text/javascript">
            <!--
            var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
            var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
            var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
            var sprypass1 = new Spry.Widget.ValidationPassword("sprypassword1");
            var spryconf1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "sprypassword1");
            //-->
        </script>
    </body>
</html>
