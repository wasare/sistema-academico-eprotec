<?php

require_once(dirname(__FILE__) .'/../../../setup.php');

// CONEXAO ABERTA PARA TRABALHAR COM TRANSACAO (NÃO PERSISTENTE)
$conexao = new connection_factory($param_conn,FALSE);

require_once($BASE_DIR .'core/web_diario.php');


$diario_id = (int) $_POST['diario_id'];
$operacao = $_POST['operacao'];

/*
TODO: verifica o direito de acesso do usuário ao diário informado
*/

if (is_finalizado($diario_id)){

    echo '<script language="javascript" type="text/javascript">';
    echo 'alert("ERRO! Este diário está finalizado e não pode ser alterado!");';
    echo 'window.close();';
    echo '</script>';
    exit;
}


$sem_faltas = '';

// HOUVE FALTAS PARA A CHAMADA
if(isset($_POST['faltas_ok']) && $_POST['faltas_ok'] != 'F') {

	$aula_tipo = $_POST['aula_tipo'];
	$num_aulas = $_POST['num_aulas'];
	$data_chamada = $_POST['data_chamada'];
}
else {
	$sem_faltas = '<h3><font color="blue"><b>Nenhum aluno faltou &agrave;(s) '. $num_aulas .' aula(s)  do dia '. $data_chamada .'</b></font></h4>';
}


if(isset($_POST['faltas'])) $nomes = $_POST['faltas']; else $nomes = '';

$periodo = $_SESSION['web_diario_periodo_id'];

$conteudo = $_SESSION['conteudo'];
$id = $_SESSION['id'];



function set_faltas($ref_pessoa, $diario_id, $qtde_faltas, $op, $qry, $dt, $qtde="")
{
	global $conn;

	$sqlsel = "SELECT nome FROM pessoas WHERE id = $ref_pessoa;";
	$aluno = $conn->get_one($sqlsel);

    $aluno = '<font color="red"><b>'. $aluno .' ('. $ref_pessoa .')</b></font>';
  
	if(falta($ref_pessoa, $diario_id, $qtde, "$op", $qry))
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
  
  echo '<br /> <br /> CHAMADA REALIZADA!<br /> *Verifique se n&atilde;o ocorreu nenhum erro no processo de incluir faltas*<br /> <br />';
  echo '<link rel="stylesheet" href="../css/gerals.css" type="text/css"> <center> <a href="chamadas.php?id='.$id.'&getperiodo='. $periodo.'&disc='.$disciplina.'&ofer='.$oferecida.'" >Fazer nova chamada</a> | <a href="../prin.php?y=2007" target="_self">HOME</a></center>';

}

function regLog($sql,$status="")
{
  global $conn, $sa_usuario;
  $ip = $_SERVER["REMOTE_ADDR"];
  $pagina = $_SERVER["PHP_SELF"];
  $sql_store = htmlspecialchars("$usuario");
  
  $sqllog = $sql;

  $sqllog .= '(\''.$sql_store.'\',\''.getTime(0).'\',\''.getTime(1).'\','."'$ip','$pagina','$status','')";
  
  $conn->Execute($sqllog);

}


function processaFaltas($Nomes, $f, $sql1, $sql2)
{

  global $conn, $data_chamada, $id, $periodo, $curso , $disciplina, $oferecida, $sem_faltas;

  $res = consulta_sql($sql1);

  if(is_string($res))
  {
        echo $res;
        exit;
  }

  $resposta = '<div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Lan&ccedil;amento de Chamada/Faltas</strong></font></div> <br />';

  $resposta .=  papeleta_header($oferecida);

  $resposta .= $sem_faltas;

	
  if($Nomes != '')
  {
    
    reset($Nomes);
	
    echo '<br /><br /><br />';
	
    while($array_cell = each($Nomes))
    {
	
	  $sqlFaltas = 'BEGIN;';
	  
      $reg_aluno = $array_cell['key'];
	  $num_faltas = $array_cell['value'];
     
	  if($num_faltas > 0 && $num_faltas <= $f) 
	  {
		for ($i = 1; $i <= $num_faltas; $i++)
		{
			$sqlFaltas .= $sql2;
	        $sqlFaltas .= " ('$reg_aluno','$data_chamada','$id','$periodo','$curso','$disciplina','$i','N',$oferecida);";
		}
      
		$resposta .= set_faltas($periodo, $reg_aluno, $disciplina, $oferecida, $num_faltas, 'SOMA', $sqlFaltas, $data_chamada,"<strong>$num_faltas</strong>");

	  }

    }
  }

  echo $resposta;
}


$datadehoje = date ("d/m/Y");


$qrySeqChamada = 'BEGIN; INSERT INTO diario_seq_faltas (id_prof, periodo, curso, disciplina, dia, conteudo, flag, ref_disciplina_ofer) VALUES ';
$qryFaltas = 'INSERT INTO diario_chamadas (ra_cnec, data_chamada, ref_professor, ref_periodo, ref_curso, ref_disciplina, aula, abono, ref_disciplina_ofer) VALUES ';
$qryLog = 'BEGIN; INSERT INTO diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) VALUES ';
$status = 'FALTA REGISTRADA ';


$qryChamada = $qrySeqChamada." ('$id','$periodo','$curso','$disciplina','$data_chamada','$conteudo', '$num_aulas', $oferecida);COMMIT;";

processaFaltas($nomes,$num_aulas,$qryChamada,$qryFaltas);

$st = $status . $aula_tipo;
regLog($qryLog,$st);

showNovaChamada();


?>
