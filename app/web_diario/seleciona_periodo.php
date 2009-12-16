<?php

require_once(dirname(__FILE__) .'/../setup.php');

$conn = new connection_factory($param_conn);

if($_POST['periodo_id']) {
	$_SESSION['web_diario_periodo_id'] = $_POST['periodo_id'];
	echo 'pane_diarios';
}

if($_POST['periodo_coordena_id']) {
    $_SESSION['web_diario_periodo_coordena_id'] = $_POST['periodo_coordena_id'];
	echo 'pane_coordenacao';
}

//$qryPeriodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $periodo_id .'\';';

//$periodo = $conn->get_row($qryPeriodo);

/*
<!--<font color="red" size="4" face="Verdana, Arial, Helvetica, sans-serif"><\?=$periodo['descricao']?></font>-->

if($_POST['send_to']) {

    header("Content-type: text/javascript"); 
	echo '<script language="javascript" type="text/javascript">'; 
	echo "$('pane_overlay').show();  thePane.load_page(". $_POST['send_to'] ."); $(". $_POST['send_to'] .").addClassName('active');$('pane_overlay').hide();";
	echo '</script>';

//  echo $_POST['send_to'];

}
*/
?>
