<?php

/**
* Funcoes usadas pelo web diário
* @author Wanderson S. Reis
* @version 1
* @since 30-09-2009
**/

require_once(dirname(__FILE__) .'/../app/setup.php');

$conn = new connection_factory($param_conn);



function papeleta_header($diario_id)
{
	global $conn;

	$sql9 = "SELECT DISTINCT
          a.id || ' - ' || a.descricao as cdesc,
          b.id || ' - ' || b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
          c.descricao as perdesc,
          d.ref_curso,
          f.nome
         FROM
          cursos a LEFT OUTER JOIN disciplinas_ofer d ON (a.id = d.ref_curso) LEFT OUTER JOIN disciplinas b ON (d.ref_disciplina = b.id) LEFT OUTER JOIN periodos c ON (c.id = d.ref_periodo) LEFT OUTER JOIN disciplinas_ofer_prof e ON (e.ref_disciplina_ofer = d.id) LEFT OUTER JOIN pessoas f ON (e.ref_professor = f.id)
        WHERE
          d.id = $diario_id AND
          d.is_cancelada = '0'
        ORDER BY f.nome;";

    $qry9 = $conn->adodb->getAll($sql9);

	$profs = count($qry9);
	
	foreach( $qry9 as $linha9 )
    {
        $Disc['curso'] = $linha9["cdesc"];
        $Disc['disc']  = $linha9["descricao_extenso"];
        $Disc['periodo']   = $linha9["perdesc"];
        $Disc['ref_curso']   = $linha9["ref_curso"];
        $Disc['prof'][]   = $linha9["nome"];
    }

	$ret = '';
    $ret .= '<input type="hidden" name="curso" id="curso" value="'.$Disc['ref_curso'].'" />';

    $ret = 'Curso: <b>'.$Disc['curso'].'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
    $ret .= 'Disciplina: <b>'.$Disc['disc'].'</b><br>';
    $ret .= 'Per&iacute;odo: <b>'.$Disc['periodo'].'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
    $ret .= 'Professor(a): ';

    for($p = 0 ; $p < $profs; $p++) {

        $ret .= '&nbsp;&nbsp;<b>'.$Disc['prof'][$p].'</b><br />';

        if(($profs - 1) > $p) {
            $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
    }

    return $ret;	

} 

function getCurso($p,$d,$o) {

	global $conn;
 
	$sql9 = "SELECT
             d.ref_curso
             FROM
              cursos a,
              disciplinas b,
              periodos c,
              disciplinas_ofer d  where
              d.ref_periodo = '$p' AND
              b.id = '$d' AND
              c.id = '$p' AND
              d.id = '$o' AND
              a.id = d.ref_curso;";

    return $conn->adodb->getOne($sql9);
}


function is_inicializado($ofer) {

	global $conn;

    $grupo = ("%-%-%-" . $ofer);

    $sql1 = "SELECT
         grupo
         FROM diario_formulas
         WHERE
         grupo ILIKE '$grupo';";

    $qry1 = $conn->adodb->getCol($sql1);

	$num_reg = count($qry1);

    if ($num_reg == 6)
        return TRUE;
    else
        return FALSE;
}

function inicializaDiario($ofer) {

	global $conn;
	
	// RECUPERA INFORMACOES DO DIARIO
    $qryDisc = " SELECT DISTINCT
                prof.ref_professor, o.ref_disciplina, o.ref_periodo 
                FROM 
                disciplinas_ofer o, disciplinas_ofer_prof prof
                WHERE
                 o.id = " . $ofer . " AND 
                 o.is_cancelada = '0' AND
                 o.id = prof.ref_disciplina_ofer ;";


    $diario_info = $conn->adodb->getAll($qryDisc);

    if($diario_info === FALSE) {

        envia_erro($qryDisc);
        exit;
    }
    else {

        // A DISCIPLINA EXISTE^M

        if(count($diario_info) > 0) {

            foreach($diario_info as $linha)
            {
                $disc = @$linha['ref_disciplina'];
                $periodo = @$linha['ref_periodo'];
                $prof = @$linha['ref_professor'];
            }

        } // ^ A DISCIPLINA EXISTE
    }

    $grupo = ($prof . "-" . $periodo . "-" . $disc . "-" . $ofer);
    $grupo_novo = ("%-%-%-" . $ofer); 
    
    $curso = getCurso($periodo,$disc,$ofer);

    $ret = TRUE;

    // PASSO 1
    $numprovas = 6;
    
    // PASSO 2
    $formula = '';
    for ($cont = 1; $cont <= $numprovas; $cont++)
    {
        $prova[] = 'Nota '.$cont;

        if($cont == 1)
        {
            $formula .= 'P'.$cont;
        }
        else
        {
            $formula .= '+P'.$cont;
        }
    }    
    
    // PASSO 3
    $sqldel = "BEGIN; DELETE FROM diario_formulas WHERE grupo ILIKE '$grupo_novo';";
    $sqldel .= "DELETE FROM diario_notas WHERE rel_diario_formulas_grupo ILIKE '$grupo_novo'; COMMIT;";

    $ret =  $conn->Execute($sqldel);

    reset($prova);

    $sql1 = 'BEGIN;';

    while(list($index,$value) = each($prova))
    {
        $descricao_prova = $prova[$index];
        $num_prova = ($index+1);
        $sql1 .= "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values($prof,'$periodo','$disc',$num_prova,'$descricao_prova','$formula','$grupo');";

    }

    $sql1 .= 'COMMIT;';

    $ret = $conn->Execute($sql1);

	// PASSO 4 - PROCESSA CRIA REGISTROS DE ACORDO COM A FORMULA
    $qryNotas = 'SELECT
        m.ref_pessoa, id_ref_pessoas
        FROM
            matricula m
        LEFT JOIN (
                SELECT DISTINCT
                d.id_ref_pessoas
            FROM
                diario_notas d
            WHERE
                d.d_ref_disciplina_ofer = ' . $ofer . '
              ) tmp
        ON ( m.ref_pessoa = id_ref_pessoas )
        WHERE
            m.ref_disciplina_ofer = ' . $ofer . ' AND
            id_ref_pessoas IS NULL AND
            (m.dt_cancelamento is null) AND
            (m.ref_motivo_matricula = 0)
        ORDER BY
                id_ref_pessoas;';

	$qry = $conn->adodb->getAll($qryNotas);

    $qryDiario = "BEGIN;";

	foreach($qry as $registro)
    {
		$ref_pessoa = $registro['ref_pessoa'];

            for($i = 1 ; $i <= $numprovas; $i++)
            {
                $qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
                $qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
                $qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
                $qryDiario .= ' rel_diario_formulas_grupo)';
                $qryDiario .= " VALUES($ref_pessoa,$i,0,0,$ref_pessoa,'$periodo',$curso,";
                $qryDiario .= " $ofer,'$grupo');";
            }

                $qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
                $qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
                $qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
                $qryDiario .= ' rel_diario_formulas_grupo)';
                $qryDiario .= " VALUES($ref_pessoa,7,-1,0,$ref_pessoa,'$periodo',$curso,";
                $qryDiario .= " $ofer,'$grupo');";
    }


    $qryDiario .= "COMMIT;";

    $ret = $conn->Execute($qryDiario);

	return $ret;
}




?>
