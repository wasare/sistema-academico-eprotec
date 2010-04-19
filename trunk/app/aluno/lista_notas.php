<?php

require_once('aluno.conf.php');
include_once('includes/topo.htm');

$aluno   = $user;
$periodo = $_GET["p"];
$curso   = $_GET["c"];

//$aluno   = '2223';
//$periodo = '0901';
//$curso   = '501';

$rs_pessoa   = $conn->get_one("SELECT nome FROM pessoas WHERE id = $aluno");
$rs_curso    = $conn->get_one("SELECT descricao FROM cursos WHERE id = $curso");
$rs_periodo  = $conn->get_one("SELECT descricao FROM periodos WHERE id = '$periodo'");

/*
$sql_diarios = "SELECT id FROM disciplinas_ofer
                WHERE ref_curso = '$curso' AND ref_periodo = '$periodo' AND is_cancelada = '0'";
*/

$sql_diarios_matriculados = "SELECT ref_disciplina_ofer 
                               FROM 
                                    matricula m 
                                LEFT OUTER JOIN 
                                    disciplinas_ofer o ON (m.ref_disciplina_ofer = o.id)
								WHERE
									(m.dt_cancelamento is null) AND
									m.ref_pessoa = $aluno AND
									m.ref_contrato IN ( 
											SELECT id 
												FROM 
													contratos 
												WHERE 
													ref_pessoa = $aluno AND 
													ref_curso = $curso
									) AND
									m.ref_motivo_matricula = 0 AND 
									o.is_cancelada = '0' AND
									o.ref_periodo = '$periodo'";


$rs_diarios = $conn->get_col($sql_diarios_matriculados);

$rs_diarios_matriculados = count($rs_diarios);



/*
foreach ($rs_diarios as $diario) {
    $str_in .= $diario[0] . ', ';
}

//Retorna o tamanho da string menos 2
$tam_str = strlen($str_in) - 2;
//Retorna os caracters comecando de Zero ate o valor especificado
$str_in = substr($str_in, 0, $tam_str);
*/
?>
<p>
    <strong>Aluno: </strong><?=$aluno?> - <?=$rs_pessoa?><br />
    <strong>Curso: </strong><?=$curso?> - <?=$rs_curso?><br />
    <strong>Per&iacute;odo: </strong><?=$rs_periodo?>
</p>
<table>
    <tr bgcolor="#545443">
        <th><font color="#ffffff">Disciplina</font></th>
        <th><font color="#ffffff">Nota 1</font></th>
        <th><font color="#ffffff">Nota 2</font></th>
        <th><font color="#ffffff">Nota 3</font></th>
        <th><font color="#ffffff">Nota 4</font></th>
        <th><font color="#ffffff">Nota 5</font></th>
        <th><font color="#ffffff">Nota 6</font></th>
        <th><font color="yellow">Reavalia&ccedil;&atilde;o</font></th>
        <th><font color="#ffffff">Nota Final</font></th>
        <th><font color="#ffffff">Nota distribuida</font></th>
        <th><font color="#ffffff">Faltas</font></th>
    </tr>
    <?php
    $count = 0;
    foreach ($rs_diarios as $diario) {

        //Exibe as principais informacoes do aluno a.ref_disciplina_ofer IN ($str_in) AND
        $sql_diario_info = "
        SELECT
            descricao_disciplina (get_disciplina_de_disciplina_of(a.ref_disciplina_ofer)),
            a.ref_disciplina_ofer, b.nome, b.ra_cnec, a.ordem_chamada,
            a.nota_final, c.ref_diario_avaliacao, c.nota, a.num_faltas,
            nota_distribuida(a.ref_disciplina_ofer) as \"total_distribuido\", fl_concluida
        FROM
            matricula a, pessoas b, diario_notas c, disciplinas_ofer d
        WHERE
            (a.dt_cancelamento is null) AND
            a.ref_disciplina_ofer =  ".$diario[0]." AND
            d.id =  ".$diario[0]." AND
            b.id = $aluno AND
            a.ref_pessoa = b.id AND
            b.ra_cnec = c.ra_cnec AND
            c.d_ref_disciplina_ofer = a.ref_disciplina_ofer AND
            a.ref_motivo_matricula = 0
        ORDER BY descricao_disciplina, ref_diario_avaliacao;";

        $rs_diarios_info = $conn->get_all($sql_diario_info);

        if ($rs_diarios_info[0][0] != '') {

            $nao_finalizada = ($rs_diarios_info[0][10] == 'f') ? '<strong>*</strong>' : '';
			$color =  ($color != '#ffffff') ? '#ffffff' : '#cce5ff';

            echo '<tr bgcolor="'.$color.'">';
            echo '<td>'.$rs_diarios_info[0][0] . $nao_finalizada .'</td>';

            foreach ($rs_diarios_info as $diario_info) {
                
                if ($diario_info[7] == '-1')
                    echo '<td align="center"> - </td>';
                else 
                    echo '<td align="center">'. number::numeric2decimal_br($diario_info[7],1) .'</td>';
            }
            echo '<td align="center">'.$rs_diarios_info[0][5].'</td>';
            echo '<td align="center">'.$rs_diarios_info[0][9].'</td>';
            echo '<td align="center">'.$rs_diarios_info[0][8].'</td>';
            echo '</tr>';
			$count++;
        }
    }
    ?>
</table>
<br />
(<strong>*</strong>) Disciplinas com lan&ccedil;amentos n&atilde;o finalizados, pass&iacute;vel de altera&ccedil;&otilde;es.
<?php if ($rs_diarios_matriculados > $count) : ?>
<br /><br />
<font color="red">
<strong>
Existem disciplinas matriculadas n&atilde;o exibidas. <br />
Estas disciplinas somente estaram dispon&iacute;veis quando o professor(a) iniciar o lan&ccedil;amento das notas. <br />
Qualquer d&uacute;vida entre em contato com seu professor(a) ou com a coordena&ccedil;&atilde;o do curso. <br />
</strong>
</font>
<?php endif; ?>
<br /><br />
<?php include_once('includes/rodape.htm'); ?>      

