<?php

require_once('../webdiario/webdiario.conf.php');

$sqlDispensas = 'SELECT id, ref_pessoa AS "Matricula", pessoa_nome(ref_pessoa) AS "Nome", ref_curso || \' - \' ||  get_curso_abrv(ref_curso) AS "Curso", descricao_disciplina(get_disciplina_de_disciplina_of(ref_disciplina_ofer)) AS "Disciplina", nota_final AS "Nota" FROM matricula where ref_motivo_matricula <> 0 AND nota_final < 50 ORDER BY 2,1;';

$sqlDispensas = 'SELECT id, ref_pessoa AS "Matricula", pessoa_nome(ref_pessoa) AS "Nome", ref_curso || \' - \' ||  get_curso_abrv(ref_curso) AS "Curso", descricao_disciplina(get_disciplina_de_disciplina_of(ref_disciplina_ofer)) AS "Disciplina", nota_final AS "Nota" FROM matricula WHERE id IN (150199,150162,150185,150194,150196,150197,151415,151416,150200,150202,150203,150204,150205,147727,147994,147995,150208,148506,148508,147778,147779,147774,147775,147776,148560,148561,148562,150212,148494,148288,148305,148105,151417,147724,147725,147726,150050,150068,147728,147731,147729,147730,147732,155298,148294,148297,148301) ORDER BY 2,1;';


$qry1 = consulta_sql($sqlDispensas);

if(is_string($qry1))
{
	echo $qry1;
	exit;
}

echo 'Matricula &nbsp;/&nbsp; Nome &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/ Curso&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/  Disciplina &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  / Nota&nbsp;&nbsp;&nbsp;  <br />  ';

$ref = '';

while($linha = pg_fetch_array($qry1))
{
    $ref_pessoa = $linha['Matricula'];
    $nome = $linha['Nome'];
    $curso = $linha['Curso'];
    $disciplina = $linha['Disciplina'];
    $nota = $linha['Nota'];
    $registro_id = $linha['id'];
    //$ref .= "$registro_id,";
    echo "$ref_pessoa&nbsp;&nbsp;&nbsp;$nome&nbsp;&nbsp;&nbsp;$curso&nbsp;&nbsp;&nbsp;$disciplina&nbsp;&nbsp;&nbsp;$nota";
    echo '&nbsp;&nbsp;&nbsp;<a href="altera_dispensa.php?id='. $registro_id .'">alterar nota</a><br />';
}

//echo $ref;

?>
