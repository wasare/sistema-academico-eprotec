<?php

header("Cache-Control: no-cache");

//-- ARQUIVO E BIBLIOTECAS
require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");


// PROCESSA A DISPENSA SE NAO HOUVER ERROS
if ( $_POST['second'] != 1 )
	die;

$flag_processa = 1;

require_once('dispensa_valida.php');

//-- Conectando com o PostgreSQL
$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


// Array ( [dispensa_tipo] => 4 [ref_liberacao_ed_fisica] => 1 ) 

// Array ( [dispensa_tipo] => 3 [nota_final] => 87 ) 

// Array ( [dispensa_tipo] => 2 [ref_instituicao] => 182 [instituicao_nome] => CESEC Professor João de Oliveira Barbosa [obs_aproveitamento] => Física [nota_final] => 87 ) 


//-- PARAMETROS
$dispensa_tipo  = $_POST['dispensa_tipo'];
$ref_liberacao_ed_fisica  = $_POST['ref_liberacao_ed_fisica'];
$processo  = $_POST['processo'];
$diario_id  = $_POST['diario_id'];
$ref_instituicao  = $_POST['ref_instituicao'];
$obs_aproveitamento  = $_POST['obs_aproveitamento'];
$obs_final  = $_POST['obs_final'];
$nota_final  = $_POST['nota_final'];


$periodo_id  = $_POST['periodo_id'];
$curso_id    = $_POST['curso_id'];
$aluno_id    = $_POST['aluno_id'];
$id_contrato = $_POST['id_contrato'];
$ref_campus  = $_POST['ref_campus'];


// PARAMETROS SQL

// APROVEITAMENTO DE ESTUDOS
if ($dispensa_tipo == 2)
{

 	$insert_sql = ',ref_instituicao,obs_aproveitamento,nota_final';
 	$values_sql = ",$ref_instituicao,'$obs_aproveitamento', $nota_final";

}
// CERTIFICACAO DE EXPERIENCIAS
if ($dispensa_tipo == 3)
{
	$insert_sql = ',nota_final';
    $values_sql = ",$nota_final";

}

// EDUCACAO FISICA
if ($dispensa_tipo == 4)
{
	$insert_sql = ',obs_final, ref_liberacao_ed_fisica';
    $values_sql = ",'$obs_final',$ref_liberacao_ed_fisica";
}

$insert_sql .= ',ref_motivo_matricula, processo';
$values_sql .= ",$dispensa_tipo,'$processo'";


$msg = '<h3><font color=\"#006600\">Dispensa de Disciplina:</font></h3>'; //-- Variavel com a resposta para o usuario

$sqlInsereDispensa = ""; //-- Variavel com a sql de insercao da dispensa


	//-- Verifica se o aluno ja esta matriculado nesta disciplina oferecida

	$sqlDispensado = "
  	SELECT 
    	count(ref_disciplina_ofer)
  	FROM 
    	matricula
  	WHERE 
    	ref_disciplina_ofer = '$diario_id' AND
    	ref_periodo = '$periodo_id' AND
    	ref_pessoa  = '$aluno_id'";
	
	$RsDispensado = $Conexao->Execute($sqlDispensado);
	$Result1 = $RsDispensado->fields[0];

         	
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
	  		id = $diario_id";
		
		$RsDisciplina = $Conexao->Execute($sqlDisciplina);
		
		$disciplina_descricao = $RsDisciplina->fields[0];
		$disciplina_id = $RsDisciplina->fields[1];
		$ref_campus_ofer = $RsDisciplina->fields[2];
		
		
		//-- Verifica se tem vaga
    	$sqlVerificaVagas = "
		SELECT
    	  count(*),
	      check_matricula_pessoa('$diario_id','$aluno_id'),
    	  num_alunos('$diario_id')
	    FROM
    	  matricula
	    WHERE
    	  ref_disciplina_ofer = '$diario_id' AND
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
	       $msg .= "<p>>> <b><font color=\"#FF0000\">Aluno n&atilde;o dispensado!</font></b><br>";
		   $msg .= "Disciplina <b>$disciplina_descricao</b> ($disciplina_id) excedeu n&uacute;mero m&aacute;ximo de alunos.</p>";
    	}
	    else
    	{
			$alunos_matriculados = $num_matriculados + 1;
			$msg .= "<p>>> <b>Di&aacute;rio: </b>$diario_id - "; 
			$msg .= "<b>$disciplina_descricao</b> ($disciplina_id) - ";
			$msg .= "<b>Matric./Vagas: </b> ".$alunos_matriculados."/$tot_alunos.</p>";
			
			//-- Informacoes da disciplina substituta --  IMPLEMENTAR
			$ref_curso_subst = 0;
			$ref_disciplina_subst = 0;
		
			$sqlInsereDispensa .= "
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
			   $insert_sql
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
    	       '$diario_id',
        	   get_complemento_ofer('$diario_id'),
	           'S',
    	       date(now()),
        	    now(),
	           'f'
			   $values_sql
    	    );";
			
		}//fim total de vagas
	}
	else{
	       $msg .= "<p>>> <b><font color=\"#FF0000\">Aluno j&aacute; matriculado no di&aacute;rio $diario_id!</font></b></p>";
	}//fim matriculados
	

//echo $sqlInsereDispensa; //die;

//-- Inserindo a matricula
$RsInsereDiario = $Conexao->Execute($sqlInsereDispensa);
			
if (!$RsInsereDiario)
{
	$title = "<h3><font color=\"#FF0000\">Erro ao efetuar a dispensa!</font></h3>";
	$msg .= ">> Di&aacute;rio: $diario_id<br>";
    
	$msg .= "<p><b>Informa&ccedil;&otilde;es adicionais:</b>".$Conexao->ErrorMsg."</p>";
}
else
{

   // EXCLUI FALTAS DO DIARIO PARA EVITAR REPROVAÇÃO POR FALTAS
   $sqlFaltas = "DELETE FROM diario_chamadas WHERE ra_cnec = $aluno_id AND ref_disciplina_ofer = $diario_id;";
   $RsFaltas = $Conexao->Execute($sqlFaltas);
   // ^ EXCLUI FALTAS DO DIARIO PARA EVITAR REPROVAÇÃO POR FALTAS ^ //
     
	// ATUALIZA NOTAS E FALTAS NO DIARIO
	require_once('atualiza_diario_dispensa.php');

    atualiza_matricula($aluno_id,$diario_id,TRUE);
    if(is_numeric($nota_final) AND $nota_final >= 50 )
		$msg .= lanca_nota($aluno_id,$nota_final,$diario_id);

	// ^ ATUALIZA NOTAS E FALTAS NO DIARIO ^ //
}

$cabecalho = ">> <strong>Aluno</strong>: $aluno_id <br />";
$cabecalho .= ">> <strong>Curso</strong>: $curso_id  - <strong>Per&iacute;odo</strong>: $periodo_id <br />";


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
  <h1>Processo de Dispensa de Disciplina</h1>
  <div class="box_geral"> 
	<?=$title?>
       <?=$cabecalho?>
    <?=$msg?>
  </div>
  <a href="dispensa_aluno.php">Nova Dispensa</a> <a href="../../diagrama.php">P&aacute;gina inicial</a> </div>
</body>
</html>
