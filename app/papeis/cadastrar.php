<?php

require_once("../setup.php");

$conn    = new connection_factory($param_conn);

?>
<html>
    <head>
        <title>SA</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Cadastro de permiss&atilde;o</h2>
        <div class="box_geral">
            <form method="post" action="cadastrar_action.php">
                C&oacute;digo da pessoa:<br />
                <input type="text" name="ref_pessoa" id="codigo_pessoa" size="8" />
                <input type="text" name="nome_pessoa" id="nome_pessoa" size="20" disabled />
                <a>Buscar</a>
                <br />
                Permiss&atilde;o:
                <br />
                <select name="permissao" id="permissao" multiple="multiple" size="4">
                    <option value="adm">Administrador</option>
                    <option value="sec">Secretaria</option>
                    <option value="pro">Professor</option>
                </select>
                <br />
                Setor:
                <br />
                <select name="setor" id="setor">
                    <option value="adm">Administrador</option>
                    <option value="sec">Secretaria</option>
                    <option value="pro">Professor</option>
                </select>
                <br />
                <br />
                Usuário:
                <br />
                <input type="text" name="nome" id="nome" />
                <br />
                Senha:
                <br />
                <input type="password" name="senha1" id="senha1" />
                <br />
                Digite a senha novamente:
                <br />
                <input type="password" name="senha2" id="senha2" />
                <br />
                <br />
                <br />
                <input type="submit" value="Confirmar" />
            </form>
        </div>
    </body>
</html>
