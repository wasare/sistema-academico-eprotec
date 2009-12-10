<?php

/**
* Funcoes usadas pelo web diário
* @author Wanderson S. Reis
* @version 1
* @since 30-09-2009
**/

require_once(dirname(__FILE__) .'/../app/setup.php');


// CONEXAO ABERTA PARA TRABALHAR COM TRANSACAO (NÃO PERSISTENTE)
$conn = new connection_factory($param_conn,FALSE);



function papeleta_header($diario_id)
{
	global $conn;

	$sql9 = "SELECT DISTINCT
          a.id || ' - ' || a.descricao as cdesc,
          b.id || ' - ' || b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
          c.descricao as perdesc,
          g.nome_campus,
          d.ref_curso,
          f.nome
         FROM
          cursos a LEFT OUTER JOIN disciplinas_ofer d ON (a.id = d.ref_curso) LEFT OUTER JOIN disciplinas b ON (d.ref_disciplina = b.id) LEFT OUTER JOIN periodos c ON (c.id = d.ref_periodo) LEFT OUTER JOIN disciplinas_ofer_prof e ON (e.ref_disciplina_ofer = d.id) LEFT OUTER JOIN pessoas f ON (e.ref_professor = f.id)  LEFT OUTER JOIN campus g ON (d.ref_campus =  g.id)
        WHERE
          d.id = $diario_id AND
          d.is_cancelada = '0'
        ORDER BY f.nome;";

    $qry9 = $conn->get_all($sql9);

	$profs = count($qry9);
	
	foreach( $qry9 as $linha9 )
    {
        $curso = $linha9["cdesc"];
        $disciplina  = $linha9["descricao_extenso"];
        $periodo   = $linha9["perdesc"];
        $ref_curso   = $linha9["ref_curso"];
        $prof[]   = $linha9["nome"];
        $campus = $linha9['nome_campus'];
    }

	$ret = '';
    $ret .= '<input type="hidden" name="curso" id="curso" value="'. $ref_curso .'" />';

    $ret = 'Curso: <b>'. $curso .'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
    $ret .= 'Disciplina: <b>'. $disciplina .'</b><br>';
    $ret .= 'Per&iacute;odo: <b>'. $periodo .'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
	$ret .= 'Campus: <b>'. $campus .'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />';
    $ret .= 'Professor(a): ';

	$i = 1;
    foreach($prof as $p) {

        $ret .= '<b>'. $p .'</b><br />';

        if($profs != $i) {
            $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';//&nbsp;';

        }
		$i++;
    }
    return $ret;	
} 

function get_curso($diario_id) {

	global $conn;
 
	$sql9 = "SELECT
             d.ref_curso
             FROM
              disciplinas_ofer d  where
              d.id = $diario_id;";

    return $conn->get_one($sql9);
}

function get_curso_tipo($diario_id) {

	global $conn;

    // CONSULTA O NIVEL DO CURSO
    $sql = 'SELECT
                ref_tipo_curso
                        FROM
                            cursos c, disciplinas_ofer d
                        WHERE
                             c.id = ref_curso AND
                         d.id = '. $diario_id .';';

    return $conn->get_one($sql);

}

function get_disciplina($diario_id) {

    global $conn;

    $sql9 = "SELECT
             d.ref_disciplina
             FROM
              disciplinas_ofer d  where
              d.id = $diario_id;";

    return $conn->get_one($sql9);
}

function get_ano_periodo($periodo) {

	global $conn;

    $qry1 = "SELECT
					 to_char(dt_inicial, 'YYYY'), to_char(dt_final, 'YYYY')
                FROM 
					periodos WHERE id = '". $periodo ."';";

    return array_unique($conn->get_row($qry1));
}


function is_inicializado($diario_id) {

	global $conn;

    $grupo = ("%-%-%-" . $diario_id);

    $sql1 = "SELECT
         COUNT(grupo)
         FROM diario_formulas
         WHERE
         grupo ILIKE '$grupo';";

    $num_reg = $conn->get_one($sql1);

    if ($num_reg == 6)
        return TRUE;
    else
        return FALSE;
}

function is_finalizado($diario_id) {

	global $conn;
  	$sql = 'SELECT
                  fl_digitada
                     FROM
                        disciplinas_ofer d
                     WHERE
                        d.id = '. $diario_id .';';

  	$diario = $conn->get_one($sql);

    if ($diario == 't')
        return TRUE;
    else
        return FALSE;
}

function ini_diario($ofer) {

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
    
    $curso = get_curso($ofer);

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

	$qry = $conn->get_all($qryNotas);

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

// function adiciona/exclui falta
function falta($ref_pessoa, $diario_id, $qtde, $par1, $qry='BEGIN;')
{
	global $conn;

	$sqlf = "SELECT
                    count(ra_cnec) AS num_faltas
                FROM
                    diario_chamadas a
                WHERE
                    (a.ref_disciplina_ofer = $diario_id) AND
                    (ra_cnec = '$ref_pessoa');";


   $total_faltas = $conn->get_one($sqlf);


   if($par1 == "SOMA")
      $total_faltas = $total_faltas + $qtde;

   if($par1 == "SUB")
      $total_faltas = $total_faltas - $qtde;

   $sqlfalta = $qry . "UPDATE
                  matricula
               SET
                  num_faltas = $total_faltas
               WHERE
                  ref_pessoa = $ref_pessoa AND
                  ref_disciplina_ofer = $diario_id;";

	$sqlfalta .= 'COMMIT;';

   $conn->Execute($sqlfalta);

}



?>