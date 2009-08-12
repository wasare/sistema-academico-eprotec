<?
include ('../../webdiario.conf.php');
// CONECTA BD
////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");


$sql1="select d.id, d.descricao_disciplina, d.descricao_extenso from disciplinas d, disciplinas_ofer o where o. ref_periodo = '$getperiodo' and d.id = o.ref_disciplina order by d.descricao_disciplina";
$query1=pg_exec($dbconnect, $sql1);
$sql2="SELECT p.nome, d.descricao_disciplina, COUNT(c.ref_disciplina) AS faltas, ROUND (SUM(n.nota * n.peso)/SUM(n.peso),1) AS nota  FROM disciplinas d LEFT OUTER JOIN matricula m ON m.ref_periodo = '$getperiodo'AND m.ref_disciplina = d.id LEFT OUTER JOIN pessoas p ON p.id = m.ref_pessoa LEFT OUTER JOIN diario_chamadas c ON c.ref_disciplina = m.ref_disciplina AND c.ra_cnec = p.ra_cnec AND c.abono <> 'S' LEFT OUTER JOIN diario_notas n ON n.ra_cnec = p.ra_cnec AND n.d_ref_disciplina = m.ref_disciplina GROUP BY p.nome, d.descricao_disciplina, m.ordem_chamada, m.ref_periodo HAVING m.ref_periodo = '$getperiodo' order by m.ordem_chamada, d.descricao_disciplina";
$query2=pg_exec($dbconnect, $sql2);

print('
<html>
<head>
<title>Consulta Alunos e Faltas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="742" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr>
    <td rowspan="2"><div align="center"><strong>NOME DO ALUNO</strong></div></td>
	<!-- NOME 1 MATERIA-->');
    $totd = 0;
while($row1=pg_fetch_array($query1)) {
   $iddis=$row1["id"];
   $descricaodis2=substr($row1["descricao_disciplina"],0,6);
   $descricaodis=$row1["descricao_disciplina"];
   print ("<td colspan=\"3\"><div align=\"center\"><strong><font color=\"#000000\">$descricaodis2</font></strong></div></td>");
   $arraydis[] = $descricaodis;
   }
	print('<!-- FIM NOME MATERIA-->
  </tr>
  <tr>
  <!-- DESCRICAO 1 MATERIA-->');
  $query1=pg_exec($dbconnect, $sql1);
while($row1=pg_fetch_array($query1)) {
print ('<td width="119"><div align="center"><strong><font size="1">M&Eacute;DIA ATUAL</font></strong></div></td>
        <td width="123"><div align="center"><strong><font size="1">TOTAL DE FALTAS</font></strong></div></td>
        <td width="119"><div align="center"><strong><font size="1">CARGA HOR&Aacute;RIA</font></strong></div></td>
        <!--acaba aqui a tag materia-->
         </tr>');
  }
  $totd = count($arraydis);
$totm = $totd;
reset($arraydis);
$i = 0;
$cor1 = "#CCCCCC";
$cor2 = "#FFFFFF";
$cor = $cor2;
//print ("</tr> <tr bgcolor=$cor>");
while($row1=pg_fetch_array($query2)) {
   $nomealu=$row1["nome"];
   $nomedisc=$row1["descricao_disciplina"];
   $faltas=$row1["faltas"];
   $notas=$row1["nota"];
   if ($i >= $totd) {
       if ($cor=="#FFFFFF") { $cor=$cor1;
       }else{ $cor=$cor2;}
      // print("</tr> <tr bgcolor=$cor>");
       $totm = $totd;
       $i = 0;
       reset($arraydis);
       }
   if ($totm == $totd) {
     print ('<!-- LINHA RESULTADOS -->
  <tr bgcolor="#CCCCCC">
    <td width="371" height="19">'.$nomealu.'</td>');
     }
   if ($i <= $totd) {
     $testa = 0;
     while ($testa == 0) {
      $valor = $arraydis[$i];
      if ($nomedisc == $valor) {
      print ('<!-- MATERIA RESULTADOS -->
    <td>
      <div align="center"><font color="#000000">'.$notas.'</font></div></td>
    <td>
      <div align="center">'.$faltas.'</div></td>
    <td>
      <div align="center">450 Horas</div></td>
      <!--FIM LINHA RESULTADOS -->');
          $i = $i +1;
          $testa = 1;
          }else{
      print ('<!-- MATERIA RESULTADOS -->
    <td>
      <div align="center"><font color="#000000">NA</font></div></td>
    <td>
      <div align="center">NA</div></td>
    <td>
      <div align="center">450 Horas</div></td>
      <!--FIM LINHA RESULTADOS -->');
          $i = $i + 1;
          }
       $totm=$totm-1;
     }
    }
 }
?>

</table>
<p>GERADO EM 10/10/2003 &Agrave;S 10:20:30 HORAS -- WEB DIARIO</p>
</body>
</html>

