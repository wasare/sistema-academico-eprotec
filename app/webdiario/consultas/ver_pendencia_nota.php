<?php

//FUNCAO RESOLVE PENDENCIAS DO SOMATORIO DE NOTAS --

//$total_nota_matricula - campo somatorio com total da nota na tabela matricula do sagu
//$soma_notas_diario - somatorio das notas da tabela diarios_nota

function ver_pendencia($soma_notas_diario, $total_nota_matricula)
{

	//Nota a exibir
	//$total_nota = 0;
	
	//se a nota do sagu for diferente do diario
	if($total_nota_matricula != $soma_notas_diario)
	{
		
		echo "eh diferente!";
		// se o somatorio do diario for 0 ou nulo
		if($soma_notas_diario == 0 or $soma_notas_diario == 'null')
		{
			
			// se a nota do sagu for 0 ou nulo
			if($total_nota_matricula == 0 or $total_nota_matricula == 'null')
			{
			
				$total_nota = 0;
			}
			else
			{
				//
				$total_nota = $total_nota_matricula;
			}
		}
		else
		{
			//
			$total_nota = $soma_notas_diario;
		}
		
	}
	else
	{
		if($soma_notas_diario == 'null')
		{
			$total_nota = 0;
		}
		//nada
		echo "eh igual!";
	}
	
	return $total_nota;
	
}//FIM FUNCAO RESOLVE PENDENCIAS --


//$racnec = '1301'; //igual ao id da tabela pessoas -  2540
//$getperiodo = '0701';
//$getofer = '1704';


//RECUPERA AS NOTAS PARCIAIS POR ALUNO
//param: codigo do aluno, periodo, diario
function ver_nota_aluno_webdiario($racnec, $getperiodo, getofer)
{
	
	include_once('../conf/webdiario.conf.php');
	
	$sqlnotas = 'SELECT DISTINCT 
    b.nome, c.nota, ref_diario_avaliacao
  	FROM 
    matricula a, pessoas b, diario_notas c 
  	WHERE 
    a.ref_periodo = \'' . $getperiodo . '\' AND 
    a.ref_disciplina_ofer = \'' . $getofer . '\' AND
    b.ra_cnec = c.ra_cnec AND
    c.d_ref_disciplina_ofer = \'' . $getofer . '\' AND
    a.ref_pessoa = b.id AND
    b.ra_cnec = ' . $racnec . '
  	ORDER BY 3;';
	
	//echo $sqlnotas;
	//die;
	
	$qrynotas = consulta_sql($sqlnotas);
	
	if(is_string($qrynotas))
	{
  		echo $qrynotas;
  		exit;
	}

	while($row = pg_fetch_array($qrynotas))
	{
   		$N = $row['nota'];
	  
	   	if($N > 0) { 
   			$N = getNumeric2Real($N); 
		}
   	
		$total_nota_webdiario += $N;
   		echo $N . "<br/>";
		
	}
	
	return $total_nota_webdiario;

}
	
//param: notas diario, nota total tabela matricula
//echo ver_pendencia(0, 3);
//die;

?>