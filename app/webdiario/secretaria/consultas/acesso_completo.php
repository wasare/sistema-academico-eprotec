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
$st='#F3F3F3';
require_once('../../webdiario.conf.php');

// CONECT NO BANCO
///////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

$Data = date("Y-m-d");

// VARS

$sql1 = "select distinct L.usuario, L.data, U.nome_completo from diario_log L, diario_usuarios U where status= 'FALTA REGISTRADA' and L.usuario = U.Login order by U.nome_completo, L.data DESC;";


$query1 = pg_exec($dbconnect, $sql1);

?>
<html>
<head>
  <title>CONSULTAS DE ACESSOS</title>
  <link rel="stylesheet" href="../css/forms.css" type="text/css">
  </head>
 <center><input type="button" onClick="window.print()" value="Imprimir"></center>
 <br>
<p align="center"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">HIST&Oacute;RICO
  DE LAN&Ccedil;AMENTO DE FALTAS NO WEB DIARIO</font><font size="3" face="Verdana, Arial, Helvetica, sans-serif">
 <?PHP
if (pg_numrows($query1) > 0) { ?><table width="70%">
<tr bgcolor="#CCCCCC">
      <td width="90%"><b>Professor</b></td>
      <td width="10%"><b>Data</b></td>
    </tr><?PHP
    while ($row1 = pg_fetch_array($query1)) {
    if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
    $qnome = $row1['nome_completo'];
    //  $qdata = br_date($row1['data']);
    $qdata = $row1['data'];
    $qhora = $row1['hora'];
    print ("<tr bgcolor=\"$st\">
				<td>$qnome</td>
				<td>$qdata</td>
			</tr>");
    }
    } else {
    print ("Não foi encontrado nenhum aluno");
    }
    pg_close($dbconnect);
      ?>
    </table>
         </body>
</html>
