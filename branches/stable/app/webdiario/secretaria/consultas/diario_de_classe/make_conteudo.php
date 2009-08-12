<?
/* 
 write by Lucas de Camargo Zechim
 	  lzechim@phreaker.net
	  Tue Mar 23 15:48:17 BRT 2004
	
     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License as
     published by the Free Software Foundation; either version 2 of
     the License, or (at your option) any later version.
*/
?>
<html>
<head>
<link rel="stylesheet" href="../../css/forms.css" type="text/css">



<style type="text/css">
<!--
.td1 {
	border: 1px solid #000000
};

.td2 {
	border: 0px solid #000000
};



.centro { 
	text-align: center
};


-->
</style>
<script languague="JavaScript" type="text/JavaScript">
<!--

function MM_openBrWindow(theUrl,winName,features) { //2.1
	window.open(theUrl,winName,features);
}
//-->
</script>

</head>

<?
include ("../../webdiario.conf.php");

/* cria a data pra consulta */
#$ano = Date(Y);
#$ano = "2003";
$vardate = "$ano-$selectmes-%";
$hoje = Date('d' . '/' . 'm' . '/' .  'Y');


///////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("kill the base");
$sql1="select dia, conteudo from diario_seq_faltas where disciplina='$getdisciplina' and periodo='$getperiodo'";

$query1=pg_exec($dbconnect, $sql1);
$con = pg_num_rows($query1);


if ($con == "0") {
	print("<font size=\"3\" color=\"#FF0000\">Nenhuma chamada efetuada esse mês");
} else {



$sql4= "select b.ref_professor from disciplinas_ofer a, disciplinas_ofer_prof b where a.ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' and a.id = b.ref_disciplina_ofer";
$query4=pg_exec($dbconnect, $sql4);

while($ab=pg_fetch_array($query4)) { 
	$abc = $ab['ref_professor'];
}
$sql5 = "select nome from pessoas where id = '$abc'";
$query5 = pg_exec($dbconnect, $sql5);



    $mes=$selectmes;
      switch ($mes) {
           case "01":
           $mesdesc = "Janeiro";
           break;
           case "02":
           $mesdesc = "Fevereiro";
           break;
           case "03":
           $mesdesc = "Março";
           break;
           case "04":
           $mesdesc = "Abril";
           break;
           case "05":
           $mesdesc = "Maio";
           break;
           case "06":
           $mesdesc = "Junho";
           break;
           case "07":
           $mesdesc = "Julho";
           break;
           case "08":
           $mesdesc = "Agosto";
           break;
           case "09":
           $mesdesc = "Setembro";
           break;
           case "10":
           $mesdesc = "Outubro";
           break;
           case "11":
           $mesdesc = "Novembro";
           break;
           case "12":
           $mesdesc = "Dezembro";
           break;
           }  ;

/* welcome to the jungle */

/* conta números de chamadas */
#$num_chamadas = pg_num_rows($query2);
#$num_chamadas2 = pg_num_rows($query2);
#$num_chamadas = $num_chamadas * 2;


function br_dias($date) {
  $dia = Substr($date, 8, 2);
  $mes = Substr($date, 5, 2);
  $ano = Substr($date, 0, 4);
  $newdate = $dia . '/' . $mes;
  return $newdate;
}

?>
<body <? if ($i == "1") { ?>
	onLoad="window.print()"
<?}?>
>

<table width="100%" border="1" cellspacing="0" cellpadding="3" class="td1">
   <tr>
     <td colspan="2" class="centro" width="20%"><font size="2"><div align="center"><b>Diario de Classe</b></font></td>
</tr>
<tr>
     <td  colspan="2" align="center"><font size="2"><b><?echo $getperiodo; ?> /  <?

$sql6="select descricao_disciplina from disciplinas where id='$getdisciplina'";
$query6=pg_exec($dbconnect, $sql6);

while($row6=pg_fetch_array($query6)){
        $descricao=$row6["descricao_disciplina"];
	print("$descricao");

}

?></b></font></td>
</tr>

<tr bgcolor="#cccccc">
<td class="center"><div align="center"><font size="2"><b>Data</b></font></td>
<td class="center"><div align="center"><font size="2"><b>Conteudo</b></font></td>
</tr>

<?
$st="#F3F3F3";
while($row1=pg_fetch_array($query1)) {
	if ($st == '#F3F3F3') {$st = '#E3E3E3';} else {$st ='#F3F3F3';}
	$dia=br_date($row1["dia"]);
	$conteudo=$row1["conteudo"];
	print ("<tr bgcolor=\"$st\">\n
		<td width=\"15%\"><div align=\"center\">$dia</td>\n
		<td width=\"85%\">$conteudo</td>\n
		</tr>");
}
?>
</table>

<table width="100%" border="0" height="70" class="td2">
       <tr>
	   <td colspan="2" height="30">Obs.:<br><br><br></td>
	 </tr>
	<tr>
		<td height="20"><b>Data: </b><?echo $hoje;?></td>
		<td><div align="center">_______________________________________________</div></td>
         </tr>
		<td height="20">&nbsp;</td>
		<td><div align="center"><?

 while($row5=pg_fetch_array($query5)) {
				$nome123=$row5['nome'];
			print ("$nome123");
			}
?></div></td>

	</tr>
</table>


<center>
<?if ($i != "1") { ?>
<br><br>
<input type="submit" value="Imprimir" onClick="MM_openBrWindow('make_conteudo.php?getperiodo=<?echo $getperiodo;?>&getdisciplina=<?echo $getdisciplina;?>&selectmes=<?echo $selectmes;?>&i=1&ano=<?echo $ano;?>','imprimir','scrollbars=yes,width=760,height=400,top=0,left=0')">
<br><br>
<?} }?>
</body>
</html>
