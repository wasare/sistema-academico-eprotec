<?php
/*
 * Arquivo com as configuracoes iniciais
*/
require_once("../../app/setup.php");

/*
 * Estancia a classe de conexao e abre
*/
$conn = new connection_factory($param_conn);

$sql_professores = "
    SELECT
        p.ref_departamento,
        p.dt_ingresso,
        u.nome as login,
        u.ativado,
        u.ref_setor
    FROM professores p, usuario u
    WHERE
        p.ref_professor = $id AND
        p.ref_professor = u.ref_pessoa";

//$arr_professores = $conn->get_row($sql_professor);

$arr_departamentos = $conn->get_all('SELECT id, descricao FROM departamentos');

$arr_setor = $conn->get_all('SELECT id, nome_setor FROM setor');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <?=$DOC_TYPE?>
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
        <script src="../../lib/Spry/widgets/textfieldvalidation/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="../../lib/Spry/widgets/passwordvalidation/SpryValidationPassword.js" type="text/javascript"></script>
        <script src="../../lib/Spry/widgets/confirmvalidation/SpryValidationConfirm.js" type="text/javascript"></script>
        <script src="../../lib/Spry/widgets/selectvalidation/SpryValidationSelect.js" type="text/javascript"></script>
        <link href="../../lib/Spry/widgets/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
        <link href="../../lib/Spry/widgets/passwordvalidation/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
        <link href="../../lib/Spry/widgets/confirmvalidation/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
        <link href="../../lib/Spry/widgets/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />

    </head>
    <body>
        <h2>Alterar professor</h2>
        <form id="form1" name="form1" method="post" action="cadastrar_action.php" >
            <div class="btn_action">
                <label class="btn_action">
                    <input name="save" type="image" src="../../public/images/icons/save.png" />
                    <br />Salvar
                </label>
            </div>
            <div class="btn_action">
                <a href="javascript:history.back();" class="bar_menu_texto">
                    <img src="../../public/images/icons/back.png" alt="Voltar" width="20" height="20" />
                    <br />Voltar
                </a>
            </div>
            <div class="panel">
                C&oacute;digo de pessoa f&iacute;sica:<br />
                <span id="sprytextfield1">
                    <input type="text" id="id_pessoa" name="id_pessoa" value="<?=$id?>" />
                    <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio</span>
                    <span class="textfieldInvalidFormatMsg">Somente n&uacute;mero inteiro.</span>
                </span>
                <br />
                Departamento:
                <br />
                <span id="validsel1">
                    <select name="departamento" id="departamento" tabindex="1">
                        <option value="">Selecione o departamento</option>
                        <?php foreach($arr_departamentos as $departamento): ?>
                        <option value="<?=$departamento['id']?>"><?=$departamento['descricao']?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="selectRequiredMsg">Selecione um item.</span>
                </span>
                <br />
                Data de ingresso:
                <br />
                <span id="date1">
                    <input type="text" name="data" id="data" />
                    <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio.</span>
                    <span class="textfieldInvalidFormatMsg">Formato inv&aacute;lido.</span>
                </span>
                <h3>Acesso ao Web Di&aacute;rio</h3>
                <p>
                    Usu&aacute;rio:
                    <br />
                    <span id="sprytextfield2">
                        <input type="text" id="user" name="user" value="<?=$arr_professores['login']?>" />
                        <a href="#">Verificar</a>
                        <input type="hidden" id="flg_user" name="flg_user" value="">
                        <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio</span>
                    </span>
                    <br />
                    Setor:
                    <br />
                    <span id="validsel2">
                        <select name="setor" id="setor" tabindex="1">
                            <option value="">Selecione o setor do usu&aacute;rio</option>
                            <?php foreach($arr_setor as $setor): ?>
                            <option value="<?=$setor['id']?>"><?=$setor['nome_setor']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="selectRequiredMsg">Selecione um item.</span>
                    </span>
                    <br />
                    <input name="ativar" type="checkbox" id="ativar" checked="checked"/>
                    <br />
                    <br />
                    Nova senha:
                    <br />
                    <span id="sprypassword1">
                        <input type="password" name="password" id="password" />
                        <span class="passwordRequiredMsg">Valor obrigat&oacute;rio.</span>
                    </span>
                    <br />
                    Confirme a nova senha:
                    <br/>
                    <span id="spryconfirm1">
                        <input type="password" name="confirm" id="confirm" />
                        <span class="confirmRequiredMsg">Valor obrigat&oacute;rio.</span>
                        <span class="confirmInvalidMsg">As senhas n&atilde;o conferem.</span>
                    </span>
                </p>
            </div>
        </form>
        <script type="text/javascript">
            <!--
            var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1","integer");
            var validsel1 = new Spry.Widget.ValidationSelect("validsel1", {validateOn:["change"]});
            var validsel2 = new Spry.Widget.ValidationSelect("validsel2", {validateOn:["change"]});
            var date1 = new Spry.Widget.ValidationTextField("date1", "date", {format:"dd/mm/yyyy", hint:"dd/mm/yyyy", validateOn:["blur", "change"], useCharacterMasking:true});
            var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
            var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
            var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "sprypassword1");
            //-->
        </script>
    </body>
</html>
