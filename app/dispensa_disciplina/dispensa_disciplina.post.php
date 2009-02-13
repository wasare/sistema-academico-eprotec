<?php

header("Cache-Control: no-cache");

//-- ARQUIVO E BIBLIOTECAS
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");

//-- Conectando com o PostgreSQL
$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


//-- PARAMETROS
$periodo_id  = $_POST["periodo_id"];
$curso_id    = $_POST["curso_id"];
$aluno_id    = $_POST["aluno_id"];
$id_contrato = $_POST["id_contrato"];
$ref_campus  = $_POST["ref_campus"];
$id_diarios  = $_POST["id_diarios"]; //Array com todos os diarios a matricular

$msg = '<h3><font color=\"#006600\">Disciplinas matr&iacute;culadas:</font></h3>'; //-- Variavel com a resposta para o usuario

$sqlInsereDiario = "BEGIN;"; //-- Variavel com a sql de insercao dos diarios

// FIXME: atualizar o contrato somente quando pelo menos uma matricula for efetivada
//-- Atualizando o contrato para o periodo corrente
$sqlAtualizaContrato = "
UPDATE contratos SET
  cod_status = null,
  ref_last_periodo = '$periodo_id'
WHERE
  id = '$id_contrato'";

if($Conexao->Execute($sqlAtualizaContrato) === false)
{
    $msg .= ">> <font color=\"#FF0000\">Erro ao atualizar contrato: $Conexao->ErrorMsg()</font><br>";
}
else
{
    $msg .= ">> Contrato atualizado para o per&iacute;odo<br>";
}



//-- Percorre os diarios
foreach($id_diarios as $diario){

	//-- Verifica se o aluno ja esta matriculado nesta disciplina oferecida

	$sqlMatriculado = "
  	SELECT 
    	count(ref_disciplina_ofer)
  	FROM 
    	matricula
  	WHERE 
    	ref_disciplina_ofer = '$diario' AND
    	ref_periodo = '$periodo_id' AND
    	ref_pessoa  = '$aluno_id'";
	
	$RsMatriculado = $Conexao->Execute($sqlMatriculado);
	$Result1 = $RsMatriculado->fields[0];

         	
	if($Result1 == 0){
	
	
		//-- Informacoes da disciplina
		$sqlDisciplina = "
		SELECT 
	  		descricao_disciplina(ref_disciplina),
	  		ref_disciplina,
	  		ref_campus
		FROM 
	  		disciplinas_ofer 
		WHERE 
	  		id = $diario";
		
		$RsDisciplina = $Conexao->Execute($sqlDisciplina);
		
		$disciplina_descricao = $RsDisciplina->fields[0];
		$disciplina_id = $RsDisciplina->fields[1];
		$ref_campus_ofer = $RsDisciplina->fields[2];
		
		
		//-- Verifica se tem vaga
    	$sqlVerificaVagas = "
		SELECT
    	  count(*),
	      check_matricula_pessoa('$diario','$aluno_id'),
    	  num_alunos('$diario')
	    FROM
    	  matricula
	    WHERE
    	  ref_disciplina_ofer = '$diario' AND
	      dt_cancelamento is null";
	  
		$RsVerificaVagas = $Conexao->Execute($sqlVerificaVagas);
	
	    if ($RsVerificaVagas)
    	{
        	$num_matriculados = $RsVerificaVagas->fields[0];
	        $is_matriculado = $RsVerificaVagas->fields[1];
    	    $tot_alunos = $RsVerificaVagas->fields[2];
    	}
	    else
    	{
        	$num_matriculados = 0;
	        $tot_alunos = 0;
    	}
	
	
		//-- Se o total de vagas excedeu não matricula
		if (($num_matriculados+1) > $tot_alunos)
    	{
	       $msg .= "<p>>> <b><font color=\"#FF0000\">Aluno n&atilde;o matriculado!</font></b><br>";
		   $msg .= "Disciplina <b>$disciplina_descricao</b> ($disciplina_id) excedeu n&uacute;mero m&aacute;ximo de alunos.</p>";
    	}
	    else
    	{
			$alunos_matriculados = $num_matriculados + 1;
			$msg .= "<p>>> <b>Di&aacute;rio: </b>$diario - "; 
			$msg .= "<b>$disciplina_descricao</b> ($disciplina_id) - ";
			$msg .= "<b>Matric./Vagas: </b> ".$alunos_matriculados."/$tot_alunos.</p>";
			
			//-- Informacoes da disciplina substituta --  IMPLEMENTAR
			$ref_curso_subst = 0;
			$ref_disciplina_subst = 0;
		
			$sqlInsereDiario .= "
			INSERT INTO matricula
    	    (
        	   ref_contrato,
	           ref_pessoa,
    	       ref_campus,
        	   ref_curso,
	           ref_periodo,
    	       ref_disciplina,
        	   ref_curso_subst,
	           ref_disciplina_subst,
    	       ref_disciplina_ofer,
        	   complemento_disc,
	           fl_exibe_displ_hist,
    	       dt_matricula,
        	   hora_matricula,
	           status_disciplina
    	    )
        	VALUES (
	           '$id_contrato',
    	       '$aluno_id',
        	   '$ref_campus_ofer',
	           '$curso_id',
    	       '$periodo_id',
        	   '$disciplina_id',
				'$ref_curso_subst',
	           '$ref_disciplina_subst',
    	       '$diario',
        	   get_complemento_ofer('$diario'),
	           'S',
    	       date(now()),
        	    now(),
	           'f'
    	    );";
			
			$diarios_matriculados[] = $diario;
	
		}//fim total de vagas
	}
	else{
	       $msg .= "<p>>> <b><font color=\"#FF0000\">Aluno j&aacute; matriculado no di&aacute;rio $diario!</font></b></p>";
	}//fim matriculados
	
}//fim foreach

$sqlInsereDiario .= "COMMIT;";


//-- Inserindo a matricula
$RsInsereDiario = $Conexao->Execute($sqlInsereDiario);
			
if (!$RsInsereDiario)
{
	$title = "<h3><font color=\"#FF0000\">Erro ao efetuar matricula!</font></h3>";
	$msg = ">> Di&aacute;rio: $diario<br>";
	/*$msg .= ">> Aluno: $aluno_id<br>";
	$msg .= ">> Per&iacute;odo: $periodo_id<br>";
	$msg .= ">> Curso: $curso_id";*/
	$msg .= "<p><b>Informa&ccedil;&otilde;es sobre o erro:</b><br>$Conexao->ErrorMsg()</p>";
}

$cabecalho = ">> <strong>Aluno</strong>: $aluno_id <br />";
$cabecalho .= ">> <strong>Curso</strong>: $curso_id  - <strong>Per&iacute;odo</strong>: $periodo_id <br />";

// ATUALIZA NOTAS E FALTAS CASO O DIARIO TEM SIDO INICIALIZADO 
require_once('atualiza_diario.php');

foreach($diarios_matriculados as $matriculado){
	atualiza_matricula("$aluno_id","$matriculado");
}

// ^ ATUALIZA NOTAS E FALTAS CASO O DIARIO TEM SIDO INICIALIZADO ^ //

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SA</title>
<link href="../../Styles/formularios.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="matricula.js"></script>
</head>
<body>
<div align="center">
  <h1>Processo de Matr&iacute;cula</h1>
  <div class="box_geral"> 
	<?=$title?>
       <?=$cabecalho?>
    <?=$msg?>
  </div>
  <a href="matricula_aluno.php">Nova matr&iacute;cula</a> <a href="../../diagrama.php">P&aacute;gina inicial</a> </div>
</body>
</html>
