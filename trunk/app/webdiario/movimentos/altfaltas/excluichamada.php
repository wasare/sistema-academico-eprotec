<?php

include_once('../../conf/webdiario.conf.php');

$disciplina = $_POST['disc'];
$getofer = $_POST['ofer'];
$data = $_POST['senddata'];
$oferecida = $getofer;
$periodo = $_SESSION['periodo'];
$id = $_SESSION['id'];



// EXCLUI TODAS AS FALTAS ANTERIORES PARA A CHAMADA

$sqlExcluiFaltas = " SELECT id, ra_cnec
                     FROM
                        diario_chamadas a
                    WHERE
                        (a.ref_periodo = '$periodo') AND
                        (a.ref_disciplina_ofer = '$oferecida') AND
                        (a.data_chamada = '$data');";


$qryExcluiFaltas = consulta_sql($sqlExcluiFaltas);

if(is_string($qryExcluiFaltas))
{
   echo $qryExcluiFaltas;
   exit;
}

$ExcluiFaltas = pg_fetch_all($qryExcluiFaltas);

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


$sql1 = "BEGIN; DELETE 
         FROM 
            diario_seq_faltas 
         WHERE  
            periodo = '$periodo' AND 
            ref_disciplina_ofer = $getofer AND   
            dia = '$data'; COMMIT;";

//id_prof = '$id' AND
// disciplina = '$disciplina' AND

$res = consulta_sql($sql1);

if(is_string($res))
{
	echo $res;
	exit;
}


echo '<script language=javascript>  window.alert(\'Foram excluídos com sucesso \n os registros referente ao dia ' . $data . '\'); javascript:window.history.back(1); </script>';
exit;

	
?>
