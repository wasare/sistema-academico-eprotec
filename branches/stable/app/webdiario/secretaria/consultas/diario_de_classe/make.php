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

function br_dias($date) {
  $dia = Substr($date, 8, 2);
  $mes = Substr($date, 5, 2);
  $ano = Substr($date, 0, 4);
  $newdate = $dia . '/' . $mes;
  return $newdate;
}

function get_mes($date) {
  $ano = Substr($date, 0, 4);
  $mes = Substr($date, 5, 2);
  $newdate = $ano . '-' . $mes;
  return $newdate;
}
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
#$ano = "2005";
$vardate = "$ano-$selectmes-%";
$vardate2 = "$ano-$selectmes";
$hoje = Date('d' . '/' . 'm' . '/' .  'Y');

////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("kill the base");


$sql2 = "select distinct dia, flag from diario_seq_faltas where dia like '$vardate' and disciplina = '$getdisciplina' and periodo = '$getperiodo'";
$query2 = pg_exec($dbconnect, $sql2);



$con = pg_num_rows($query2);

$cat4= "select b.ref_professor from disciplinas_ofer a, disciplinas_ofer_prof b where a.ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' and a.id = b.ref_disciplina_ofer";
$query4=pg_exec($dbconnect, $cat4);


if ($paginas == "1") {
	$limit_in="39";
	$limit_out="0";
}  elseif ($paginas == "2") {
	$limit_in="10";
	$limit_out="39";
} elseif ($paginas == "3") {
	$limit_in="100";
	$limit_out="49";
} else {
	$paginas="sem";
}




if ($con == "0") {
	print("<font size=\"3\" color=\"#FF0000\">Nenhuma chamada efetuada esse mês");
} else {


if ($paginas != "sem") { 
	$mais="limit $limit_in,$limit_out"; 
}


$cat3="select b.nome, b.ra_cnec, a.ordem_chamada, ref_motivo_cancelamento, dt_cancelamento  from matricula a, pessoas b where a.ref_periodo='$getperiodo' and a.ref_disciplina='$getdisciplina' and a.ref_pessoa=b.id order by ordem_chamada " . "$mais";
$cade=pg_exec($dbconnect, $cat3);


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
$num_chamadas = pg_num_rows($query2);
$num_chamadas2 = pg_num_rows($query2);


$sql_flag = "select SUM(to_number(flag, 999)) from diario_seq_faltas where dia like '$vardate' and disciplina = '$getdisciplina' and periodo = '$getperiodo'";
$query_flag = pg_exec($dbconnect, $sql_flag);
   while($row_flag=pg_fetch_array($query_flag)) {
         $num_chamadas=$row_flag["flag"];
}

$num_chamadas = $num_chamadas * 2 + 1 ;


?>
<body <? if ($i == "1") { ?>
	onLoad="window.print()"
<?}?>
>

<table width="100%" border="1" cellspacing="0" cellpadding="3" class="td1">
   <tr>
     <td colspan="2" class="centro"><font size="2"><div align="center"><b>Diario de Classe</b></font></td>
     <td colspan="<?echo $num_chamadas;?>" align="center"><font size="2"><b><?echo $mesdesc;?></b></font></td>
</tr>
<tr>
     <td colspan="2" align="center"><font size="2"><b><?echo $getperiodo; ?> /  <?

$sql6="select descricao_disciplina from disciplinas where id='$getdisciplina'";
$query6=pg_exec($dbconnect, $sql6);

while($row6=pg_fetch_array($query6)){
        $descricao=$row6["descricao_disciplina"];
	print("$descricao");

}

?></b></font></td>
<?
while($row2=pg_fetch_array($query2)) { 
	$date = $row2["dia"];
	$flag = $row2["flag"];
	$date2 = br_dias($row2["dia"]);
	$datas[]=$date;
	print("<td colspan=\"$flag\" align=\"center\">$date2</td>\n");


}
?>
<td align="center"><b>Total de Faltas</b></td>
</tr>
<?

while($row3=pg_fetch_array($cade)) {
	$nome_aluno=$row3["nome"];
	$num_chamada=$row3["ordem_chamada"];
	$ra_cnec=$row3["ra_cnec"];
	$dt_cancelamento=get_mes($row3["dt_cancelamento"]);
	$motivo_trancamento=$row3["ref_motivo_cancelamento"];
	print("<tr><td width=\"5%\" align=\"center\">$num_chamada</td>\n
		    <td width=\"40%\">$nome_aluno</td>");




	if (($motivo_trancamento != "") && ($dt_cancelamento < $vardate)) {

		if ($motivo_trancamento == "3") {
		print("<td colspan=\"$num_chamadas\" align=\"center\"><b>TRANCAMENTO DE MATRÍCULA</td></tr>");
		}
		if ($motivo_trancamento == "5") {
		print("<td colspan=\"$num_chamadas\" align=\"center\"><b>CANCELAMENTO DE MATRICULA</td></tr>");
		}
		if ($motivo_trancamento == "4") {
			print("<td colspan=\"$num_chamadas\" align=\"center\"><b>TRANSFERÊNCIA INTERNA EXPEDIDA</td></tr>\n");		
		}
		if ($motivo_trancamento == "6") {
		print("<td colspan=\"$num_chamadas\" align=\"center\"><b>TRANSFERÊNCIA INTERNA RECEBIDA</td></tr>");
		}
		if ($motivo_trancamento == "8") {
		print("<td colspan=\"$num_chamadas\" align=\"center\"><b>TRANSFERÊNCIA EXTERNA RECEBIDA</td></tr>");
		}
		if ($motivo_trancamento == "7") {
		print("<td colspan=\"$num_chamadas\" align=\"center\"><b>TRANSFERÊNCIA EXTERNA EXPEDIDA</td></tr>");
		}
	} else {

	$x=0;
	while($x < $num_chamadas2) {
	$a=$datas[$x];
	$select_flag="select flag from diario_seq_faltas where dia='$a' and periodo='$getperiodo' and disciplina='$getdisciplina'";
	$query_flags = pg_exec($dbconnect, $select_flag);
	while($row_flags=pg_fetch_array($query_flags)) {
		$flags=$row_flags["flag"];
	}
//	print("$flags");


	if ($flags >= "1") {
	$sql1 = "select data_chamada, ra_cnec, aula, abono, ref_disciplina from diario_chamadas where data_chamada='$a' and ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' and ra_cnec = '$ra_cnec' and aula = '1'";
	$query1 = pg_exec($dbconnect, $sql1);
    $num1 = pg_num_rows($query1);
    }
    
   	if ($flags >= "2") {
	$sql12 = "select data_chamada, ra_cnec, aula, abono, ref_disciplina from diario_chamadas where data_chamada='$a' and ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' and ra_cnec = '$ra_cnec' and aula = '2'";
	$query12 = pg_exec($dbconnect, $sql12);
    $num2 = pg_num_rows($query12);
     }
    
   	if ($flags >= "3") {
   	$sql13 = "select data_chamada, ra_cnec, aula, abono, ref_disciplina from diario_chamadas where data_chamada='$a' and ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' and ra_cnec = '$ra_cnec' and aula = '3'";
	$query13 = pg_exec($dbconnect, $sql13);
    $num3 = pg_num_rows($query13);
    }

   	if ($flags == "4") {
	$sql14 = "select data_chamada, ra_cnec, aula, abono, ref_disciplina from diario_chamadas where data_chamada='$a' and ref_periodo = '$getperiodo' and ref_disciplina = '$getdisciplina' and ra_cnec = '$ra_cnec' and aula = '4'";
	$query14 = pg_exec($dbconnect, $sql14);
    $num4 = pg_num_rows($query14);
    }


if ($flags >= "1") {
	if ($num1 == "0") {
 		print ("<td width=\"5%\" align=\"center\">*</td>");
	}
}

if ($flags >= "2") {
	if ($num2 == "0") {
 		print ("<td width=\"5%\" align=\"center\">*</td>");
	}
}

if ($flags >= "3") {
	if ($num3 == "0") {
 		print ("<td width=\"5%\" align=\"center\">*</td>");
	}
}
	
if ($flags == "4") {
	if ($num4 == "0") {
 		print ("<td width=\"5%\" align=\"center\">*</td>");
	}
}


if ($flags >= "1") {
	while($aula1=pg_fetch_array($query1)) { 
		$data_aula1 = $aula1["data_chamada"];
		$ra_cnec_aula1 = $aula1["ra_cnec"];
		$aula_aula1 = $aula1["aula"];
		$abono_aula1 = $aula1["abono"];
		if ($abono_aula1 == "S") { 
			print("<td width=\"5%\" align=\"center\">A</td>");
		} else {
			print("<td width=\"5%\" align=\"center\">F</td>");
			$total++;
		}
	}
}

if ($flags >= "2") {
	while($aula2=pg_fetch_array($query12)) { 
		$data_aula2 = $aula2["data_chamada"];
		$ra_cnec_aula2 = $aula2["ra_cnec"];
		$aula_aula2 = $aula2["aula"];
		$abono_aula2 = $aula2["abono"];	
		if ($abono_aula2 == "S") { 
			print("<td width=\"5%\" align=\"center\">A</td>");
		} else {
			print("<td width=\"5%\" align=\"center\">F</td>");
			$total++;
		}
	}
}

if ($flags >= "3") {
	while($aula3=pg_fetch_array($query13)) {
		$data_aula3 = $aula3["data_chamada"];
		$ra_cnec_aula3 = $aula3["ra_cnec"];
		$aula_aula3 = $aula3["aula"];
		$abono_aula3 = $aula3["abono"];
		if ($abono_aula3 == "S") {
			print("<td width=\"5%\" align=\"center\">A</td>");
		} else {
			print("<td width=\"5%\" align=\"center\">F</td>");
			$total++;
		}
	}
}


if ($flags == "4") {
	while($aula4=pg_fetch_array($query14)) {
		$data_aula4 = $aula4["data_chamada"];
		$ra_cnec_aula4 = $aula4["ra_cnec"];
		$aula_aula4 = $aula4["aula"];
		$abono_aula4 = $aula4["abono"];
		if ($abono_aula4 == "S") {
			print("<td width=\"5%\" align=\"center\">A</td>");
		} else {
			print("<td width=\"5%\" align=\"center\">F</td>");
			$total++;
		}
	}
	}
	
	$x++;	

	$asdf="1";
	
	}	
	if ($total != "") {
	print ("<td align=\"center\">$total</td>\n
			</tr>");
		unset($total);
	} else {
		print("<td align=\"center\">0</td></tr>\n");
	}
	}
   }


?>

</tr>
</table>
<br><br>

<table width="100%" border="0" height="70" class="td2">
       <tr>
	   <td colspan="2" height="30">Obs.:<br><br><br></td>
	 </tr>
	<tr>
		<td height="20"><b>Data: </b><?echo $hoje;?></td>
		<td><div align="center">_______________________________________________</div></td>
         </tr>
		<td height="20">&nbsp;</td>
		<td><div align="center">
<?
 while($row5=pg_fetch_array($query5)) {
	$nome123=$row5['nome'];
	print ("$nome123");
}
?></div></td>

	</tr>
</table>


<center>

<br><br>
<?
if ($i != "1") {
?>
<input type="submit" value="Imprimir Pág 1" onClick="MM_openBrWindow('make.php?getperiodo=<?echo $getperiodo;?>&getdisciplina=<?echo $getdisciplina;?>&selectmes=<?echo $selectmes;?>&i=1&ano=<?echo $ano;?>&paginas=1','imprimir','scrollbars=yes,width=760,height=400,top=0,left=0')">
<input type="submit" value="Imprimir Pág 2" onClick="MM_openBrWindow('make.php?getperiodo=<?echo $getperiodo;?>&getdisciplina=<?echo $getdisciplina;?>&selectmes=<?echo $selectmes;?>&i=1&ano=<?echo $ano;?>&paginas=2','imprimir','scrollbars=yes,width=760,height=400,top=0,left=0')">
<input type="submit" value="Imprimir Pág 3" onClick="MM_openBrWindow('make.php?getperiodo=<?echo $getperiodo;?>&getdisciplina=<?echo $getdisciplina;?>&selectmes=<?echo $selectmes;?>&i=1&ano=<?echo $ano;?>&paginas=3','imprimir','scrollbars=yes,width=760,height=400,top=0,left=0')">
<br><br>
<?
}
}

pg_close($dbconnect);?>
</body>
</html>
