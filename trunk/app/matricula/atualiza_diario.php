<?php

function envia_erro($msg) {

	// ENVIA EMAIL PARA O ADMINISTRADOR
        $mail_header = "FROM: webmaster@cefetbambui.edu.br";
        @mail( 'webmaster@cefetbambui.edu.br', '[ Erro ao atualizar diario ] ', $msg, $mail_header);

}

function sa_consulta_sql($sql_query) {
	global $conn, $error_msg;

	if (!$conn) {
		if (!($conn = diario_open_db())) {
			return null;
		}
	}

	if (( $result_sql = pg_exec($conn, $sql_query)) == false) {
		$error_msg = "Error ao executar a consulta: " . $sql_query;
		$error_msg .= '<br /> <br />Entre em contato com o respons&aacute;vel: ';
		$error_msg .= '<a href="javascript:history.go(-1)">Voltar</a></b>';
		return $error_msg;
	} else {
		//$rows = pg_fetch_array($result_sql);
		//echo pg_result_error($result_sql);

		return $result_sql;
	}
}


function sa_getCurso($p,$d,$o) {


	// VAR CONSULTA
	$sql9 = "SELECT
	a.descricao as cdesc,
	b.descricao_extenso,
	c.descricao as perdesc,
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

	//echo $sql9;
	//exit;

	$qry9 = sa_consulta_sql($sql9);

	if(is_string($qry9)) {

		envia_erro($qry9);
		exit;
	}

	while($linha9 = pg_fetch_array($qry9)) {
		$curso   = $linha9["ref_curso"];
	}

	return $curso;

}


function sa_calcNotaReavaliacao($o,$nd,$ne) {


	// CONSULTA O NIVEL DO CURSO
	$sqlCursoTipo = 'SELECT
                     ref_tipo_curso
                     FROM
                     cursos c, disciplinas_ofer d
                     WHERE
                     c.id = ref_curso AND
                     d.id = '.$o.';';

	$qryCursoTipo = sa_consulta_sql($sqlCursoTipo);

	if(is_string($qryCursoTipo))
	{
		envia_erro($qryCursoTipo);
		exit;
	}
	else
	{

		$CursoTipo = pg_fetch_array($qryCursoTipo);
		$CursoTipo = $CursoTipo['ref_tipo_curso'];
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

	// RECUPERA INFORMACOES DO DIARIO
	$qryDisc = " SELECT DISTINCT
				prof.ref_professor, o.ref_disciplina, o.ref_periodo 
				FROM 
				disciplinas_ofer o, disciplinas_ofer_prof prof
            	WHERE
                 o.id = '" . $getofer . "' AND 
				 o.is_cancelada = 0 AND
				 o.id = prof.ref_disciplina_ofer ;";


	$qry1 = sa_consulta_sql($qryDisc);

	//echo $qryDisc;
	//die;

	if(is_string($qry1)) {

		envia_erro($qry1);
		exit;
	}
	else {

		// A DISCIPLINA EXISTE

		if(pg_numrows($qry1) > 0) {

			while($linha = pg_fetch_array($qry1))
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

	// $msg = 'Encontradas e resolvidas as seguintes pendencias:\n\n';
	//echo $getperiodo . " - ". $getdisciplina ." - ". $getofer;
	//die;

	$getcurso = sa_getCurso($getperiodo,$getdisciplina,$getofer);


	// VERIFICA PENDENCIAS RELACIONADAS AO LANCAMENTO DE NOTAS
	$sql1 = "SELECT
	grupo
	FROM diario_formulas
	WHERE
	grupo ILIKE '$grupo_novo';";


	$qryFormula = sa_consulta_sql($sql1);

	if(is_string($qry))
	{
		envia_erro($qry);
		exit;
	}

	$numformula = pg_numrows($qryFormula);

	if($numformula == 6) {

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
		  id_ref_pessoas IS NULL 
	        ORDER BY id_ref_pessoas;';

		//echo $qryNotas;


		$qry = sa_consulta_sql($qryNotas);

		if(is_string($qry))
		{
			envia_erro($qry);
			exit;
		}

		$NumReg = pg_numrows($qry);

		$NumNotas = 6;

		if ($NumReg > 0)
		{
			$getcurso = sa_getCurso($getperiodo,$getdisciplina,$getofer);

			while($registro = pg_fetch_array($qry))
			{
				$ref_pessoa = $registro['ref_pessoa'];

				for($i = 1 ; $i <= $NumNotas; $i++)
				{
					$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
					$qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
					$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
					$qryDiario .= ' rel_diario_formulas_grupo)';
					$qryDiario .= " VALUES($ref_pessoa,'$i','0','0',$ref_pessoa,'$getperiodo',$getcurso,";
					$qryDiario .= " $getofer,'$grupo');";
				}

				$qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
				$qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
				$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
				$qryDiario .= ' rel_diario_formulas_grupo)';
				$qryDiario .= " VALUES($ref_pessoa,'7','-1','0',$ref_pessoa,'$getperiodo',$getcurso,";
				$qryDiario .= " $getofer,'$grupo');";
			}

			$flag_pendencia = 1;
			// $msg .= $NumReg . ' alunos com problemas no lancamento de notas\n';

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
		a.ref_disciplina_ofer = '$getofer' AND
		a.ref_pessoa = '$aluno'
		) AS T1
		FULL OUTER JOIN
		(
		SELECT
		CAST(a.ra_cnec AS INTEGER) AS registro_id, count(a.ra_cnec) AS faltas_diario
		FROM
		diario_chamadas a
		WHERE
		(a.ref_periodo = '$getperiodo') AND
		(a.ref_disciplina_ofer = '$getofer') AND
		a.ra_cnec = '$aluno'
		GROUP BY ra_cnec
		) AS T4

		USING (registro_id)

		) AS TB

		WHERE
		(num_faltas <> faltas_diario);";

		$qryFaltas = sa_consulta_sql($sqlDiarioFaltas);


		if(is_string($qryFaltas))
		{
			envia_erro($qryFaltas);
			exit;
		}

		$numFalta = pg_numrows($qryFaltas);


		if ($numFalta != 0) {

			while($registro = pg_fetch_array($qryFaltas))
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
		a.ref_disciplina_ofer = '$getofer' AND
		b.ra_cnec = c.ra_cnec AND
		c.d_ref_disciplina_ofer = '$getofer' AND
		a.ref_pessoa = b.id AND
		b.ra_cnec = '$aluno'  AND
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
		a.ref_disciplina_ofer = '$getofer' AND
		b.ra_cnec = c.ra_cnec AND
		c.d_ref_disciplina_ofer = '$getofer' AND
		a.ref_pessoa = b.id AND
		b.ra_cnec = '$aluno'  AND
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
		a.ref_disciplina_ofer = '$getofer' AND
		a.ref_pessoa = '$aluno'
		) AS T3

		USING (registro_id)

		WHERE
		nota_diario <> nota_final;";

		$qryNotas = sa_consulta_sql($sqlNotas);

		if(is_string($qryNotas))
		{
			envia_erro($qryFaltas);
			exit;
		}


		$numNotas = pg_numrows($qryNotas);


		if ($numNotas != 0) {

			$numNotas = 0;

			while($registro = pg_fetch_array($qryNotas))
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
			$res = sa_consulta_sql($qryDiario);
                        // echo $qryDiario;

			if(is_string($res)) {

				// MENSAGEM DE ERRO AO GRAVAR AS ALTERACOES OU ENVIA EMAIL AVISANDO ALGUEM
				$msg_erro = "";
                                envia_erro($res);

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
