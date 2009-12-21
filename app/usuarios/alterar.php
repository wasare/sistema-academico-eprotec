<?php

require_once(dirname(__FILE__) ."/../setup.php");

$conn = new connection_factory($param_conn);

$id_usuario = $_GET["id_usuario"];

$sqlUsuario = '
SELECT
    u.id,
    u.nome,
    u.ativado,
    u.ref_pessoa,
    p.nome,
    s.nome_setor
FROM
    usuario u, setor s, pessoas p
WHERE
    s.id = u.ref_setor AND
    u.ref_pessoa = p.id AND
    u.id = '.$id_usuario;

$RsUsuario = $conn->Execute($sqlUsuario);
$RsSetor = $conn->Execute('SELECT id, nome_setor FROM setor;');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
        <script>
            function validarSenha(){
                if(document.form1.senha_atual.checked == 1){
                    return true;
                }else{
                    senha1 = document.form1.senha.value;
                    senha2 = document.form1.resenha.value;
                    if(senha1 == ""){
                        alert("O campo senha nao pode ser vazio!");
                        return false;
                    }
                    if (senha1 != senha2){
                        alert("As senhas nao conferem!");
                        return false;
                    }
                    return true;
                }
            }
        </script>
    </head>
    <body>
        <h2>Alterar usu&aacute;rio</h2>

        <form id="form1" name="form1" method="post" action="alterar_action.php" onSubmit="return validarSenha()">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>" />

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
                <strong>Pessoa:</strong><br />
                <?=$RsUsuario->fields[3]?> - <?=$RsUsuario->fields[4]?><br />
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
                    <input type="text"
                           name="usuario"
                           id="usuario"
                           value="<?php echo $RsUsuario->fields[1]; ?>"
                           disabled="disabled" />
                </p>
                <strong>Senha:</strong><br />
                <input type="password" name="senha" id="senha" /><br />
                <strong>Digite a senha novamente:</strong><br />
                <input type="password" name="resenha" id="resenha" />
                <p>
                    Manter senha atual?
                    <input type="checkbox"
                           name="senha_atual"
                           id="senha_atual" />
                    <span class="comentario">Marcado para sim.</span>
                </p>
                <p>
                    <strong>Permiss&otilde;es:</strong><br />
                    <select name="permissao[]" id="permissao[]" multiple="multiple" size="4">
                        <?php

                        //Permissoes de usuario

                        $sqlPapelUsuario =  'SELECT ref_papel '.
                            'FROM usuario_papel '.
                            'WHERE ref_usuario = '.$RsUsuario->fields[0];

                        $arr_papel_usuario = $conn->adodb->GetCol($sqlPapelUsuario);

                        $arr_papel = $conn->adodb->GetAll('SELECT papel_id, descricao, nome FROM papel');

                        foreach($arr_papel as $papel) {
                            if(in_array($papel['papel_id'],$arr_papel_usuario)) {
                                echo '<option value="'.$papel['papel_id'].'" selected="selected" >';
                                echo $papel['nome']."</option>";
                            }else {
                                echo '<option value="'.$papel['papel_id'].'" >';
                                echo $papel['nome']."</option>";
                            }
                        }
                        ?>
                    </select>
                </p>
                <p>
                    Usu&aacute;rio ativado?
                    <?php
                    if ($RsUsuario->fields[2] == 't') {
                        echo '<input type="checkbox" checked="checked" name="ativado" id="ativado" />';
                    }
                    else {
                        echo '<input type="checkbox" name="ativado" id="ativado" />';
                    }
                    ?> <span class="comentario">Marcado para sim.</span>
                </p>
            </div>
        </form>
    </body>
</html>
