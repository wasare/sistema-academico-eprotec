<?
include ('../../conf/webdiario.conf.php');
// CONECTA BD
//////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");


$sql1="select d.id, d.descricao_disciplina, d.descricao_extenso, d.carga_horaria from disciplinas d, disciplinas_ofer o where o.ref_periodo = '$getperiodo' and d.id = o.ref_disciplina order by d.descricao_disciplina";
$query1=pg_exec($dbconnect, $sql1);
$sql2="SELECT p.nome, d.descricao_disciplina, COUNT(c.ref_disciplina) AS faltas, ROUND (SUM(n.nota * n.peso)/SUM(n.peso),1) AS nota  FROM disciplinas d LEFT OUTER JOIN matricula m ON m.ref_periodo = '$getperiodo'AND m.ref_disciplina = d.id LEFT OUTER JOIN pessoas p ON p.id = m.ref_pessoa LEFT OUTER JOIN diario_chamadas c ON c.ref_disciplina = m.ref_disciplina AND c.ra_cnec = p.ra_cnec AND c.abono <> 'S' LEFT OUTER JOIN diario_notas n ON n.ra_cnec = p.ra_cnec AND n.d_ref_disciplina = m.ref_disciplina GROUP BY p.nome, d.descricao_disciplina, m.ordem_chamada, m.ref_periodo HAVING m.ref_periodo = '$getperiodo' order by m.ordem_chamada, d.descricao_disciplina";
$sql3="SELECT d.descricao_disciplina, COUNT(c.ref_disciplina) AS faltas, ROUND (SUM(n.nota * n.peso)/SUM(n.peso),1) AS nota FROM disciplinas d LEFT OUTER JOIN matricula m ON m.ref_periodo = '$getperiodo'AND m.ref_disciplina = d.id LEFT OUTER JOIN pessoas p ON p.id = m.ref_pessoa LEFT OUTER JOIN diario_chamadas c ON c.ref_disciplina = m.ref_disciplina AND c.ra_cnec = p.ra_cnec AND c.abono <> 'S' LEFT OUTER JOIN diario_notas n ON n.ra_cnec = p.ra_cnec AND n.d_ref_disciplina = m.ref_disciplina GROUP BY p.nome, d.descricao_disciplina, m.ordem_chamada, m.ref_periodo HAVING m.ref_periodo = '$getperiodo' order by m.ordem_chamada, d.descricao_disciplina";
$query2=pg_exec($dbconnect, $sql2);
$query3=pg_exec($dbconnect, $sql2);
$query4=pg_exec($dbconnect, $sql3);

?>
<html>
<head>
<title>Consulta Alunos e Faltas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
</head>

<body>

<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<table width="100%">
<td align="center"><h2><font face="arial" color="#990000" face="verdana">Consulta de Notas & Faltas</h1><br>
<input type="button" value="Imprimir" onClick="window.print()">
<br><br>
</td>
</tr>
</table>
<table width="100%">
<tr bgcolor="#9b9b9b">
<td>NOME</td>
<?
while ($row_d=pg_fetch_array($query1)) {
	$disp=$row_d["descricao_disciplina"];
	$disp_array=$row_d["descricao_disciplina"];
	$array_disp[]=$disp_array;
	print ("<td colspan=\"2\" align=\"center\">$disp</td>\n");
}
?>
<tr bgcolor="#9b9b9b">
<td>&nbsp;</td>
<?
$ndisp=count($array_disp);
for ($i = 0; $i < $ndisp; $i++) {
	print ("<td align=\"center\">F</td>\n
		     <td align=\"center\">N</td>");
}
?>
</tr>
<tr bgcolor="#9b9b9b">
<td>&nbsp;</td>
<?
#$tes=count($array_disp);
#print("$tes<br>");
while($row_a=pg_fetch_row($query4)) {
	for ($j=0; $j <= $ndisp; $j++) {
#		print("$row_a[$j]<br>");
		if ($row_a[$j] == $array_disp[$j]) {
			$j=$j+1;
			print("<td>$row_a[$j]</td>\n");
#			$j=$j+1;
#			print("<td>$row_a[$j]</td>\n");
		} else {
			print("<td> - </td>\n");
#			print("<td> - </td>\n");
		}
		
	}
}

?>
</body>
</html>

