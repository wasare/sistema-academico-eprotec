<?php


$conn = new Connection;
$dbconnect = $conn->Open();


function envia_erro($msg) {

	// ENVIA EMAIL PARA O ADMINISTRADOR
        $mail_header = "FROM: webmaster@cefetbambui.edu.br";
        @mail( 'webmaster@cefetbambui.edu.br', '[ Erro ao atualizar diario ] ', $msg, $mail_header);

}

function consulta_sql($sql_query) {
	global $dbconnect, $error_msg;

	if (!$dbconnect) {
		if (!($dbconnect = diario_open_db())) {
			return null;
		}
	}

	if (( $result_sql = pg_exec($dbconnect, $sql_query)) == false) {
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


function getCurso($p,$d,$o) {


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

	$qry9 = consulta_sql($sql9);

	if(is_string($qry9)) {

		envia_erro($qry9);
		exit;
	}

	while($linha9 = pg_fetch_array($qry9)) {
		$curso   = $linha9["ref_curso"];
	}

	return $curso;

}


function calcNotaReavaliacao($o,$nd,$ne) {


	// CONSULTA O NIVEL DO CURSO
	$sqlCursoTipo = 'SELECT
                     ref_tipo_curso
                     FROM
                     cursos c, disciplinas_ofer d
                     WHERE
                     c.id = ref_curso AND
                     d.id = '.$o.';';

	$qryCursoTipo = consulta_sql($sqlCursoTipo);

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


function atualiza_matricula($aluno,$getofer,$abre_diario=FALSE) {


	// RECUPERA INFORMACOES DO DIARIO
    $qryDisc = " SELECT DISTINCT
                o.ref_disciplina, o.ref_periodo 
                FROM 
                disciplinas_ofer o
                WHERE
                 o.id = '" . $getofer . "' AND 
                 o.is_cancelada = 0 ;";


    $qry1 = consulta_sql($qryDisc);


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
                $id = 0;
            }

			$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);
    		$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);
			$getcurso = getCurso($getperiodo,$getdisciplina,$getofer);

        } // ^ A DISCIPLINA EXISTE
    }
	// ^ RECUPERA INFORMACOES DO DIARIO ^ //

	$sql1 = "SELECT
    grupo
    FROM diario_formulas
    WHERE
    grupo ILIKE '$grupo_novo';";


    $qryFormula = consulta_sql($sql1);

    if(is_string($qry))
    {
        envia_erro($qry);
        exit;
    }

    $num_formula = pg_numrows($qryFormula);

    // INICIALIZA O DIARIO CASO NECESSÁRIO
	if($abre_diario AND !empty($grupo) AND is_numeric($getcurso))
    {

		$grupo_inicial = ($id ."-" . $getperiodo . "-". $id ."-" . $getofer);

		if($num_formula == 0) 
		{

			// PASSO 1
			$numprovas = 6;

			// PASSO 2
			for ($cont=1; $cont <= $numprovas; $cont++)
			{
   				$prova[] = 'Nota '.$cont;
			}

			// PASSO 3 - EXCLUI REFERENCIAS PERDIDAS 
			$sqldel = "BEGIN; DELETE FROM diario_formulas WHERE grupo ILIKE '$grupo_novo';";
			$sqldel .= "DELETE FROM diario_notas WHERE rel_diario_formulas_grupo ILIKE '$grupo_novo'; COMMIT;";

			$qrydel =  consulta_sql($sqldel);

			if(is_string($qrydel))
			{
    			envia_erro($qrydel);
		    	exit;
			}

			reset($prova);

			// REGISTRA A FORMULA PARA O DIARIO E INICIALIZA OS REGISTROS
			$sql1 = 'BEGIN;';

			while (list($index,$value) = each($prova))
			{
   				$descricao_prova = $prova[$index];
  	 			$num_prova=($index+1);
   				$frm='P1';
   				$sql1 .= "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values('$id','$getperiodo','$getdisciplina','$num_prova','$descricao_prova','$frm','$grupo_inicial');";

			}

			$sql1 .= 'COMMIT;';

			$qry1 = consulta_sql($sql1);

			if(is_string($qry1))
			{
    			envia_erro($qry1);
    			exit;
			}
			$formula = '';

			for ($cont = 1; $cont <= $numprovas; $cont++)
			{
   					if($cont == 1)
  				{
      				$formula .= 'P'.$cont;
   				}
   				else
   				{
      				$formula .= '+P'.$cont;
   				}
			}


			// PASSO 4 E FINAL
			require_once('processa_formula_diario.php');

			// ^ REGISTRA A FORMULA PARA O DIARIO E INICIALIZA REGISTROS ^ //
		}
    } // ^ INICIALIZA O DIARIO CASO NECESSÁRIO ^ //

    

	$flag_pendencia = 0;

	$qryDiario = 'BEGIN;';

	// $msg = 'Encontradas e resolvidas as seguintes pendencias:\n\n';
	//echo $getperiodo . " - ". $getdisciplina ." - ". $getofer;
	//die;


	// VERIFICA PENDENCIAS RELACIONADAS AO LANCAMENTO DE NOTAS

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
		  id_ref_pessoas IS NULL 
	        ORDER BY id_ref_pessoas;';



		$qry = consulta_sql($qryNotas);

		if(is_string($qry))
		{
			envia_erro($qry);
			exit;
		}

		$NumReg = pg_numrows($qry);

		$NumNotas = 6;

		if ($NumReg > 0)
		{

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

		$qryFaltas = consulta_sql($sqlDiarioFaltas);


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

		$qryNotas = consulta_sql($sqlNotas);

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

						$nota_final_calculada = calcNotaReavaliacao($getofer,$nota_diario,$nota_extra);

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
			$res = consulta_sql($qryDiario);
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

function lanca_nota($aluno,$nota_final,$getofer,$codprova=1)
{
    // FIXME: antes de gravar a nota verificar:
    //     - se nota não é > 100
    //     - lançamento de nota extra, não lançar nota caso exista a extra
    $msg = '';

    $nota = str_replace(",",".",$nota_final);

    $sqlUpdate .= "UPDATE matricula
                             SET 
                            nota_final = $nota 
                          WHERE 
                             ref_pessoa = $aluno AND
                             ref_disciplina_ofer = $getofer;";
    $sqlUpdate .= "UPDATE 
                     diario_notas 
                  SET 
                     nota = $nota 
                  WHERE 
                     d_ref_disciplina_ofer = $getofer AND
                     ref_diario_avaliacao = $codprova AND 
                     ra_cnec = $aluno;";

    $qry1 = consulta_sql($sqlUpdate);

    if(is_string($qry1)) {
        envia_erro($qry1);
        $msg = 'p>>> <b><font color="#FF0000">Falha ao atualizar Nota '. $codprova .' do aluno '. $aluno .' Di&aacute;rio '. $getofer .'</font></b></p>';
    }

    return $msg;
}

/*
 // FIXME  -- para as faltas construir a chamada a partir de uma data inicial
function lanca_chamada($aluno,$num_faltas,$getofer,$data_inicial) 
{

    



}

// FIXME  -- gravar o conteúdo de aula na primeira chamada e anexar uma observação
function lanca_conteudo($getofer,$data_inicial,$conteudo) 
{



}
*/


//RESOLVER PENDENCIAS
//Param: id aluno, id disciplina oferecida
//atualiza_matricula('819','4012');

?>
