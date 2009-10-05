<?php
require_once('../../setup.php');

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

$conn = new connection_factory($param_conn);

$sql1 = 'select usuario, data, hora from diario_log where usuario = \''. $uid .'\' AND data <= \''. date("d/m/Y") .'\' AND status = \'LOGIN ACEITO\' order by data DESC, hora DESC LIMIT 20;';

$logs_acesso = $conn->adodb->getAll($sql1);

if($logs_acesso === FALSE || !is_array($logs_acesso))
{
    die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}

?>
<html>
<head>
  <title><?=$IEnome?> - log acesso</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">
  </head>
  
<p align="center"><b><font size="3" face="Verdana, Arial, Helvetica, sans-serif">Acesso ao Web Di&aacute;rio <font size="1">(&Uacute;ltimos 20 acessos)</font></font></b></p>

<br />

<?php

if (count($logs_acesso) > 0) { ?>
<table width="80%" class="papeleta">
<tr bgcolor="#CCCCCC">
      <td width="100"><b>Usu&aacute;rio</b></td>
      <td width="100"><b>Data</b></td>
      <td width="100"><b>Hora</b></td>
    </tr>
<?php    
	foreach($logs_acesso as $row1) 
	{
		if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
		$qnome = $row1['usuario'];
		$qdata = $row1['data'];
		$qhora = $row1['hora'];
		print ("<tr bgcolor=\"$st\">
				<td>$qnome</td>
				<td>$qdata</td>
				<td>$qhora</td>
			</tr>");
    }
} else {
    print ("Não foi encontrado nenhum registro");
}
?>
      
      </body>
</html>
