<?php

require_once('aluno.conf.php');
include_once('includes/topo.htm');

$sql_notas = '
SELECT DISTINCT
    c.descricao_disciplina, b.ra_cnec, a.ordem_chamada, a.nota_final,
    a.ref_curso, a.num_faltas, d.fl_digitada
FROM matricula a, pessoas b, disciplinas c, disciplinas_ofer d
WHERE
    a.ref_periodo = \'%s\' AND
    a.ref_disciplina IN (
        SELECT DISTINCT a.ref_disciplina
        FROM matricula a, disciplinas b
        WHERE
            a.ref_disciplina = b.id AND
            a.ref_periodo = \'%s\' AND
            a.ref_motivo_matricula = 0 AND
            a.ref_pessoa = %s
    ) AND
    a.ref_disciplina = c.id AND
    a.ref_pessoa = b.id AND
    a.ref_disciplina_ofer = d.id AND
    d.is_cancelada = \'0\' AND
    a.ref_curso = %s AND
    a.ref_pessoa = %s
ORDER BY c.descricao_disciplina;';

$aluno = $user;
$data = $DataInicial;

$periodo = $_GET["p"];
$curso = $_GET["c"];

$result_notas = $conn->get_all(sprintf($sql_notas,$periodo,$periodo,$aluno,$curso,$aluno));

$AlunoNome   = $conn->get_one('SELECT nome FROM pessoas WHERE id = '.$aluno.';');
$CursoNome   = $conn->get_one('SELECT abreviatura FROM cursos WHERE id = '.$curso.';');
$PeriodoNome = $conn->get_one('SELECT descricao FROM periodos WHERE id = \''.$periodo.'\';');

?>
<h2>Meu Aproveitamento</h2>
<strong>Registro: </strong><?=str_pad($aluno, 5, "0", STR_PAD_LEFT)?>&nbsp;&nbsp;
<strong>Aluno: </strong><?=$AlunoNome?>
<br />
<strong>Curso: </strong><?=$CursoNome?>&nbsp;&nbsp;
<strong>Per&iacute;odo: </strong><?=$PeriodoNome?>
<br /><br />
<table cellpadding="2" cellspacing="2" width="600">
    <tr bgcolor="#000000" >
        <td align="center"><font color="#FFFFFF"><b>Disciplina</b></font></td>
        <td align="center"><font color="#FFFFFF"><b>Nota</b></font></td>
        <td align="center"><font color="#FFFFFF"><b>Faltas</b></font></td>
    </tr>
    <?php
    $bgcolor = '';

    for($i = 0; $i < count($result_notas) ; $i++) {
        if ( ($i % 2) == 0 ) $bgcolor = "#FFFFFF";
        else $bgcolor = "#FFFFCC";

        if($result_notas[$i]['fl_digitada'] == 'f')
            $encerrada = '<font color="red" size="-2"><strong>*</strong></font>';
        else $encerrada = '';

        if (empty($result_notas[$i]["num_faltas"])) $faltas = ' - ';
        else $faltas = $result_notas[$i]["num_faltas"];

        echo '<tr bgcolor="'.$bgcolor.'"><td>&nbsp;&nbsp;'.$result_notas[$i]["descricao_disciplina"].'&nbsp;&nbsp;'.$encerrada.'</td>';
        echo '<td>&nbsp;&nbsp;'.$result_notas[$i]["nota_final"].'&nbsp;&nbsp;</td>';
        echo '<td>&nbsp;&nbsp;'. $faltas .'&nbsp;&nbsp;</td></tr>';
    }

    ?>
</table>
<br />
<font color="red" size="-2">
    (<strong>*</strong>) disciplinas com notas
    parciais, ainda poder&aacute; sofrer altera&ccedil;&otilde;es!
</font>
<br />
<a href="lista_cursos.php">Voltar</a>

<?php include_once('includes/rodape.htm'); ?>
