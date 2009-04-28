<?php

include_once('../../webdiario.conf.php');


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];


$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);


$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);

$getcurso = getCurso($getperiodo,$getdisciplina,$getofer);

$sql1 = "SELECT
         grupo
         FROM diario_formulas
         WHERE
         grupo ILIKE '$grupo_novo';";
                
$query1 = pg_exec($dbconnect, $sql1);

$numreg = pg_NumRows($query1);


if($numreg == 0) 
{

	// PASSO 1
	$numprovas = 6;

	// PASSO 2
	for ($cont=1; $cont <= $numprovas; $cont++) 
	{
	$prova[] = 'Nota '.$cont;
	}

	// PASSO 3
	$sqldel = "BEGIN; DELETE FROM diario_formulas WHERE grupo ILIKE '$grupo_novo';";
	$sqldel .= "DELETE FROM diario_notas WHERE rel_diario_formulas_grupo ILIKE '$grupo_novo'; COMMIT;";

	$qrydel =  consulta_sql($sqldel);

	if(is_string($qrydel))
	{
		echo $qrydel;
		exit;
	}

	reset($prova);

	$sql1 = 'BEGIN;';

	while (list($index,$value) = each($prova)) 
	{
		$descricao_prova = $prova[$index];
		$num_prova=($index+1);
		$frm='P1';
		$sql1 .= "INSERT INTO diario_formulas (ref_prof, ref_periodo, ref_disciplina, prova, descricao, formula, grupo) values('$id','$getperiodo','$getdisciplina','$num_prova','$descricao_prova','$frm','$grupo');";
   
	}

	$sql1 .= 'COMMIT;';

	$qry1 = consulta_sql($sql1);

	if(is_string($qry1))
	{
		echo $qry1;
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


	//$formula = 'P1+P2+P3+P4';

	// PASSO 4 E FINAL
	include_once('processa_formula.php');


} 
else 
{

 
  echo '<html> <body> <script type="text/javascript"> self.location.href = "lancanotas2.php?id=' . $id. '&disc=' . $getdisciplina . '&ofer=' . $getofer. '&getperiodo=' . $getperiodo. '&curso=' . $curso. '" </script> </body> </html>';
}

?>
