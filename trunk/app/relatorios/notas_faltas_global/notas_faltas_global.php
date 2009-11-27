<?php
/*
 * Arquivo com as configuracoes iniciais
 */
require_once("../../../app/setup.php");
require_once("../../../core/reports/header.php");

$header  = new header($param_conn);

/**
 * Parametros do formulario
 */
$periodo = $_POST['periodo'];;
$campus  = $_POST['campus'];
$curso   = $_POST['curso'];
$turma   = $_POST['turma'];

if(empty($periodo) or empty($campus) or empty($curso) or empty($turma) or
    !isset($periodo) or !isset($campus) or !isset($curso) or !isset($turma) ) {
    echo '<script language="javascript">window.alert("Nenhum diario a ser exibido!");</script>';
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
    exit;
}

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
    o.id AS diario,
    d.id,
    d.descricao_disciplina,
    d.descricao_extenso,
    d.carga_horaria, 
    p.id || ' - ' || p.nome AS prof,
    o.fl_concluida,
    o.fl_digitada
FROM
    disciplinas d, disciplinas_ofer o, disciplinas_ofer_prof dp, pessoas p
WHERE
    o.ref_periodo = '".$periodo."' AND
    d.id = o.ref_disciplina AND
    dp.ref_disciplina_ofer = o.id AND
    dp.ref_professor IS NOT NULL AND
    p.id = dp.ref_professor AND
    o.turma = '".$turma."' AND
    o.ref_curso = ".$curso." AND
    is_cancelada = '0'
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

$arr_diarios  = array();
$arr_alunosid = array();

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
        <style>
            table{
                font-size:11px;
            }
        </style>
    </head>
    <body>
        <div align="center">
            <?php echo $header->get_empresa($PATH_IMAGES); ?>
            <br /><br />
            <h2>Resumo de notas e faltas de curso no per&iacute;odo</h2>
        </div>
        <strong>Per&iacute;odo:</strong> <?php echo $desc_periodo; ?><br />
        <strong>Curso:</strong> <?php echo $desc_curso[0]; ?><br />
        <strong>Turma:</strong> <?php echo $turma; ?><br />
        <strong>Campus:</strong> <?php echo $desc_curso[1]; ?><br />
        <br />

        <b>LEGENDA</b>

        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"><strong>C&oacute;d. Di&aacute;rio</strong></td>
                <td align="center"><strong>Descri&ccedil;&atilde;o</strong></td>
                <td align="center"><strong>Professor(a)</strong></td>
                <td align="center"><strong>CH Prevista</strong></td>
                <td align="center"><strong>CH Realizada</strong></td>
                <td align="center"><strong>N Distribuida</strong></td>
                <td align="center"><strong>Situa&ccedil;&atilde;o</strong></td>
            </tr>

            <?php foreach($arr_legenda as $legenda) : ?>

            <tr>
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
                <td align="center">
                        <?php
                        //Nota distribuida
                        $sql_distribuida = "
                            SELECT SUM(nota_distribuida) AS nota
                            FROM  diario_formulas
                            WHERE  grupo ILIKE '%-".$legenda['diario']."' ;";

                        $nota_distribuida = $conn->get_one($sql_distribuida);

                        if ( $nota_distribuida == "") {
                            $nota_distribuida = 0;
                        }
                        echo $nota_distribuida;
                        ?>
                </td>
                <td>
                    <?php
                    //Situacao do diario
                    if($legenda['fl_concluida'] == 't') {
                        echo 'Finalizado';
                    }
                    elseif($legenda['fl_concluida'] == 'f' AND $legenda['fl_digitada'] == 't'){
                        echo 'Conclu&iacute;da';
                    }
                    elseif($legenda['fl_concluida'] == 'f' AND $legenda['fl_digitada'] == 'f') {
                        echo 'Aberto';
                    }
                    ?>
                </td>
            </tr>

            <?php endforeach; ?>

        </table>
        <br />
        <br />
        <strong>Aten&ccedil;&atilde;o:</strong>O relat&oacute;rio abaixo exibe somente os di&aacute;rio concluídos.
        <br />
        <br />
        <!-- Tabela principal -->

        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td rowspan="2">
                    <strong>Aluno</strong>
                </td>

                <?php foreach($arr_diarios as $diario) : ?>

                <td colspan="2"><strong><?=$diario?></strong></td>

                <?php endforeach; ?>

            </tr>
            <tr>

                <?php for($i = 0; $i < $num_diarios; $i++): ?>

                <td><strong>N</strong></td>
                <td><strong>F</strong></td>

                <?php endfor; ?>

            </tr>

            <?php foreach($arr_alunosid as $alunoid) : ?>

            <tr valign="top">
                <td width="300">
                        <?php echo $conn->get_one('SELECT nome FROM pessoas WHERE id = '.$alunoid); ?>
                </td>
                    <?php foreach($arr_diarios as $diario): ?>
                <td>
                            <?php
                            foreach($arr_rel as $rel) {
                                if($alunoid === $rel['matricula'] AND $diario === $rel['ref_disciplina_ofer'])
                                    echo $rel['nota_final'];
                            }
                            ?>&nbsp;
                </td>
                <td>
                            <?php
                            foreach($arr_rel as $rel) {
                                if($alunoid === $rel['matricula'] AND $diario === $rel['ref_disciplina_ofer'])
                                    echo $rel['num_faltas'];
                            }
                            ?>&nbsp;
                </td>
                    <?php endforeach; ?>
            </tr>

            <?php endforeach; ?>

        </table>

    </body>
</html>