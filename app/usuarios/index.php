<?php

require_once("../../app/setup.php");

$conn = new connection_factory($param_conn);

$sql = '
SELECT
    usuario.id,
    usuario.nome,
    usuario.nome_completo,
    usuario.email,
    usuario.setor,
    usuario.obs,
    usuario.grupo,
    usuario.ref_pessoa,
    usuario.senha,
    usuario.ativado,
    setor.nome_setor
FROM
    usuario, setor
WHERE
    lower(to_ascii("nome")) like lower(to_ascii(\'%'.$_POST['nome'].'%\')) AND
    setor.id = usuario.ref_setor
ORDER BY lower(usuario.nome)';

$sql = iconv("utf-8","iso-8859-1",$sql);

$RsNome = $conn->Execute($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
        <link href="../../public/styles/style.css" rel="stylesheet" type="text/css" />
        <script language="javascript" src="../../lib/prototype.js" type="text/js"></script>
        <script language="javascript" src="index.js" type="text/js"></script>
    </head>

    <body onLoad="pesquisar();">
        <h2>Controle de usu&aacute;rios</h2>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="60">
                    <div align="center">
                        <a href="cadastrar.php" class="bar_menu_texto">
                            <img src="../../public/images/icons/new.png" alt="Novo" width="20" height="20" />
                            <br />Novo
                        </a>
                    </div>
                </td>
                <td width="60">
                    <div align="center">
                        <a href="javascript:history.back();" class="bar_menu_texto">
                            <img src="../../public/images/icons/back.png" alt="Voltar" width="20" height="20" />
                            <br />
                            Voltar
                        </a>
                    </div>
                </td>
            </tr>
        </table>

        <table width="80%" border="0">
            <tr  style="font-weight:bold; color: white; background-color: black;">
                <td>Usu&aacute;rio</td>
                <td>Setor</td>
                <td>Permiss&atilde;o</td>
                <td>Alterar</td>
            </tr>
            <?php

            while(!$RsNome->EOF) {

                if($RsNome->fields[9] == 't') {
                    $cor_linha = '#DDDDDD';
                    $situacao = ' ';

                } else {
                    $cor_linha = '#999999';
                    $situacao = ' - <font color="#DDDDDD">Usu&aacute;rio desativado</font>';
                }
            ?>
            <tr bgcolor="<?=$cor_linha?>">
                <td align="left">
                    <a href="../relatorios/pessoas/lista_pessoas.php?id_pessoa=<?=$RsNome->fields[7]?>" target="blank"><?=$RsNome->fields[1]?></a>
                    <?=$situacao?>
                </td>
                <td align="left"><?=$RsNome->fields[10]?></td>
                <td align="left"><?=$RsNome->fields[6]?></td>
                <td align="center">
                    <a href="alterar.php?id_usuario=<?=$RsNome->fields[0]?>">
                        <img src="../../public/images/icons/edit.png" alt="Editar" />
                    </a>
                </td>
            </tr>
            <?php
                $RsNome->MoveNext();
            }
            ?>
        </table>
    </body>
</html>
