<?php
/*
 * Arquivo com as configuracoes iniciais
 */
require_once("../../app/setup.php");

/*
 * Parametros do formulario de cadastro
 */
$id_pessoa    = 819;//$_POST['id_pessoa'];
$departamento = $_POST['departamento'];
$data         = $_POST['data'];
$user         = $_POST['user'];
$password     = $_POST['password'];


/*
 * Estancia a classe de conexao e abre
 */
$conn = new connection_factory($param_conn);

$sql_conf_pessoa = "
SELECT COUNT(id)
FROM professores
WHERE ref_professor = ".$id_pessoa.";";

$count = $conn->get_one($sql_conf_pessoa);

if($count > 0 ) {
    $msg_error = '<b>Erro:</b> Pessoa f&iacute;sica j&aacute; cadastrada.';
}else {
    $sql_conf_user = "";
    $count = $conn->get_one($sql_conf_pessoa);
    if($count > 0){
        $msg_error = '<b>Erro:</b> Usu&aacute;rio j&aacute; exite.
        Na tela de cadastro clique em \'verificar\' para ver
        a disponibilidade do usuario.';
    }
}

?>
<html>
    <head>
        <?=$DOC_TYPE?>
        <title>SA</title>
        <link href="../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Cadastrar professor</h2>
        <div class="panel">
            <font color="red"><?=$msg_error?></font>
            <p>
                <a href="cadastrar.php" class="bar_menu_texto">
                    Cadastrar professor
                </a>&nbsp;&nbsp;
                <a href="index.php" class="bar_menu_texto">
                    Voltar para p&aacute;gina inicial de professores
                </a>
            </p>
        </div>
    </body>
</html>
