<?php
/*
====================================
DESENVOLVIDO SOBRE LEIS DA GNU/GPL
====================================

E-CNEC : ti@cneccapviari.br

CNEC CAPVIARI - www.cneccapivari.br
Rua Barï¿½o do Rio Branco, 347, Centro - Capivari/SP
Tel.: (19)3492-1869
*/
$st = '#F3F3F3';

include_once('../webdiario.conf.php');

//print_r($_SESSION);

if(!IsSet($_SESSION['login'])) 
{
   header("location:$erro");
   exit;
} 
else 
{


$ras = $_GET['aluno'];

if(isset($ras) && is_numeric($ras) && $ras != "") {
	$btnOK = true;
}

$cs = $_GET['cs'];

if(isset($cs) && is_numeric($cs) && $cs != "") {
	$btnOK = true;
}
		
$sql1 = "SELECT DISTINCT
	d.id, s.descricao as periodo, d.descricao_disciplina as descricao, d.carga_horaria, m.ref_periodo, 
	m.num_faltas as faltas, m.nota_final as nota_final, m.nota as nota, m.ref_disciplina_ofer as oferecida
	FROM 
		matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s  
	WHERE 
		m.ref_pessoa = p.id AND 
		p.ra_cnec = $ras AND 
		m.ref_curso = $cs AND 
		m.dt_matricula >= '2004-01-01' AND
		m.ref_disciplina_ofer = o.id AND 
		d.id = o.ref_disciplina AND
		o.is_cancelada = 0 AND
		s.id = o.ref_periodo
	ORDER BY 2, 3";

//o.is_cancelada = \'0\' Verifica se a disciplina foi cancelada

/*$sql1 = "SELECT d.id, d.descricao_disciplina as descricao, d.carga_horaria, m.ref_periodo, m.num_faltas as faltas,
             m.nota_final as nota_final, m.nota as nota, m.ref_disciplina_ofer as oferecida
             FROM matricula m, disciplinas d, pessoas p
             WHERE
             m.ref_pessoa = p.id AND p.ra_cnec = $ras AND m.ref_curso = $cs AND m.dt_matricula >= '2004-01-01' AND
             d.id = m.ref_disciplina ORDER BY 2;";*/



$qry1 = consulta_sql($sql1);

if(is_string($qry1)) {

     echo $qry1;
     exit;
}


?>

<html>
<head>
<title>Web Di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
<body>
<br>
<h2>Ficha Acad&ecirc;mica</h2>
<?php 

	if (pg_numrows($qry1) > 0) { 
?>
      <font color="#000000" size="2">
      	<b>Matr&iacute;cula: </b><?php echo($ras);?><b> Nome: </b><?php echo($_GET['nome']);?>
      </font><br>
	  <font color="#000000" size="2">
      	<b>Curso: </b><?php echo($_GET['curso']);?><br />
      	<b>Data: </b> <?php echo date("d/m/Y"); ?> 
      	<b>Hora: </b><?php  echo date("H:i"); ?>
      </font>
      <br><br>	  
      
      <table width="80%" cellpadding="0" cellspacing="0" class="papeleta">
        <tr bgcolor="#666666">
        <td width="14%"><div align="center"><font color="#FFFFFF"><b>Per&iacute;odo</b></font></div></td>
        <td width="60%"><div align="center"><font color="#FFFFFF"><b>Componente Modular</b></font></div></td>
        <td width="8%"><div align="center"><font color="#FFFFFF"><b>M&eacute;dia</b></font></div></td>
        <td width="8%"><div align="center"><font color="#FFFFFF"><b>Faltas</b></font></div></td>
	    <td width="20%"><div align="center"><font color="#FFFFFF"><b>% Faltas</b></font></div></td>
	    <td width="15%"><div align="center"><font color="#FFFFFF"><b>CH Realizada</b></font></div></td>
  	    <td width="15%"><div align="center"><font color="#FFFFFF"><b>CH Prevista</b></font></div></td>
       </tr>
<?php

//VARIAVEIS --

//nota total aprovado
$notaAprovado = 0;
//contador
$contAprovado = 0;
//percentual de faltas
$percFaltasAprovado = 0;
//carga horaria realizada
$chRealizadaAprovado = 0;



while($row2 = pg_fetch_array($qry1)) {


	$iddisc = $row2['id'];
    $nomemateria = $row2['descricao'];
	$periodo = $row2['periodo'];
    
	
	//CODIGO RETIRADO DA PAPELETA papeleta.php
    if($row2['nota_final'] != 0) 
	{ 
		$notafinal = getNumeric2Real($row2['nota_final']); 
	}
	else { 
		$notafinal = $row2['nota_final'];
	}
   	//FIM CODIGO PAPELETA
	
	
	//$notafinal = getNumeric2Real($row2['nota_final']);
    
	$faltasmateria = $row2['faltas'];
    $classe = $row2['ref_periodo'];
    $aulaprev = $row2['carga_horaria'];
    $oferecida = $row2['oferecida'];
               
    if ($faltasmateria == 0) 
	{
    	$faltasmateria='-';   
    }
 
	$sqlflag ="SELECT SUM(CAST(flag AS INTEGER)) AS carga
               FROM 
                  diario_seq_faltas 
               WHERE 
                  periodo = '$classe' AND 
                  disciplina = '$iddisc' AND 
                  ref_disciplina_ofer = $oferecida; ";
                  
    //echo '<br />'.$sqlflag; 
    // and ref_disciplina_ofer = '$getofer'
    //$qryflag = pg_exec($dbconnect, $sqlflag);

    $qryflag = consulta_sql($sqlflag);

    if(is_string($qryflag)) {
    
	   	echo $qry1;
     	exit;
    }

       
    $rowflag = pg_fetch_array($qryflag);
       
    $res = $rowflag['carga'];

    if ($res <> "") 
	{
    
		$perfaltas = ($faltasmateria * 100) / $res;
        $pfaltas = substr($perfaltas,0,5);
        $stfaltas = getNumeric2Real($pfaltas);
    
	}
    else 
	{
		$pfaltas = '-'; $stfaltas=$pfaltas;
	}
       
	   
	   
	//Reprovado
	//Se as faltas estao acima de 25% e a nota menor que 60
    if ($pfaltas > 25 || $notafinal < 60) 
	{ 
       	
		$fcolor = '#FF0000';
	} 
	//Disciplinas Aprovadas
	else 
	{
		
		$contAprovado++;
		//total notas aprovado
		$notaAprovado += $notafinal;
		//total percentual de faltas
		$percFaltasAprovado += $stfaltas;
		//Total carga horaria realizada
		$chRealizadaAprovado += $res;
		
		$fcolor = '#000000';
	}
	
	
		
    //if () { $fcolor = '#FF0000';} else {$fcolor = '#000000';}

	if ($res == "") { $res = 0;}
	   
		print ("<tr bgcolor=\"$st\">
        <td><font color=$fcolor>$periodo</font></td>
		<td><font color=$fcolor>$iddisc - $nomemateria</font></td>
		<td align=center><font color=$fcolor>$notafinal</font></td>
        <td align=center><font color=$fcolor>$faltasmateria</font></td>
        <td align=center><font color=$fcolor>$stfaltas %</font></td>
        <td align=center><font color=$fcolor>$res</font></td>
        <td align=center><font color=$fcolor>$aulaprev</font></td>
        </tr>");
		$res = "";

	if ($st == '#F3F3F3') 
	{ 		
		$st = '#E3E3E3';
	} 
	else 
	{  
		$st ='#F3F3F3';
	 }
	  
}//fim while



//INFORMACOES --

//Media nas disciplinas aprovadas
$notaMediaAprovado = $notaAprovado / $contAprovado;

//Convertendo para o padrao decimal - Media nas disciplinas aprovadas
$notaMediaAprovado = number_format($notaMediaAprovado,'2',',','.');

//Media percentual de faltas das disciplinas aprovadas
$percFaltasAprovado = $percFaltasAprovado / $contAprovado;

//Convertendo para o padrao decimal - Media percentual de faltas das disciplinas aprovadas
$percFaltasAprovado = number_format($percFaltasAprovado,'2',',','.');
 
 
 
?>

</table>

<p>&nbsp;</p>
<table width="379" border="0" cellspacing="0" cellpadding="0" class="papeleta">
  <tr bgcolor="666666">
    <td height="24" colspan="2" bgcolor="666666">
    	<span class="style1"><b>Informa&ccedil;&otilde;es:</b><br>    
    	</span></td>
  </tr>
  <tr>
    <td width="321">M&eacute;dia da nota nas disciplinas aprovadas:</td>
    <td width="58" align="right">&nbsp;<?php echo $notaMediaAprovado; ?></td>
  </tr>
  <tr>
    <td>M&eacute;dia percentual de faltas das disciplinas aprovadas: </td>
    <td align="right">&nbsp;<?php echo $percFaltasAprovado . ' %'; ?></td>
  </tr>
  <tr>
    <td>Total carga hor&aacute;ria realizada nas disciplinas aprovadas: </td>
    <td align="right">&nbsp;<?php echo $chRealizadaAprovado;?></td>
  </tr>
</table>

<p>
  <input type="button" value="Imprimir" onClick="window.print()">
</p>
</body>
</html>
<?php 
	
	} 
	else {
		print("<font color=\"#FF0000\" size=\"2\"><b>Esse aluno não possui notas e faltas</b></font>"); }
	} 
	//print_r($_SESSION);

?>
