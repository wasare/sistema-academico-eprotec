<?php
# write by lucas@cneccapivari.br
# this is a free software, you can modify follow the GNU/GPL

include_once('../conf/webdiario.conf.php');

$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

//$ref_prof = $id;

if(isset($_SESSION['select_prof']) && is_numeric($_SESSION['select_prof']) ) {

	    $id = $_SESSION['select_prof'];
}
		

$ref_prof = $id;

$sql3 = "SELECT 
         b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas 
         FROM matricula a, pessoas b 
         WHERE		 	
			(a.dt_cancelamento is null) AND
            a.ref_periodo = '$getperiodo' AND 
            a.ref_disciplina_ofer = '$getofer' AND
            a.ref_pessoa = b.id 
         ORDER BY lower(to_ascii(nome));" ;


		 
//echo $sql3; die; // a.ref_contrato IN(SELECT id FROM contratos WHERE dt_desativacao is null) AND

//a.ref_disciplina = '$getdisciplina' AND

         
$sql4 = "SELECT 
         a.descricao_extenso, a.carga_horaria, b.nome 
         FROM disciplinas a, pessoas b
         WHERE a.id = '$getdisciplina' ";
         
if($ref_prof != 0)
{
       $sql4 .=  "AND b.id = $ref_prof;";
}

else
{
      $sql4 .=  ";";
}


/*
$sql5 = " SELECT 
            DISTINCT 
               a.id, a.descricao as descricao, b.id, c.descricao as periodo 
            FROM 
               cursos a, disciplinas_ofer b, periodos c 
            WHERE 
               b.ref_periodo = '$getperiodo' AND 
               c.id = '$getperiodo' AND 
               b.ref_curso = a.id AND 
               b.id = '$getofer';";
*/ 
//echo $sql5;
         
$qry3 = consulta_sql($sql3);

if(is_string($qry3))
{
	echo $qry3;
	exit;
}

$qry4 = consulta_sql($sql4);

if(is_string($qry4))
{
   echo $qry4;
   exit;
}


$sql5 = " SELECT fl_digitada, fl_concluida
            FROM
                disciplinas_ofer
            WHERE
               id = '$getofer';";


$qry5 = consulta_sql($sql5);

if(is_string($qry5))
{
    echo $qry5;
    exit;
}
else {

	$flag = pg_fetch_array($qry5,0);
/*
    echo $flag;
    echo '<br />';

    print_r($flag);
*/
    $fl_digitada = $flag[0];
    $fl_concluida = $flag[1];
}


?>

<html>
<head>
<title>papeletas</title>
</head>

<link rel="stylesheet" href="../css/forms.css" type="text/css">
<font size="2">

<?php
/*
while($row5 = pg_fetch_array($qry5)) 
{
   $classe = $row5['descricao'];
   $periodo = $row5['periodo'];
   $ofcod = $row5['id'];
   break;
   print("Curso: <b>$classe</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />");
   print("Per&iacute;odo: <b>$periodo</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
}

*/
while($row4 = pg_fetch_array($qry4)) 
{
	$dis = $row4['descricao_extenso'];
	$prof = $row4['nome'];
	$cargap = $row4['carga_horaria'];
	break;
	print("Disciplina: <b>$dis ($ofcod)</b><br>");
	print("Professor(a): <b>$prof</b><br><br>");
}

/*
print("Curso: <b>$classe</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />");
print("Disciplina: <b>$dis ($ofcod)</b><br>");
print("Per&iacute;odo: <b>$periodo</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />");
print("Professor(a): <b>$prof</b><br><br>");
*/

echo getHeaderDisc($getofer);

    if($fl_digitada == 'f' && $fl_concluida == 'f') {
        $fl_situacao = '<font color="green"><b>Aberto</b></font>';
    }
    else {
        if($fl_concluida == 't') {
            $fl_situacao = '<font color="blue"><b>Conclu&iacute;do</b></font>';
        }

        if($fl_digitada == 't') {
            $fl_situacao = '<font color="red"><b>Finalizado</b></font>';
            $fl_encerrado = 1;
        }
    }

echo 'Situa��o: ' . $fl_situacao;

if( $fl_digitada == 'f') {

	echo '<br /><font color="red" size="-2"><strong>SEM VALOR COMO DOCUMENTO, PASS�VEL DE ALTERA��ES</strong></font>';

}


$FaltaMax = $cargap * 0.25;
//echo $FaltaMax;

?>
</font>
<table width="80%" cellspacing="0" cellpadding="0" class="papeleta">	
	<tr bgcolor="#cccccc">
		<td width="5%"><b>N&ordm;</b></td>
		<td width="10%"><b>Matr&iacute;cula</b></td>
		<td width="40%"><b>Nome</b></td>
		<td align="center"><b>N1</b></td>
		<td align="center"><b>N2</b></td>
		<td align="center"><b>N3</b></td>
		<td align="center"><b>N4</b></td>
		<td align="center"><b>N5</b></td>
		<td align="center"><b>N6</b></td>
		<?php
		   if(eregi("06", $getperiodo) || eregi("07", $getperiodo) || eregi("08", $getperiodo) || eregi("09", $getperiodo) ) { 
				echo '<td align="center"><b>N. Extra</b></td>';
			}
		?>
		<td align="center"><b>Total</b></td>
		<td align="center"><b>Faltas</b></td>
	</tr>
<?php


$sqlflag ="SELECT
                  SUM(CAST(flag AS INTEGER)) AS carga
               FROM
                  diario_seq_faltas
               WHERE
                  periodo = '$getperiodo' AND
                  ref_disciplina_ofer = $getofer; ";

//disciplina = $getdisciplina AND

$qryflag = consulta_sql($sqlflag);
     
if(is_string($qryflag))
{
	echo $qryflag;
	exit;
}

$rowflag = pg_fetch_array($qryflag);

$result = $rowflag['carga'];

if( $result < 1 )
{
    $result = 0;
}

$i = 0;
$No = 1;

$r1 = '#FFFFFF';
$r2 = '#FFFFCC';


while($row3=pg_fetch_array($qry3)) 
{
	$nome_f = $row3["nome"];
   	$racnec = $row3["ra_cnec"];
   	$racnec = str_pad($racnec, 5, "0", STR_PAD_LEFT) ;
   	$num = $row3["ordem_chamada"];
   
   	if ($row3["num_faltas"] > 0)
   	{
    	$falta = $row3["num_faltas"];
   	}
   	else
   	{
    	$falta = '0';
   	}

   	if($falta > $FaltaMax) 
	{
		$falta = "<font color=\"red\"><b>$falta</b></font>";
	}
   
   	if($row3['nota_final'] != 0) 
	{    
		$nota = getNumeric2Real($row3["nota_final"]); 
	}
	else { 
		$nota = $row3['nota_final'];
	}

	if ($nota < 60) 
   	{
    	$nota = "<font color=\"red\"><b>$nota</b></font>";
   	}
   
   	//<td width=\"10%\">$num</td>\n
   	if ( ($i % 2) == 0)
   	{
    	$rcolor = $r1;
   	}
   	else
   	{
    	$rcolor = $r2;
   	}
   	
	print("<tr bgcolor=\"$rcolor\">\n"); 
   	print ("<td align=\"center\">".$No++."</td>\n ");
   	print(" <td width=\"10%\" align=\"center\">$racnec</td>\n <td width=\"40%\">$nome_f</td>\n "); 
   
	
	//   -- RECUPERA AS NOTAS PARCIAIS POR ALUNO
	$sqlnotas = 'SELECT DISTINCT 
    b.nome, c.nota, ref_diario_avaliacao
  	FROM 
    matricula a, pessoas b, diario_notas c 
  	WHERE 
    a.ref_periodo = \''.$getperiodo.'\' AND 
    a.ref_disciplina_ofer = \''.$getofer.'\' AND
    b.ra_cnec = c.ra_cnec AND
    c.d_ref_disciplina_ofer = \''.$getofer.'\' AND
    a.ref_pessoa = b.id AND
    b.ra_cnec = '.$racnec.'
  	ORDER BY 3;';
	
	//a.ref_disciplina = \''.$getdisciplina.'\' AND

	//echo $sqlnotas; die;
	
	$qrynotas = consulta_sql($sqlnotas);
	
	if(is_string($qrynotas))
	{
  		echo $qrynotas;
  		exit;
	}

	
	$total_nota_webdiario = 0;
	
	while($row=pg_fetch_array($qrynotas))
	{
   		$N = $row['nota'];
	
   		if($N < 0)
   		{
       		$N = '-';
   		}
   
	   	if($N > 0) { 
   			$N = getNumeric2Real($N); 
		}
   	
		//somatorio nota web diario
		$total_nota_webdiario += $N;
		
   		print ("<td align=\"center\">$N</td>\n ");
	}


   	//print ("<td align=\"center\">$total_nota_webdiario</td>\n ");
	
	print ("<td align=\"center\">$nota</td>\n ");
   	print ("<td align=\"center\">$falta</td>\n ");
   
   	print("</tr>\n ");
   
   	$i++;

}

?>

</table>
<hr width="60%" size="1" align="left" color="#FFFFFF">

<?php
	
print("Aulas dadas: <b>$result</b>&nbsp;&nbsp;&nbsp;");
print("Aulas previstas na estrututa curricular: <b>$cargap</b><br>");
print("<center>ASSINATURA(S):</center>");

?>
<br><br>
<input type="button" value="Imprimir" onClick="window.print()">
</body>
</html>