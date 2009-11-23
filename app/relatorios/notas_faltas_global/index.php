<?php
/*
 * Arquivo com as configuracoes iniciais
 */
require_once("../../../app/setup.php");

/*
 * Estancia a classe de conexao e abre
 */
$conn = new connection_factory($param_conn);

/*
 * Realiza uma consulta no banco de dados retornando um vetor multidimensional
 */
$sql = 'SELECT
            id, nome_setor, email
        FROM setor
        ORDER BY nome_setor
        LIMIT 20;';

$arr_setor = $conn->get_all($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>SA</title>
        <link href="../../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Relat&oacute;rio global de notas e faltas</h2>
        <div class="btn_action">
            <a href="javascript:history.back();" class="bar_menu_texto">
                <img src="../../../public/images/icons/back.png" alt="Voltar" width="20" height="20" />
                <br />Voltar
            </a>
        </div>
        <div class="panel">
            <form action="notas_faltas_global.php" method="post">
                Per&iacuate;odo:<br />
                <input type="text" id="periodo" name="periodo" />
                <br />
                Campus:<br />
                <input type="text" id="campus" name="campus" />
                <br />
                Curso:<br />
                <input type="text" id="curso" name="curso" />
                <br />
                Turma:<br />
                <input type="text" id="turma" name="turma" />
                <br />
                <p>
                    <input type="submit" value="Gerar" />
                </p>
            </form>
        </div>
    </body>
</html>
