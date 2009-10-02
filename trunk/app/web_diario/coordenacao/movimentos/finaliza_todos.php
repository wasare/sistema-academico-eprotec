<?php

require_once('../../webdiario.conf.php');
require_once($BASE_DIR_WEBDIARIO .'conf/verifica_acesso.php');	

$getofer = $_GET['ofer'];
$curso_id = $_GET['curso_id'];
$periodo_id = $_GET['periodo_id'];


// FIXME: nao fechar diarios sem prefessor
// MARCA O DIARIO COMO CONCLUIDO
$sql = "SELECT COUNT(*) 
			FROM
            disciplinas_ofer
         WHERE
            fl_concluida = 't' AND
            ref_curso = $getcurso AND
			ref_periodo = '$getperiodo' AND
            is_cancelada = '0';";

//echo $sql;die;

$qry1 = consulta_sql($sql);

if(is_string($qry1))
{
   echo $qry1;
   exit;
}
else {
	//$num_com = pg_numrows($qry1);
	$num_concluida = pg_fetch_result($qry1,0);
	//echo $num_concluida; die;
	
	if($num_concluida == 0) {
	
		echo '<script language=javascript>  window.alert(\'Não existe nenhum diário concluído a ser encerrado!\'); javascript:window.history.back(1); </script>';
    	//echo '<script language=javascript>javascript:window.history.back(1); </script>';
    	die;
    }

}

			
$sql1 = "UPDATE 
			disciplinas_ofer
         SET
            fl_digitada = 't' 
         WHERE  
		    fl_concluida = 't' AND
			ref_curso = $getcurso AND
            ref_periodo = '$getperiodo' AND 
            is_cancelada = '0';";

//echo $sql1;die;
$res = consulta_sql($sql1);

if(is_string($res))
{
	echo $res;
	exit;
}
else {

	echo '<script language=javascript>  window.alert(\''.$num_concluida.' diário(s) encerrado(s) com sucesso!\'); javascript:window.history.back(1); </script>';
	
	//echo '<script language=javascript>javascript:window.history.back(1); </script>';

	exit;
}

	
?>
