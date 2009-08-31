
<?php
require_once("../config.class.php");
require_once("../modelo/clsRelatorio.class.php");

$datainicial = $_POST['txtDataInicial'];
$datafinal = $_POST['txtDataFinal'];
$refeicao = $_POST['dpdRefeicao'];

$SQL = "";

$rel = new clsRelatorio();
$rel->RelSomatorio($datainicial, $datafinal, $refeicao);

?>