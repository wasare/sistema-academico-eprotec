<?php
	
include_once('../../../webdiario.conf.php');

/*
$var = explode(":",$_GET[getperiodo]);
$getperiodo = $var[0];
$getofer = $var[1];
*/

$getperido = $_GET['getperiodo'];

$id = $_GET['id'];

/*
$sql1 ="SELECT DISTINCT
         d.id, d.descricao_disciplina, d.descricao_extenso, d.carga_horaria 
         FROM disciplinas d, disciplinas_ofer_prof e, disciplinas_ofer o 
         WHERE o.ref_periodo = '$getperiodo' 
         AND d.id = o.ref_disciplina
         AND e.ref_professor = '$id' 
         ORDER BY d.descricao_disciplina";
*/         
$sql1 = "SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso || '  ' || '(' || o.id || ')' as descricao_extenso,
                d.carga_horaria,
                o.id as idof
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                o.id = f.ref_disciplina_ofer AND
                o.ref_periodo = '$getperiodo' AND
                o.is_cancelada = 0 AND
                d.id = o.ref_disciplina
                ORDER BY d.descricao_disciplina;";


//f.ref_professor = '$id' AND
				
/*
b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
*/         
// echo $sql1;

$query1 = pg_exec($dbconnect, $sql1);
//$sql2="SELECT p.nome, d.descricao_disciplina, COUNT(c.ref_disciplina) AS faltas, ROUND (SUM(n.nota * n.peso)/SUM(n.peso),1) AS nota  FROM disciplinas d LEFT OUTER JOIN matricula m ON m.ref_periodo = '$getperiodo'AND m.ref_disciplina = d.id LEFT OUTER JOIN pessoas p ON p.id = m.ref_pessoa LEFT OUTER JOIN diario_chamadas c ON c.ref_disciplina = m.ref_disciplina AND c.ra_cnec = p.ra_cnec AND c.abono <> 'S' LEFT OUTER JOIN diario_notas n ON n.ra_cnec = p.ra_cnec AND n.d_ref_disciplina = m.ref_disciplina GROUP BY p.nome, d.descricao_disciplina, m.ordem_chamada, m.ref_periodo HAVING m.ref_periodo = '$getperiodo' order by m.ordem_chamada, d.descricao_disciplina";
$sql2 = "SELECT DISTINCT 
         p.nome, m.ordem_chamada, m.ref_pessoa 
         FROM matricula m, pessoas p 
         WHERE m.ref_periodo = '$getperiodo' 
         AND p.id = m.ref_pessoa 
         AND m.dt_cancelamento ISNULL 
         ORDER BY 2 ";
// $sql2 = "select count(ra_cnec) as faltas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(ref_disciplina) as disc from diario_chamadas where ref_periodo = '$getperiodo' and abono <> 'S' group by nome, disc order by nome, disc";
$query2 = pg_exec($dbconnect, $sql2);

// $sqlfaltas = "select count(ra_cnec) as faltas, pessoa_ra(ra_cnec) as nome, descricao_disciplina_sucinto(ref_disciplina) as disc from diario_chamadas where ref_periodo = '$getperiodo' group by nome, disc";


?>
<html>
<head>
<title>Consulta de Notas e Faltas por Classe</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
<script languague="JavaScript" type="text/JavaScript">
<!--

function MM_openBrWindow(theUrl,winName,features) { //2.1
	window.open(theUrl,winName,features);
}
//-->
</script>
</head>
<body <? if ($botao == "1") { ?>
	onLoad="window.print()"
<?}?>
>
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<div align="center">
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>CEFET-BAMBU&Iacute;<br>
 CONSULTA DE NOTAS E FALTAS</strong></font></p>
      <p align="left"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">PER&Iacute;ODO: <?echo $getperiodo;?></font></strong></p>
<?php
if($botao != "1") 
{
?>
<input type="submit" value="Imprimir" onClick="MM_openBrWindow('consultaclasse.php?getperiodo=<?echo $getperiodo;?>&botao=1','imprimir','scrollbars=yes,width=760,height=400,top=0,left=0')">
</div>
<?
}
$cor1 = "#8FB5DA";
$cor2 = "#FFFFFF";
$cor = $cor2;
print(" <b>LEGENDA</b>
<br>
<table width=100%>
<tr bgcolor=\"#336699\">
<td align=center><b><font color=\"#FFFFFF\">Descri��o Abreviada</td>
<td align=center><b><font color=\"#FFFFFF\">Descri��o</td>
<td align=\"center\"><b><font color=\"#FFFFFF\">Carga Horaria Prevista</td>
<td align=\"center\"><b><font color=\"#FFFFFF\">Carga Horaria Realizada</td></font></b></tr>
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
       while ($rowflag=pg_fetch_array($queryflag)) {
             $flags=$rowflag["flag"];
             if ($flags == "") {
                $result=$flags;
                } elseif ($flags != "") {
                $result=$result+$flags;   }
       }
       print("<td align=\"center\">$result</td>");
       unset($result);
       print ("</tr>");
}
print("</tr> </table><br><br>");

print('<table width="100%">
<tr bgcolor="#336699"><td><div align="center"><strong><font color="#FFFFFF"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">NOME</font></font></strong></div></td>');
$query1=pg_exec($dbconnect, $sql1);
while($row1=pg_fetch_array($query1)) {
   $iddis=$row1["id"];
   $descricaodis2=substr($row1["descricao_disciplina"],0,6);
   $descricaodis=$row1["descricao_disciplina"];
   print ('<td colspan="2"> <div align="center"><strong><font color="#FFFFFF"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$descricaodis2.'</font></font></strong></div></td>');
   }
print ("</tr>");
print("<tr bgcolor=#FFFFFF><td>&nbsp;</td>");
$query1=pg_exec($dbconnect, $sql1);
while($row1=pg_fetch_array($query1)) {
   print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">N</font></div></td>');
   print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">F</font></div></td>');
   }
print ("</tr>");
$query2=pg_exec($dbconnect, $sql2);
while($row2=pg_fetch_array($query2)) {
       if ($cor=="#FFFFFF") { $cor=$cor1;
       }else{ $cor=$cor2;}
       $numero=$row2["ordem_chamada"];
       $nomealuno=$row2["nome"];
       $refpessoa=$row2["ref_pessoa"];
       print ("<tr bgcolor=\"$cor\">");
       print ("<td align=left>$numero - $nomealuno</td>");
       $query1=pg_exec($dbconnect, $sql1);
       while($row1=pg_fetch_array($query1)) {
             $iddis=$row1["id"];
             $sqlbusca="select nota_final, num_faltas from matricula where ref_periodo = '$getperiodo' and ref_pessoa = '$refpessoa' and ref_disciplina = '$iddis'";
             $querybusca=pg_exec($dbconnect, $sqlbusca);
             $numlinhas=pg_num_rows($querybusca);
             if ($numlinhas == "0") {
                 print ('<td align=center>N/C</td><td align=center>N/C</td>');}
             else{
                  while($row3=pg_fetch_array($querybusca)) {
                       $nota=$row3["nota_final"];
                       $falta=$row3["num_faltas"];
                       if ($nota < 7 ) {
                       print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#CC0000">'.$nota.'</font></strong></font></div></td>');
                       print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$falta.'</font></div></td>');
                       } else {
                       print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$nota.'</font></div></td>');
                       print ('<td> <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$falta.'</font></div></td>');
                       }
                       }

             }
       }
       print("</tr>");
    }
print("
</table>
<br>
</body>
</html>");
?>
