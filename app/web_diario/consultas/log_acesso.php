<?php
/*
====================================
DESENVOLVIDO SOBRE LEIS DA GNU/GPL
====================================

E-CNEC : ti@cneccapviari.br

CNEC CAPVIARI - www.cneccapivari.br
Rua Barão do Rio Branco, 347, Centro - Capivari/SP
Tel.: (19)3492-1869
*/
$st=#F3F3F3;
require_once('../webdiario.conf.php');

// CONECT NO BANCO
$dbconnect = pg_Pconnect("host=$host user=$user password=$password dbname=$database");

$Data = date("d/m/Y");

$usuario = $_SESSION['login'];
// VARS
$sql1 = "select usuario, data, hora from diario_log where usuario = '$usuario' AND data <= '$Data' AND status= 'LOGIN ACEITO' order by data DESC, hora DESC LIMIT 20;";
$query1 = pg_exec($dbconnect, $sql1);



?>
<html>
<head>
  <title>CONSULTAS DE ACESSOS</title>
  <link rel="stylesheet" href="../css/forms.css" type="text/css">
  </head>
  
<p align="center"><b><font size="3" face="Verdana, Arial, Helvetica, sans-serif">ACESSO 
  AO WEB DIARIO <font size="1">(&Uacute;ltimos 20 acessos)</font></font></b></p>
  <?PHP
if (pg_numrows($query1) > 0) { ?><table width="650">
<tr bgcolor="#CCCCCC">
      <td width="100"><b>Usuario</b></td>
      <td width="100"><b>Data</b></td>
      <td width="100"><b>Hora</b></td>
    </tr><?PHP
    while ($row1 = pg_fetch_array($query1)) {
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
    pg_close($dbconnect);
      ?>
      
      </body>
</html>
