<?php

$BASE_DIR_WEBDIARIO  = dirname(__FILE__) . '/';

require_once($BASE_DIR_WEBDIARIO .'../../configs/configuracao.php');

@session_start();

ini_set('display_errors',1);

date_default_timezone_set('America/Sao_Paulo');

$BASE_URL = $BASE_URL .'app/webdiario/';

require_once($BASE_DIR_WEBDIARIO .'conf/conn_diario.php');

$CSS_DIR = $BASE_URL .'css/';

/*Define onde está a página de erro*/
$erro = $BASE_URL .'erro.php';

$speriodo = $_SESSION['lst_periodo'];

// PERIODOS/PROFESSORES LIBERADOS PARA ACESSO 
//$L['20072'] = array('2530','2522','2524','2513','2516','2515','2491','2521');
//Secretaria: 1406,4487
$L['20071'] = array('1406','4487','2530','2957');
$L['20062'] = array('1406','4487','2530','2960','2851');
$L['20061'] = array('1406','4487');
$L['20052'] = array('1406','4487');


// ACOES QUE MODIFICAM OS DIARIOS
$Movimento = array('0','1','3','4','8','10');

// PERIODOS ATUALMENTE LIBERADOS PARA OS PROFESSORES
$Autorizado = array('20031','20032','20041','20042','20051','20052','20061','20062','','20071','20072','20081','20082','20091');


$P['20091'] = "'09','0901','09011','09012'";

$P['20082'] = "'08','0802','08021','08022'";
$P['20081'] = "'08','0801','08011','08012'";

$P['20071'] = "'07','07011','07011.F','07011.I','07012','07012.F','07012.I','0701','0701A','0701.F','0701.I','0701.EJA'";
$P['20072'] = "'07','07021','07022','0702'";

$P['20061'] = "'0601','06011','06012'";
$P['20062'] = "'0602','06021','06022'";

$P['20051'] = "'07','07011','07011.F','07011.I','07012','07012.F','07012.I','0701','0701A','0701.F','0701.I','0701.EJA'";
$P['20052'] = "'07021','07022','0702'";

$P['20041'] = "'07','07011','07011.F','07011.I','07012','07012.F','07012.I','0701','0701A','0701.F','0701.I','0701.EJA'";
$P['20042'] = "'07021','07022','0702'";

$P['20031'] = "'07','07011','07011.F','07011.I','07012','07012.F','07012.I','0701','0701A','0701.F','0701.I','0701.EJA'";
$P['20032'] = "'07021','07022','0702'";


// INCLUI FUNCOES 
require_once($BASE_DIR_WEBDIARIO .'lib/functions_diario.php');


?>
