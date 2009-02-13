<?php
include_once ('../../../conf/webdiario.conf.php');

$sql1="select d.id, d.descricao_disciplina, d.descricao_extenso, d.carga_horaria from disciplinas d, disciplinas_ofer o where o.ref_periodo = '$getperiodo' and d.id = o.ref_disciplina order by d.descricao_disciplina";
$query1=pg_exec($dbconnect, $sql1);
$sql2=" select a.nota_final as notas, b.nome as nome, c.descricao_disciplina as disc from matricula a, pessoas b, disciplinas c where ref_periodo = '$getperiodo' and a.ref_pessoa = b.id and a.ref_disciplina = c.id order by nome, disc";
///$sql2 = "select round(sum(nota*peso)/sum(peso),1) as notas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(d_ref_disciplina) as disc from diario_notas where id_ref_periodos = '$getperiodo' group by nome, disc order by nome, disc";
// $sql2="SELECT p.nome, d.descricao_disciplina, COUNT(c.ref_disciplina) AS faltas, ROUND (SUM(n.nota * n.peso)/SUM(n.peso),1) AS nota  FROM disciplinas d LEFT OUTER JOIN matricula m ON m.ref_periodo = '$getperiodo'AND m.ref_disciplina = d.id LEFT OUTER JOIN pessoas p ON p.id = m.ref_pessoa LEFT OUTER JOIN diario_chamadas c ON c.ref_disciplina = m.ref_disciplina AND c.ra_cnec = p.ra_cnec AND c.abono <> 'S' LEFT OUTER JOIN diario_notas n ON n.ra_cnec = p.ra_cnec AND n.d_ref_disciplina = m.ref_disciplina GROUP BY p.nome, d.descricao_disciplina, m.ordem_chamada, m.ref_periodo HAVING m.ref_periodo = '$getperiodo' order by m.ordem_chamada, d.descricao_disciplina";
$query2=pg_exec($dbconnect, $sql2);


// $sqlnotas = "select round(sum(nota*peso)/sum(peso),1) as notas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(d_ref_disciplina) as disc from diario_notas where id_ref_periodos = '$getperiodo' group by nome, disc";

?>
<html>
<head>
<title>Consulta Notas por Classe</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
</head>

<body>

<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<table width="100%">
<td align="center"><h2><font face="arial" color="#990000" face="verdana">Consulta de Notas por Classe</h1><br>
<input type="button" value="Imprimir" onClick="window.print()">
<br><br>
</td>
</tr>
</table>
<table width="100%">
<tr bgcolor="#9b9b9b">
<td>NOME</td>
<?php
$totd = 0;
while($row1=pg_fetch_array($query1)) {
   $iddis=$row1["id"];
   $descricaodis2=substr($row1["descricao_disciplina"],0,6);
   $descricaodis=$row1["descricao_disciplina"];
   print ("<td> <div align=center> $descricaodis2 </div></td>");
   $arraydis[] = $descricaodis;
   }
print ("</tr> <tr bgcolor=#9b9b9b>");
print ("<td align=center> </td>");
$dnota = 'Notas';
$dfalta= 'F';
$query1=pg_exec($dbconnect, $sql1);
while($row1=pg_fetch_array($query1)) {
   print ("<td align=center>$dnota</td>");
   }
$totd = count($arraydis);
$totm = $totd;
reset($arraydis);
$i = 0;
$cor1 = "#CCCCCC";
$cor2 = "#FFFFFF";
$cor = $cor2;
print ("</tr> <tr bgcolor=$cor>");
$ultdis = $arraydis[$i];
$ultalu = "*";
$i=100;
while($row1=pg_fetch_array($query2)) {
   $nomealu=$row1["nome"];
   $nomedisc=$row1["disc"];
   $notas=$row1["notas"];
   $notas=media($notas);
   if ($ultalu != $nomealu) {
   if ($i < $totm) {
           while ($i < $totm) {
               print("<td align=center>  </td>");
               $i = $i +1;
               }
        }
        if ($cor=="#FFFFFF") { $cor=$cor1;}else{ $cor=$cor2;}
        print("</tr> <tr bgcolor=$cor>");
        print ("<td width=\"250\">$nomealu</td>");
        $ultalu=$nomealu;
        $i = 0;
        reset($arraydis);
    }
  // antes
   while ($nomedisc != $arraydis[$i]) {
       print("<td align=center>  </td>");
       $i = $i+1;
       }
   if ($nomedisc == $arraydis[$i]) {
   	  print ("<td align=center>$notas</td>");
   	  }else {
       print("<td align=center>  </td>");
      }
   $i = $i +1;
/*
   if ($i >= $totd) {
       if ($cor=="#FFFFFF") { $cor=$cor1;
       }else{ $cor=$cor2;}
       print("</tr> <tr bgcolor=$cor>");
       $totm = $totd;
       $i = 0;
       reset($arraydis);
       }
   if ($totm == $totd) {
     print ("<td width=\"250\">$nomealu</td>");
     }
   if ($i <= $totd) {
     $testa = 0;
     while ($testa == 0) {
      $valor = $arraydis[$i];
      if ($nomedisc == $valor) {
     	  print ("<td align=center>$notas</td>");
     	  print ("<td align=center>$faltas</td>");
          $i = $i +1;
          $testa = 1;
          }else{
          print("<td align=center> - </td>");
          print("<td align=center> - </td>");
          $i = $i + 1;
          }
       $totm=$totm-1;
     }
    } */
 }

print("
</tr>
</table>
<br> <br>
LEGENDA
<br>
<table width=100%>
<tr bgcolor=\"#9b9b9b\">
<td align=center>Descrição Abreviada</td>
<td align=center>Descrição</td>
<td align=\"center\">Carga Horaria Prevista</td>
<td align=\"center\">Carga Horaria Realizada</td>
</tr>
<tr>");
$query1=pg_exec($dbconnect, $sql1);
while($row1=pg_fetch_array($query1)) {
       if ($cor=="#FFFFFF") { $cor=$cor1;
       }else{ $cor=$cor2;}

   $carga=$row1[carga_horaria];
   $descricaodis2=substr($row1["descricao_disciplina"],0,6);
   $descricaodis=$row1["descricao_extenso"];
   $iddisciplina=$row1["id"];
   print ("<tr bgcolor=\"$cor\">");
   print ("<td align=center>$descricaodis2</td>");
   print ("<td align=left>$descricaodis</td>");
   print ("<td align=center>$carga</td>");
   $sqlflag="select flag from diario_seq_faltas where periodo='$getperiodo' and disciplina='$iddisciplina'";
   $queryflag=pg_exec($dbconnect, $sqlflag);
#   $numrows=pg_numrows($queryflag);
   print($numrows);
   while ($rowflag=pg_fetch_array($queryflag)) {
   $flags=$rowflag["flag"];
   if ($flags =='0') {
       $flags=1;     }
   if ($flags == "") {
     $result=$flags;
#    print("<td>$result<br>$flags</td>");
   } elseif ($flags != "") {
     $result=$result+$flags;
#     print("<td>$result<br>$flags</td>");
   }
   }
   print("<td align=\"center\">$result</td>");
   unset($result);
   print ("</tr>");
}
print("</tr> </table>");
?>
<br><br><br>
<?

$total= pg_fetch_array($query1);
$carga2=$total["carga_horaria"];
print("$carga2");


?>


</body>
</html>

