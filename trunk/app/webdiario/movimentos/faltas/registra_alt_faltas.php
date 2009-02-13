<?php
	
include_once ('../../conf/webdiario.conf.php');

/*
print_r($_GET);

print_r($_POST);

print_r($_SESSION);
*/

if(isset($_POST['faltas_ok']) && $_POST['faltas_ok'] == 'F') {


    //print_r($_POST);

	$disciplina = $_POST['disciplina'];
	$getofer = $_POST['oferecida'];
	$num_aulas = $_POST['num_aulas'];
	$data_chamada = $_POST['data_chamada'];
    $oferecida = $getofer;

	$aulatipo = $_POST['aulatipo'];

	$nomes = $_POST['faltas'];
}


$periodo = $_SESSION['periodo'];

$id = $_SESSION['id'];


$sqlCurso = "SELECT DISTINCT
             d.ref_curso
         FROM
          disciplinas_ofer d
        WHERE
          d.ref_periodo = '$periodo' AND
          d.id = '$oferecida' AND
          d.is_cancelada = 0;";

// d.ref_disciplina = '$disciplina' AND

$qryCurso = consulta_sql($sqlCurso);

if(is_string($qryCurso))
{
   echo $qryCurso;
   exit;
}
else
{
    while ( $linha = pg_fetch_array($qryCurso) )
    {
        $curso = $linha['ref_curso'];
     }
}


function setFaltas($p, $v, $d, $o, $f, $op, $qry, $dt, $qtde="")
{

  $aluno = getNome($v);

  //echo $qry;

  if(falta($p, $v, $d, $o, $f, "$op", $qry))
  {
    return $qtde . " Falta(s) registrada(s) para $aluno no dia $dt<br>";
  }
  else
  {
	  return '<font color="orange"><b>Ocorreu um erro ao registrar falta</b></font> para '.$aluno.' no dia '.$dt.'<br />';
  }
}


function showNovaChamada()
{
  global $id, $periodo, $disciplina, $oferecida;
  
  echo '<br /> <br /> ALTERA&Ccedil;&Atilde;O DE FALTAS REALIZADA!<br /> *Verifique se n&atilde;o ocorreu nenhum erro no processo de altera&ccedil;&atilde;o de faltas*<br /> <br />';
  echo '<link rel="stylesheet" href="../../css/gerals.css" type="text/css">';
  echo '<center> <a href="faltas.php?disc='.$disciplina.'&ofer='.$oferecida.'" >Alterar Outras Faltas</a> | <a href="../../prin.php?y=2007" target="_self">HOME</a></center>';

  ///faltas/faltas.php?id=2472&getperiodo=0701&disc=107001&ofer=1715
}

function regLog($sql,$status="")
{
  global $us;
  $ip = $_SERVER["REMOTE_ADDR"];
  $pagina = $_SERVER["PHP_SELF"];
  $usuario = trim($us);
  $sql_store = htmlspecialchars("$usuario");
  
  $sqllog = $sql;

  $sqllog .= '(\''.$sql_store.'\',\''.getTime(0).'\',\''.getTime(1).'\','."'$ip','$pagina','$status','')";
  
  $res = consulta_sql($sqllog);

  if(is_string($res))
  {
	  echo $res;
	  exit;
  }
}

function getNome($id)
{
  $sqlsel = "SELECT nome FROM pessoas WHERE ra_cnec = '$id';";
  $querysel = consulta_sql($sqlsel);
  
  while ($linhasel = pg_fetch_array($querysel))
  {  
    $selnome = $linhasel['nome'];
	$aluno = '<font color="red"><b>'.$selnome.' ('.$id.')</b></font>';
  }
  
  return $aluno;
}

function processaAlteraFaltas($Nomes, $n_aulas)
{

  global $data_chamada, $id, $periodo, $curso , $disciplina, $oferecida;

  $qryFaltas = 'INSERT INTO diario_chamadas (ra_cnec, data_chamada, ref_professor, ref_periodo, ref_curso, ref_disciplina, aula, abono, ref_disciplina_ofer) VALUES ';

  $resposta = '<div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Lan&ccedil;amento de Faltas - Altera&ccedil;&atilde;o</strong></font></div> <br />';

  $resposta .=  getHeaderDisc($oferecida);


	if ( array_sum($Nomes) == 0 ) {

		$resposta .= '<h3><font color="blue"><b>Nenhum aluno faltou &agrave;(s) '.$n_aulas.' aula(s) do dia '.$data_chamada.'</b></font></h4>';

	}

  
	//$sqlFaltas = 'BEGIN;';
    
	reset($Nomes);
	
	echo '<br /><br /><br />';
	
	while($array_cell = each($Nomes))
	{
		$sqlFaltas = 'BEGIN;';
		
		$reg_aluno = $array_cell['key'];
		$num_faltas = $array_cell['value'];

		if($num_faltas == 0) { $num_faltas == -1; }
     
		if($num_faltas > 0  && $num_faltas <= $n_aulas) 
		{
			for ($i = 1; $i <= $num_faltas; $i++)
			{
				$sqlFaltas .= $qryFaltas." ('$reg_aluno','$data_chamada','$id','$periodo','$curso','$disciplina','$i','N',$oferecida);";
			}

			$resposta .= setFaltas($periodo, $reg_aluno, $disciplina, $oferecida, $num_faltas, 'SOMA', $sqlFaltas, $data_chamada,"<strong>$num_faltas</strong>");

		}

	}

    echo $resposta;
}


$datadehoje = date ("d/m/Y");

// EXCLUI TODAS AS FALTAS ANTERIORES PARA A CHAMADA

$sqlExcluiFaltas = " SELECT id, ra_cnec
					 FROM
  						diario_chamadas a
  					WHERE
  						(a.ref_periodo = '$periodo') AND
                        (a.ref_disciplina_ofer = '$oferecida') AND
                        (a.data_chamada = '$data_chamada');";

//echo $sqlExcluiFaltas;

$qryExcluiFaltas = consulta_sql($sqlExcluiFaltas);

if(is_string($qryExcluiFaltas))
{
   echo $qryExcluiFaltas;
   exit;
}

$ExcluiFaltas = pg_fetch_all($qryExcluiFaltas);

//print_r($ExcluiFaltas);

if(@count($ExcluiFaltas) > 0) {

	while( $array_cell = @each($ExcluiFaltas) )
	{
		$vlr = $array_cell['value'];

		$valor = $vlr['id'];
		$ra_cnec = $vlr['ra_cnec'];
		
		// DELETA A FALTA DO DIARIO
		$sql1 = " BEGIN; DELETE FROM diario_chamadas WHERE id = $valor;";

		falta($periodo, $ra_cnec, $disciplina, $oferecida, 1, 'SUB', $sql1);
   }

}

// <


//$qryFaltas = 'INSERT INTO diario_chamadas (ra_cnec, data_chamada, ref_professor, ref_periodo, ref_curso, ref_disciplina, aula, abono, ref_disciplina_ofer) VALUES ';
$qryLog = 'BEGIN; INSERT INTO diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) VALUES ';
$status = 'FALTA ATUALIZADA';


//print_r($nomes);


processaAlteraFaltas($nomes,$num_aulas);

$st = $status . $aulatipo;
regLog($qryLog,$st);

showNovaChamada();


?>
