<?php

include_once('../conf/webdiario.conf.php');
	

$getofer = $_GET['ofer'];


// INVERTE A MARCACAO DE ESTADO DO DIARIO
$sql1 = "SELECT
            fl_concluida
		 FROM
			disciplinas_ofer
         WHERE
            id = $getofer;";

$qry1 = consulta_sql($sql1);

if(is_string($qry1))
{
    echo $qry1;
    exit;
}
else {

   $fl_concluida = pg_fetch_result($qry1,0);

   if($fl_concluida === 'f') {
		$flag = 't';
   }
   else {
		$flag = 'f';
   }	
}


// MARCA/DESMARCA O DIARIO COMO CONCLUIDO
$sql2 = "UPDATE 
			disciplinas_ofer
         SET
            fl_concluida = '$flag' 
         WHERE  
            id = $getofer;";

//echo $sql2; die;

$res = consulta_sql($sql2);

if(is_string($res))
{
	echo $res;
	exit;
}
else {

    //	echo '<script language=javascript>  window.alert(\'Diário marcado/desmarcado com sucesso!\'); javascript:window.history.back(1); </script>';
	
	echo '<script language=javascript>javascript:window.history.back(1); </script>';
	
	exit;
}

	
?>
