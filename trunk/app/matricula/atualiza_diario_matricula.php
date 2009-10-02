<?php

require_once('../../setup.php');

$conn = new connection_factory($param_conn);


function envia_erro($msg) {

	// ENVIA EMAIL PARA O ADMINISTRADOR
    $mail_header = "FROM: webmaster@cefetbambui.edu.br";
    @mail( 'webmaster@cefetbambui.edu.br', '[ Erro ao atualizar diario ] ', $msg, $mail_header);

}

function sa_getCurso($p,$d,$o) {

	global $conn;

	// VAR CONSULTA
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
	d.id = $o AND
	a.id = d.ref_curso;";

	$curso = $conn->adodb->getOne($sql9);

	if($curso === FALSE) {

		envia_erro($curso);
		exit;
	}

	return $curso;

}


function sa_calcNotaReavaliacao($o,$nd,$ne) {

	global $conn;

	// CONSULTA O NIVEL DO CURSO
	$sqlCursoTipo = 'SELECT
                     ref_tipo_curso
                     FROM
                     cursos c, disciplinas_ofer d
                     WHERE
                     c.id = ref_curso AND
                     d.id = '.$o.';';

	$CursoTipo = $conn->adodb->getOne($sqlCursoTipo);

	if($CursoTipo === FALSE)
	{
		envia_erro($sqlCursoTipo);
		exit;
	}

	/*
	 1     Tecnico
	 2     Graduacao Tecnologica
	 4     Pos-Graduacao Latu-Sensu
	 5     Qualificacao
	 6     Bacharelado
	 7     Tecnico Integrado
	 8     Tecnico - EJA
	 9     Tecnico Integrado - EJA
	 10    Licenciatura
	 */

	if($CursoTipo == 2 || $CursoTipo == 4 || $CursoTipo == 6 || $CursoTipo == 10)
	{
		return  (($nd + $ne) / 2);
	}
	else
	{
		return $ne;
	}

}


function atualiza_matricula($aluno,$getofer){

	global $conn;

	// RECUPERA INFORMACOES DO DIARIO
	$qryDisc = " SELECT DISTINCT
				prof.ref_professor, o.ref_disciplina, o.ref_periodo 
				FROM 
				disciplinas_ofer o, disciplinas_ofer_prof prof
            	WHERE
                 o.id = " . $getofer . " AND 
				 o.is_cancelada = '0' AND
				 o.id = prof.ref_disciplina_ofer ;";


	$diario_info = $conn->adodb->getAll($qryDisc);

	if($diario_info === FALSE) {

		envia_erro($qryDisc);
		exit;
	}
	else {

		// A DISCIPLINA EXISTE

		if(count($diario_info) > 0) {

			foreach($diario_info as $linha)
			{
				$getdisciplina = @$linha['ref_disciplina'];
				$getperiodo = @$linha['ref_periodo'];
				$id = @$linha['ref_professor'];
			}

		} // ^ A DISCIPLINA EXISTE
	}

	$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);

	$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);


	$flag_pendencia = 0;

	$qryDiario = 'BEGIN;';

	$getcurso = sa_getCurso($getperiodo,$getdisciplina,$getofer);


	// VERIFICA PENDENCIAS RELACIONADAS AO LANCAMENTO DE NOTAS
	$sql1 = "SELECT
	COUNT(grupo)
	FROM diario_formulas
	WHERE
	grupo ILIKE '$grupo_novo';";


	$num_formula = $conn->adodb->getOne($sql1);

	if($num_formula === FALSE)
	{
		envia_erro($sql1);
		exit;
	}

	if($num_formula == 6) {

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
				d.d_ref_disciplina_ofer = ' . $getofer . ' AND
                id_ref_pessoas = ' . $aluno . '
		      ) tmp 
			ON ( m.ref_pessoa = id_ref_pessoas ) 
	    	WHERE 
		        m.ref_disciplina_ofer = ' . $getofer . ' AND 
		        m.ref_pessoa = ' . $aluno . ' AND
		        id_ref_pessoas IS NULL  AND
			    (m.dt_cancelamento is null) AND
			    (m.ref_motivo_matricula = 0)
	        ORDER BY id_ref_pessoas;';

		$alunos_sem_registro_notas = $conn->adodb->getAll($qryNotas);

		if($alunos_sem_registro_notas === FALSE)
		{
			envia_erro($qryNotas);
			exit;
		}

		$num_registros = count($alunos_sem_registro_notas);

		$num_notas = 6;

		if ($num_registros > 0)
		{
			foreach($alunos_sem_registro_notas as $registro)
			{
				$ref_pessoa = $registro['ref_pessoa'];

				for($i = 1 ; $i <= $num_notas; $i++)
				{
					$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
					$qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
					$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
					$qryDiario .= ' rel_diario_formulas_grupo)';
					$qryDiario .= " VALUES($ref_pessoa,$i,0,0,$ref_pessoa,'$getperiodo',$getcurso,";
					$qryDiario .= " $getofer,'$grupo');";
				}

				$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
				$qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
				$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
				$qryDiario .= ' rel_diario_formulas_grupo)';
				$qryDiario .= " VALUES($ref_pessoa,7,-1,0,$ref_pessoa,'$getperiodo',$getcurso,";
				$qryDiario .= " $getofer,'$grupo');";
			}

			$flag_pendencia = 1;
			// $msg .= $num_registros .' alunos com problemas no lancamento de notas\n';

		}

		// ^ VERIFICA PENDENCIAS RELACIONADAS AO LANCAMENTO DE NOTAS ^


		// VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE FALTAS
		$sqlDiarioFaltas = "
		SELECT * FROM
		(
		SELECT DISTINCT
		registro_id,
		CASE
		WHEN num_faltas IS NULL THEN '0'
		ELSE num_faltas
		END AS num_faltas,
		CASE
		WHEN faltas_diario IS NULL THEN '0'
		ELSE faltas_diario
		END AS faltas_diario
		FROM
		(
		SELECT DISTINCT
		CAST(a.ref_pessoa AS INTEGER) AS registro_id, a.num_faltas
		FROM
		matricula a
		WHERE
		a.ref_periodo = '$getperiodo' AND
		a.ref_disciplina_ofer = $getofer AND
		a.ref_pessoa = $aluno
		) AS T1
		FULL OUTER JOIN
		(
		SELECT
		CAST(a.ra_cnec AS INTEGER) AS registro_id, count(a.ra_cnec) AS faltas_diario
		FROM
		diario_chamadas a
		WHERE
		(a.ref_periodo = '$getperiodo') AND
		(a.ref_disciplina_ofer = $getofer) AND
		a.ra_cnec = $aluno
		GROUP BY ra_cnec
		) AS T4

		USING (registro_id)

		) AS TB

		WHERE
		(num_faltas <> faltas_diario);";

		$diario_faltas = $conn->adodb->getAll($sqlDiarioFaltas);


		if($diario_faltas === FALSE)
		{
			envia_erro($sqlDiarioFaltas);
			exit;
		}

		$numFalta = count($diario_faltas);


		if ($numFalta != 0) {

			foreach($diario_faltas as $registro)
			{
				$ref_pessoa = $registro['registro_id'];
				$faltas = $registro['faltas_diario'];
				$num_faltas = $registro['num_faltas'];

				$qryDiario .= ' UPDATE matricula SET num_faltas = '. $faltas;
				$qryDiario .= ' WHERE ref_pessoa = '. $ref_pessoa .' AND';
				$qryDiario .= " ref_periodo = '$getperiodo' AND ";
				$qryDiario .= ' ref_disciplina_ofer = '. $getofer .';';
			}

			$flag_pendencia = 1;
			$msg_atualiza .= 'Atualizado somat&oacute;rio de faltas\n';
			// $msg .= $numFalta . ' alunos com problemas no somatorio de faltas\n';

		}
		// ^ VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE FALTAS ^


		// VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE NOTAS *** CONSIDERA NOTA EXTRA ***
		$sqlNotas = "
		SELECT DISTINCT
		registro_id, nota_diario, nota_extra, nota_final
		FROM
		(
		SELECT
		DISTINCT
		CAST(b.id AS INTEGER) AS registro_id, CAST(SUM(c.nota) AS NUMERIC) AS nota_diario
		FROM
		matricula a, pessoas b, diario_notas c
		WHERE
		a.ref_periodo = '$getperiodo' AND
		a.ref_disciplina_ofer = $getofer AND
		b.ra_cnec = c.ra_cnec AND
		c.d_ref_disciplina_ofer = $getofer AND
		a.ref_pessoa = b.id AND
		b.ra_cnec = $aluno  AND
		ref_diario_avaliacao < 7
		GROUP BY b.id
		) AS T1

		INNER JOIN (
		SELECT DISTINCT
		CAST(b.id AS INTEGER) AS registro_id, CAST(c.nota AS NUMERIC) AS nota_extra
		FROM
		matricula a, pessoas b, diario_notas c
		WHERE
		a.ref_periodo = '$getperiodo' AND
		a.ref_disciplina_ofer = $getofer AND
		b.ra_cnec = c.ra_cnec AND
		c.d_ref_disciplina_ofer = $getofer AND
		a.ref_pessoa = b.id AND
		b.ra_cnec = $aluno  AND
		ref_diario_avaliacao = 7
		) AS T2

		USING (registro_id)
		INNER JOIN

		(
		SELECT DISTINCT
		CAST(a.ref_pessoa AS INTEGER) AS registro_id, CAST(a.nota_final AS NUMERIC)
		FROM
		matricula a
		WHERE
		a.ref_periodo = '$getperiodo' AND
		a.ref_disciplina_ofer = $getofer AND
		a.ref_pessoa = $aluno
		) AS T3

		USING (registro_id)

		WHERE
		nota_diario <> nota_final;";

		$diario_notas = $conn->adodb->getAll($sqlNotas);

		if($diario_faltas === FALSE)
		{
			envia_erro($sqlNotas);
			exit;
		}

		$numNotas = count($diario_notas);


		if ($numNotas != 0) {

			$numNotas = 0;

			foreach($diario_notas as $registro)
			{
			
				$ref_pessoa = $registro['registro_id'];
				$nota_diario = $registro['nota_diario'];
				$nota_final = $registro['nota_final'];
				$nota_extra = $registro['nota_extra'];

					
				if($nota_extra == -1 && $nota_diario != $nota_final) {
					// NOTA EXTRA NAO LANCADA E SOMATORIO ERRADO
					$qryDiario .= ' UPDATE matricula SET nota_final = '. $nota_diario;
					$qryDiario .= ' WHERE ref_pessoa = '. $ref_pessoa .' AND';
					$qryDiario .= " ref_periodo = '$getperiodo' AND ";
					$qryDiario .= ' ref_disciplina_ofer = '. $getofer .';';

					$numNotas++;
				}
				else {
					// NOTA EXTRA LANCADA
					if($nota_diario < 60 || $nota_final < 60) {

						// CALCULA NOTA FINAL E VERIFICA NOTA EXTRA SOMENTE COM NOTA < 60
						// NOTA < 60 RATIFICA O LANCAMENTO DA NOTA EXTRA

						$nota_final_calculada = sa_calcNotaReavaliacao($getofer,$nota_diario,$nota_extra);

						if($nota_final_calculada != $nota_final) {

							// NOTA EXTRA LANCADA E SOMATORIO ERRADO
							$qryDiario .= ' UPDATE matricula SET nota_final = '. $nota_final_calculada;
							$qryDiario .= ' WHERE ref_pessoa = '. $ref_pessoa .' AND';
							$qryDiario .= " ref_periodo = '$getperiodo' AND ";
							$qryDiario .= ' ref_disciplina_ofer = '. $getofer .';';

							$numNotas++;
						}
					}
				}
			}

			if($numNotas > 0) {

				$flag_pendencia = 1;
				$msg_atualiza .= 'Atualizado somat&oacute;rio de faltas\n';
				// $msg .= $numNotas . ' alunos com problemas no somatorio de notas\n';
			}

		}

		// VERIFICA PENDENCIAS RELACIONADAS AO SOMATORIO DE NOTAS *** CONSIDERA NOTA EXTRA ***

		// APLICA A RESOLUCAO DE PENDENCIAS CASO EXISTA ALGUMA
		if($flag_pendencia == 1) {

			$qryDiario .= "COMMIT;";

			// GRAVA AS ALTERACOES
			$res = $conn->Execute($qryDiario);
                        // echo $qryDiario;

			if($res === FALSE) {

				// MENSAGEM DE ERRO AO GRAVAR AS ALTERACOES OU ENVIA EMAIL AVISANDO ALGUEM
				$msg_erro = "";
                envia_erro($res ."\n\n". $qryDiario);

				//^ MENSAGEM DE ERRO AO GRAVAR AS ALTERACOES OU ENVIA EMAIL AVISANDO ALGUEM

			}
			else {
				// MENSAGEM PENDENCIAS RESOLVIDAS COM SUCESSO
				$msg_sucesso = "";
				//^ MENSAGEM PENDENCIAS RESOLVIDAS COM SUCESSO
			}

		}
		else {

			// MENSAGEM NENHUMA PENDENCIA A RESOLVER
			$msg_sem_pendencias = "";
			//^  MENSAGEM NENHUMA PENDENCIA A RESOLVER
		}
		// ^ APLICA A RESOLUCAO DE PENDENCIAS CASO EXISTA ALGUMA

	}

}


//RESOLVER PENDENCIAS
//Param: id aluno, id disciplina oferecida
//atualiza_matricula('819','4012');

?>
