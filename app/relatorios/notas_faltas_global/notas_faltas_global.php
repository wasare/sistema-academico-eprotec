<?php
/*
 * Arquivo com as configuracoes iniciais
 */
require_once("../../../app/setup.php");

/**
 * Parametros do formulario
 */
$periodo = $_POST['periodo'];;
$campus  = $_POST['campus'];
$curso   = $_POST['curso'];;
$turma   = $_POST['turma'];;

/*
 * Estancia a classe de conexao e abre
 */
$conn = new connection_factory($param_conn);


/**
 * Busca a descricao do periodo
 */
$sql_periodo = '
SELECT DISTINCT descricao
FROM periodos WHERE id = \''. $periodo.'\';';

$desc_periodo = $conn->get_one($sql_periodo);


/**
 * Busca a descricao do curso
 */
$sql_curso = "
SELECT DISTINCT
    a.ref_curso || ' - ' || c.descricao AS curso, b.nome_campus
FROM
    disciplinas_ofer a, cursos c, campus b
WHERE
    a.ref_periodo = '".$periodo."' AND
    c.id = a.ref_curso AND
    a.ref_curso = ".$curso." AND
    a.ref_campus = b.id AND
    b.id = $campus; ";

$desc_curso = $conn->get_row($sql_curso);


/**
 * Conteudo da legenda
 */
$sql_legenda = "
SELECT
    o.id AS diario, d.id, d.descricao_disciplina, d.descricao_extenso,
    d.carga_horaria, p.id || ' - ' || p.nome AS prof
FROM
    disciplinas d, disciplinas_ofer o, disciplinas_ofer_prof dp, pessoas p
WHERE
    o.ref_periodo = '".$periodo."' and
    d.id = o.ref_disciplina AND
    dp.ref_disciplina_ofer = o.id AND
    dp.ref_professor IS NOT NULL AND
    p.id = dp.ref_professor AND
    o.turma = '".$turma."' AND
    o.ref_curso = ".$curso."
ORDER BY d.descricao_disciplina ;";

$arr_legenda = $conn->get_all($sql_legenda);

if(count($arr_legenda) == 0) {
    echo '<script language="javascript">window.alert("Nenhum diario encontrado!");</script>';
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
    exit;
}


/**
 * Consulta principal
 */
$sql_rel = "
SELECT * FROM (
    SELECT DISTINCT
        b.nome, b.id as matricula, a.nota_final, a.num_faltas, ref_disciplina_ofer
    FROM
        matricula a, pessoas b
    WHERE
        (a.dt_cancelamento is null) AND
        a.ref_disciplina_ofer IN (
            SELECT
                id from disciplinas_ofer
            WHERE
                fl_digitada = 't' AND
                fl_concluida = 't' AND
                is_cancelada = '0' AND
                ref_curso = $curso AND
                turma = '$turma' and ref_periodo = '$periodo'
        ) AND
        a.ref_pessoa = b.id AND
        a.ref_motivo_matricula = '0'
) AS T1
ORDER BY lower(to_ascii(nome)), ref_disciplina_ofer";

$arr_rel = $conn->get_all($sql_rel);

//Criar um vetor somente com os diarios
foreach($arr_rel as $rel) {
    $arr_diarios[]  = $rel['ref_disciplina_ofer'];
    $arr_alunosid[] = $rel['matricula'];
}

//Remove os valores duplicados
$arr_diarios = array_unique($arr_diarios);

//Remove os valores duplicados
$arr_alunosid = array_unique($arr_alunosid);

//Totalizando
$num_diarios = count($arr_diarios);
$num_alunos  = count($arr_alunosid);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>SA</title>
        <link href="../../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <h2>Resumo de Notas e Faltas</h2>
        <div class="btn_action">
            <a href="javascript:history.back();" class="bar_menu_texto">
                <img src="../../../public/images/icons/back.png" alt="Voltar" width="20" height="20" />
                <br />Voltar
            </a>
        </div>
        <div class="panel">
            <strong>Per&iacute;odo:</strong> <?php echo $desc_periodo; ?><br />
            <strong>Curso:</strong> <?php echo $desc_curso[0]; ?><br />
            <strong>Turma:</strong> <?php echo $turma; ?><br />
            <strong>Campus:</strong> <?php echo $desc_curso[1]; ?><br />
            <br />

            <b>LEGENDA</b>

            <table class="grid">
                <tr class="grid_head">
                    <td align="center">C&oacute;d. Di&aacute;rio</td>
                    <td align="center">Descri&ccedil;&atilde;o</td>
                    <td align="center">Professor(a)</td>
                    <td align="center">CH Prevista</td>
                    <td align="center">CH Realizada</td>
                    <td align="center">Nota Distribuida</td>
                </tr>

                <?php foreach($arr_legenda as $legenda) : ?>

                <tr class="grid_row">
                    <td align="center"><?=$legenda['diario']?></td>
                    <td><?=$legenda['id']?> - <?=$legenda['descricao_extenso']?></td>
                    <td><?=$legenda['prof']?></td>
                    <td align="center"><?=$legenda['carga_horaria']?></td>
                    <td align="center">
                            <?php
                            //Carga horaria realizada
                            $sql_realizada = "
                            SELECT SUM(CAST(flag AS INTEGER)) AS carga
                            FROM  diario_seq_faltas
                            WHERE  ref_disciplina_ofer = ".$legenda['diario']." ;";

                            $carga_realizada = $conn->get_one($sql_realizada);

                            if ( $carga_realizada == "") {
                                $carga_realizada = 0;
                            }
                            echo $carga_realizada;
                            ?>
                    </td>
                </tr>

                <?php endforeach; ?>

            </table>

            <br />
            <br />

            <!-- Tabela principal -->

            <table class="grid">
                <tr class="grid_head">
                    <td rowspan="2">Aluno</td>

                    <?php foreach($arr_diarios as $diario) : ?>

                    <td colspan="2"><?=$diario?></td>

                    <?php endforeach; ?>

                </tr>
                <tr class="grid_head">

                    <?php for($i = 0; $i < $num_diarios; $i++): ?>

                    <td>N</td>
                    <td>F</td>

                    <?php endfor; ?>

                </tr>

                <?php foreach($arr_alunosid as $alunoid) : ?>

                <tr class="grid_row">
                    <td>
                            <?php
                            echo $conn->get_one('SELECT nome FROM pessoas WHERE id = '.$alunoid);
                            ?>
                    </td>
                        <?php foreach($arr_diarios as $diario): ?>
                    <td>

                                <?php
                                foreach($arr_rel as $rel) {
                                    if($alunoid === $rel['matricula'] AND $diario === $rel['ref_disciplina_ofer'])
                                        echo $rel['nota_final'];
                                }
                                ?>

                    </td>
                    <td>

                                <?php
                                foreach($arr_rel as $rel) {
                                    if($alunoid === $rel['matricula'] AND $diario === $rel['ref_disciplina_ofer'])
                                        echo $rel['num_faltas'];
                                }
                                ?>

                    </td>
                        <?php endforeach; ?>
                </tr>

                <?php endforeach; ?>

            </table>
        </div>
    </body>
</html>