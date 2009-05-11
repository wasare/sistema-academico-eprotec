<?php

  //ARQUIVO DE CONFIGURACAO E CLASSE ADODB
  header ("Cache-Control: no-cache");
  require("../../lib/common.php");
  require("../../configuracao.php");
  require("../../lib/adodb/adodb.inc.php");
  require("../../lib/adodb/tohtml.inc.php");
  

  $Conexao = NewADOConnection("postgres");
  $Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

	$ras = $_GET['aluno'];
	
	if(isset($ras) && is_numeric($ras) && $ras != "") {
		$btnOK = true;
	}
	
	$cs = $_GET['cs'];
	
	if(isset($cs) && is_numeric($cs) && $cs != "") {
		$btnOK = true;
	}
		
	$sql1 = "SELECT DISTINCT
	d.id, 
	s.descricao as periodo, 
	d.descricao_disciplina as descricao, 
	d.carga_horaria, 
	m.ref_periodo, 
	m.num_faltas as faltas, 
	m.nota_final as nota_final, 
	m.nota as nota, 
	m.ref_disciplina_ofer as oferecida
	FROM 
		matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s  
	WHERE 
		m.ref_pessoa = p.id AND 
		p.ra_cnec = $ras AND 
		m.ref_curso = $cs AND 
		m.dt_matricula >= '2004-01-01' AND
		m.ref_disciplina_ofer = o.id AND 
		d.id = o.ref_disciplina AND

		s.id = o.ref_periodo
	ORDER BY 2, 3";
	
	//echo $sql1;	die;
	
	//EXECUTANDO A SQL COM ADODB
  	$Result1 = $Conexao->Execute($sql1);
	
	//CONTANTO O NUMERO DE RESULTADOS
  	$num_result = $Result1->RecordCount();

?>
<html>
<head>
<title>SA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../Styles/style.css" rel="stylesheet" type="text/css">
<body>
<div style="width: 760px;">
<div align="center" style="text-align:center; font-size:12px;">
	<img src="../../images/armasbra.jpg" width="57" height="60"><br />
	MEC-SETEC<br />
	CENTRO FEDERAL DE EDUCA&Ccedil;&Atilde;O TECNOL&Oacute;GICA DE BAMBU&Iacute;-MG<br />
    SETOR DE REGISTROS ESCOLARES
    <br /><br /><br />
</div>
<h2>Ficha Acad&ecirc;mica</h2>
<font color="#000000" size="2"> <b>Matr&iacute;cula: </b><?php echo($ras);?> <b> Nome: </b><?php echo($_GET['nome']);?> </font><br>
<font color="#000000" size="2"> <b>Curso: </b><?php echo($_GET['curso']);?><br />
<b>Data: </b> <?php echo date("d/m/Y"); ?> <b>Hora: </b><?php echo date("H:i"); ?> </font><br>
<br>
<table width="700" cellpadding="0" cellspacing="0" class="tabela_relatorio">
  <tr bgcolor="#666666">
    <th width="14%"><div align="center"><font color="#FFFFFF"><b>Per&iacute;odo</b></font></div></th>
    <th width="60%"><div align="center"><font color="#FFFFFF"><b>Componente Modular</b></font></div></th>
    <th width="8%"><div align="center"><font color="#FFFFFF"><b>M&eacute;dia</b></font></div></th>
    <th width="8%"><div align="center"><font color="#FFFFFF"><b>Faltas</b></font></div></th>
    <th width="20%"><div align="center"><font color="#FFFFFF"><b>% Faltas</b></font></div></th>
    <th width="15%"><div align="center"><font color="#FFFFFF"><b>CH Realizada</b></font></div></th>
    <th width="15%"><div align="center"><font color="#FFFFFF"><b>CH Prevista</b></font></div></th>
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
  

while(!$Result1->EOF) {

	$iddisc = $Result1->fields[0];
    $nomemateria = $Result1->fields[2];
	$periodo = $Result1->fields[1];
	$faltasmateria = $Result1->fields[5];
    $classe = $Result1->fields[4];
    $aulaprev = $Result1->fields[3];
    $oferecida = $Result1->fields[8];
    
	
	$notafinal = $Result1->fields[6];
   	
	if($notafinal == ''){
		$notafinal = ' - ';
	}  
               
    if ($faltasmateria == 0) { 
		$faltasmateria='-';
	}
 
 
 	//SQL COM CARGA HORARIA REALIZADA
	$sqlflag ="
	SELECT SUM(CAST(flag AS INTEGER)) AS carga
    FROM 
    diario_seq_faltas 
    WHERE 
    periodo = '$classe' AND 
    disciplina = '$iddisc' AND 
    ref_disciplina_ofer = $oferecida; ";
  	
	//EXECUTANDO A SQL COM ADODB
  	$Result2 = $Conexao->Execute($sqlflag);
	
    $res = $Result2->fields[0];

	
    if ($res <> "") {
    	$perfaltas = ($faltasmateria * 100) / $res;
        $pfaltas = substr($perfaltas,0,5);
		
		$stfaltas = $pfaltas;
        //$stfaltas = getNumeric2Real($pfaltas) . ' %';
    }
    else {
		$pfaltas = '-'; $stfaltas=$pfaltas;
	}
    
	
	//VERIFICANDO APROVACAO POR FALTAS
    if ($pfaltas > 25 || $notafinal < 60) { 
		$fcolor = '#FF0000';
	}
	else {
		
		$contAprovado++;
		//total notas aprovado
		$notaAprovado += $notafinal;
		//total percentual de faltas
		$percFaltasAprovado += $stfaltas;
		//Total carga horaria realizada
		$chRealizadaAprovado += $res;
		
		$fcolor = '#000000';
	}
	
	
	//VERIFICA SE A CARGA HORARIA REALIZADA ESTA NULA
	if ($res == "") { 
		$res = 0;
	}
	
	if ($st == '#F3F3F3') {
   		$st = '#E3E3E3';
	}
	else {
		$st ='#F3F3F3';
	}
	
	   
	print ("<tr bgcolor=\"$st\">
        <td><font color=$fcolor>$periodo</font></td>
		<td><font color=$fcolor>$iddisc - $nomemateria</font></td>
		<td align=center><font color=$fcolor>$notafinal</font></td>
        <td align=center><font color=$fcolor>$faltasmateria</font></td>
        <td align=center><font color=$fcolor>$stfaltas</font></td>
        <td align=center><font color=$fcolor>$res</font></td>
        <td align=center><font color=$fcolor>$aulaprev</font></td>
        </tr>");
		
	$res = "";

	$Result1->MoveNext();
	
}//FIM WHILE


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
</div>

<br /><br />

<table width="379" border="0" cellspacing="0" cellpadding="0" class="tabela_relatorio">
  <tr bgcolor="666666">
    <th height="24" colspan="2">
    	<b>Informa&ccedil;&otilde;es:</b><br>    
    </th>
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

</body>
</html>
