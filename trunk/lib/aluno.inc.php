<?php

/**
* Funcoes que verificam a situacao do aluno
* @author Wanderson S. Reis
* @version 1
* @since 15-05-2009
**/

//Arquivos de configuracao e biblioteca
header("Cache-Control: no-cache");
require_once( dirname(__FILE__) .'/common.php');
require_once(dirname(__FILE__) .'/../configuracao.php');
require_once(dirname(__FILE__) .'/adodb/adodb.inc.php');


//Criando a classe de conexao ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexao persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


function verificaReprovacaoPorFaltas($aluno_id,$diarios)
{
	global $Conexao;

	$diarios_matriculados = count($diarios);

    $diarios_reprovados = 0;

    foreach($diarios as $id)
    {
        $diario_id = $id['diario'];
		
    	// -- Verifica se foi reprovado por faltas
        $sqlDisciplina = "
         SELECT DISTINCT
            COUNT(o.id)
        FROM
                matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
        WHERE
                m.ref_pessoa = p.id AND
                p.id = '$aluno_id' AND
                m.ref_disciplina_ofer = o.id AND
                d.id = o.ref_disciplina AND
                o.is_cancelada = 0 AND
                s.id = o.ref_periodo AND
                ( m.num_faltas > ( 
                                  	( SELECT
                                        	SUM(CAST(flag AS INTEGER)) AS carga
                                    	FROM
                                        	diario_seq_faltas
                                    	WHERE
                                        	ref_disciplina_ofer = $diario_id ) * 0.25 
									) 
									 
				) AND
				o.id = $diario_id; ";

		$RsDisciplina = $Conexao->Execute($sqlDisciplina);
		$diarios_reprovados += $RsDisciplina->fields[0];
	}

    if ($diarios_reprovados > $diarios_matriculados )
         return TRUE;
    else
         return FALSE;


} 

function verificaAprovacao($aluno_id,$curso_id,$diario_id)
{
    global $Conexao;
      // -- Verifica se foi aprovado ou dispensado nesta disciplina ou em disciplina equivalente a qualquer tempo
        $sqlDisciplina = "
        SELECT DISTINCT
            o.id AS diario
        FROM
                matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
        WHERE
                m.ref_pessoa = p.id AND
                p.id = '$aluno_id' AND
                m.ref_disciplina_ofer = o.id AND
                d.id = o.ref_disciplina AND
                o.is_cancelada = 0 AND
                s.id = o.ref_periodo AND
                ( d.id = get_disciplina_de_disciplina_of('$diario_id') OR 
                            d.id IN ( 
                                        select 
                                                distinct ref_disciplina_equivalente 
                                        from disciplinas_equivalentes 
                                        where ref_disciplina = get_disciplina_de_disciplina_of('$diario_id') and ref_curso = '$curso_id'  
                                    )
                ) AND
                ( m.nota_final >= 60 OR ref_motivo_matricula IN (2,3,4) ); ";

        $RsDisciplina = $Conexao->Execute($sqlDisciplina);
        $diarios_matriculados = $RsDisciplina->GetAll();

        if (count($diarios_matriculados) > 0 )
        {
            if (verificaReprovacaoPorFaltas($aluno_id,$diarios_matriculados))
                    return FALSE;
            else
                    return TRUE;
        }
        else
            return FALSE;


   // ^ Verifica se o aluno ja foi aprovado ou dispensado nesta mesma disciplina a qualquer tempo ^ //
}

function verificaRequisitos($aluno_id,$curso_id,$diario_id)
{
	global $Conexao;
    // -- Verifica se o aluno ja eliminou os pre-requisitos
    // CONSIDERA SOMATORIO FINAL DE NOTA E FALTAS E DISPENSA
  	// existe  pre-requisito?
    $sqlPreRequisito = "
    SELECT DISTINCT
          ref_disciplina_pre
    FROM
          pre_requisitos 
    WHERE
          ref_disciplina IN ( select get_disciplina_de_disciplina_of('$diario_id') ); ";

    $RsPreRequisito = $Conexao->Execute($sqlPreRequisito);
    $pre_requisitos = $RsPreRequisito->GetAll();

    $total_requisitos = count($pre_requisitos);
    $requisitos_matriculados = array();
    if (count($total_requisitos) > 0) 
	{
		foreach($pre_requisitos as $req)
		{
			$disc_req = $req['ref_disciplina_pre'];
        	// foi aprovado ou dispensado do pre-requisito? considera disciplina equivalente também
        	$sqlPreRequisito1 = "
        			SELECT DISTINCT
        				o.id AS diario
        			FROM
            			matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
        			WHERE
             			m.ref_pessoa = p.id AND
                		p.id = '$aluno_id' AND
                		m.ref_disciplina_ofer = o.id AND
                		d.id = o.ref_disciplina AND
                		o.is_cancelada = 0 AND
                		s.id = o.ref_periodo AND
                		( d.id = '$disc_req' OR d.id IN ( select distinct ref_disciplina_equivalente 
															from disciplinas_equivalentes 
														where ref_disciplina = '$disc_req' and ref_curso = '$curso_id'  ) ) AND 
        	       		( m.nota_final >= 60 OR ref_motivo_matricula IN (2,3,4) ); ";
        				$RsPreRequisito1 = $Conexao->Execute($sqlPreRequisito1);
        				$requisitos_matriculados = array_merge($requisitos_matriculados,$RsPreRequisito1->GetAll());
						//$requisitos_matriculados = $RsPreRequisito1->GetAll();
		 }
    }
    
	if (count($requisitos_matriculados) > 0)
    {
    	if (verificaReprovacaoPorFaltas($aluno_id,$requisitos_matriculados))
            return TRUE;
      	else
            return FALSE;
    }
    else
	    if (count($requisitos_matriculados) >= $total_requisitos)
			return FALSE;
		else
			return TRUE;
}

function verificaEquivalencia($curso_id,$diario_id)
{
    global $Conexao;
    // -- Verifica se a disciplina é equivalente para o curso matriculado
    $sqlDisciplina = "
					SELECT 
                          DISTINCT 
								ref_disciplina_equivalente 
                        FROM
							    disciplinas_equivalentes 
                        WHERE 
                             ref_disciplina = get_disciplina_de_disciplina_of('$diario_id') AND ref_curso = '$curso_id';";
                                    
    $RsDisciplina = $Conexao->Execute($sqlDisciplina);
    $equivalentes = $RsDisciplina->GetAll();

    if (count($equivalentes) > 0 )
		return TRUE;
    else
        return FALSE;
}

function verificaPeriodo($periodo_id)
{
    global $Conexao;
    // -- Verifica é um periodo em andamento
    $sqlPeriodo = "
                    SELECT 
                          dt_final 
                        FROM
                             periodos 
                        WHERE 
                             id = '$periodo_id';";

    $data_final_periodo = strtotime($Conexao->GetOne($sqlPeriodo));
    $data_atual = strtotime(date('Y-m-d'));

    if ( $data_atual > $data_final_periodo )
        return TRUE;
    else
        return FALSE;
}

?>
