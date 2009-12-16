<?php

require_once(dirname(__FILE__) .'/../../setup.php');

if(empty($_SESSION['web_diario_periodo_coordena_id']))
{
        echo '<script language="javascript">
                window.alert("ERRO! Primeiro informe um per�odo!");
                window.close();
        </script>';
        exit;
}

$conn = new connection_factory($param_conn);

// RECUPERA INFORMACOES SOBRE DO PROFESSOR E SEUS PERIODOS
$qry_periodos = 'SELECT DISTINCT o.ref_periodo,p.descricao FROM disciplinas_ofer o, periodos p WHERE  o.ref_periodo = p.id AND o.ref_curso IN (SELECT DISTINCT ref_curso FROM coordenadores WHERE ref_professor = '. $sa_ref_pessoa .') ORDER BY ref_periodo DESC;';

$periodos = $conn->get_all($qry_periodos);
// ^ RECUPERA INFORMACOES SOBRE O PROFESSOR E SEUS PERIODOS ^ //

$qry_periodo = 'SELECT id, descricao FROM periodos WHERE id = \''. $_SESSION['web_diario_periodo_coordena_id'].'\';';
$periodo = $conn->get_row($qry_periodo);

?>

<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

<script type="text/javascript" src="<?=$BASE_URL .'lib/prototype.js'?>"> </script>
</head>

<body bgcolor="#FFFFFF" text="#000000" >
<center>
<div align="left">
<br />
  
<strong>
			<font size="4" face="Verdana, Arial, Helvetica, sans-serif">
				Per&iacute;odo de coordena&ccedil;&atilde;o ativo: 
				<font color="red" size="4" face="Verdana, Arial, Helvetica, sans-serif"><?=$periodo['descricao']?></font>
			</font>
</strong>
<br /> <br /> <br />

<h3>Selecione o per&iacute;odo com o qual deseja trabalhar:</h3>
<br />

<?php
	foreach($periodos as $p)
	{
		echo '<a href="#" title="Per&iacute;odo '. $p['descricao'] .'" alt="Per&iacute;odo '. $p['descricao'] .'" onclick="set_periodo(\'periodo_coordena_id='. $p['ref_periodo'] .'\');">'. $p['descricao'] .'</a><br />';
	}

?>
<br /><br />
</form>

</body>
</head>
</html>
