<?php

require_once("../modelo/clsFila.class.php");

$data = $_POST['txtData'];
$refeicao = $_POST['txtRefeicao'];

$fila = new clsFila();
echo '<strong> RELA��O DE INTERVALOS </strong><br/>';
echo 'data: '.$data;
echo '<br/>refei��o: '.$refeicao;

echo '<br/> <br />';
$fila->PegaIntervalos($data,$refeicao);
echo '<br/> <br />';


?>