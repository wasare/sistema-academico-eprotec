<?php
/*
 * Arquivo com as configuracoes iniciais
 */
require_once("../../app/setup.php");

/*
 * Estancia a classe de conexao e abre
 */
$conn = new connection_factory($param_conn);

/*
 * Executa uma instrucao SQL no banco de dados
 */
$sql = "UPDATE salas SET 
            ref_campus=null,
            numero='".$_POST['numero']."',
            capacidade='".$_POST['capacidade']."'
        WHERE
            id=".$_POST['id']."; ";

$conn->Execute($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Alterar sala</h2>
        <div class="panel">
            <font color="green">Sala alterada com sucesso!</font>
            <p>
                <a href="index.php">Voltar para o controle de salas</a>
            </p>
        </div>
    </body>
</html>
