<?php

header("Cache-Control: no-cache");

//-- ARQUIVO E BIBLIOTECAS
require_once("../../lib/common.php");
require_once("../../configuracao.php");
require_once("../../lib/adodb/adodb.inc.php");

//-- Conectando com o PostgreSQL
$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


//-- PARAMETROS
$aluno_id    = $_GET['id']; // matricula do aluno
$diarios  = explode("|", $_GET['d']); // diarios a ajustar quando for mais de um separ�-los por um |

/* 
 Exemplos de URLs para efetiva��o do ajuste de nota e/ou faltas

	ajusta_notas_faltas.php?d=2483|2484|2485|2486|2487|2488&id=2735
	ajusta_notas_faltas.php?d=2483&id=2735
*/

// SOMENTE EFETUA AJUSTE SE EXISTIR PELO MENOS UM DIARIO E UM ALUNO
if ( is_numeric(count($diarios)) AND count($diarios) > 0 AND is_numeric($aluno_id))
{
    $diarios_ajustados = '';
	// ATUALIZA NOTAS E FALTAS CASO O DIARIO TEM SIDO INICIALIZADO 
	//-- Conectando com o PostgreSQL
	// FIXME: migrar para conexao ADODB
	if(($conn = pg_Pconnect("host=$host user=$user password=$password dbname=$database")) == false)
	{
   		$error_msg = "N�o foi poss�vel estabeler conex�o com o Banco: " . $database;
	}
	require_once('atualiza_diario_matricula.php');

	foreach($diarios as $diario) {
		atualiza_matricula("$aluno_id","$diario");
        $diarios_ajustados .=  $diario .'  ';
	}

	// ^ ATUALIZA NOTAS E FALTAS CASO O DIARIO TEM SIDO INICIALIZADO ^ //
} //^ SOMENTE EFETUA AJUSTE SE EXISTIR PELO MENOS UM DIARIO E UM ALUNO ^//

$cabecalho = ">> <strong>Aluno</strong>: $aluno_id <br />";
$cabecalho .= ">> <strong>Di&aacute;rios</strong>: $diarios_ajustados <br />";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SA</title>
<link href="../../Styles/formularios.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
  <h1>Ajuste de Notas e Faltas</h1>
  <div class="box_geral"> 
	<?=$title?>
       <?=$cabecalho?>
  </div>
</body>
</html>
