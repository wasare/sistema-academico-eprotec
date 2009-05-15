<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");

//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//print_r($_SESSION);

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

//echo $sql1;
//die;

//EXECUTANDO A SQL COM ADODB
$Result1 = $Conexao->Execute($sql1);

//CONTANTO O NUMERO DE RESULTADOS
$num_result = $Result1->RecordCount();


//html2pdf
ob_start();


?>
<link
	href="../../desenvolvimento/Styles/style.css"
	rel="stylesheet" type="text/css">
<page backtop="10mm"
	backbottom="10mm">
<page_header></page_header>
<page_footer>
<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
	<tr>
		<td style="text-align: left; width: 50%">&nbsp;</td>
		<td style="text-align: right; width: 50%">página
		[[page_cu]]/[[page_nb]]</td>
	</tr>
</table>
</page_footer>
<span style="text-align: center; font-size: 12px;"> <img
	src="../../images/armasbra.jpg" width="57" height="60"><br />
MEC-SETEC<br />
CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
SETOR DE REGISTROS ESCOLARES <br />
<br />
<br />
</span>
<h2 style="font-size: 16px;">Ficha Acad&ecirc;mica</h2>
<b>Matr&iacute;cula: </b>
<?php echo($ras);?>
<b> Nome: </b>
<?php echo($_GET['nome']);?>
<br>
<b>Curso: </b>
<?php echo($_GET['curso']);?>
<br />
<b>Data: </b>
<?php echo date("d/m/Y"); ?>
<b>Hora: </b>
<?php echo date("H:i"); ?>
<br>
<br>
<table width="80%" cellpadding="0" cellspacing="0"
	class="tabela_relatorio">
	<tr bgcolor="#666666">
		<th width="14%"><font color="#FFFFFF"><b>Per&iacute;odo</b></font></th>
		<th width="60%"><font color="#FFFFFF"><b>Componente Modular</b></font></th>
		<th width="8%"><font color="#FFFFFF"><b>M&eacute;dia</b></font></th>
		<th width="8%"><font color="#FFFFFF"><b>Faltas</b></font></th>
		<th width="20%"><font color="#FFFFFF"><b>% Faltas</b></font></th>
		<th width="15%"><font color="#FFFFFF"><b>CH Realizada</b></font></th>
		<th width="15%"><<font color="#FFFFFF"><b>CH Prevista</b></font></th>
	</tr>
	<?php

	while(!$Result1->EOF) {

		$iddisc = $Result1->fields[0];
		$nomemateria = $Result1->fields[2];
		$periodo = $Result1->fields[1];
		$faltasmateria = $Result1->fields[5];
		$classe = $Result1->fields[4];
		$aulaprev = $Result1->fields[3];
		$oferecida = $Result1->fields[8];

		//CODIGO RETIRADO DA PAPELETA papeleta.php - NOTA FINAL

		/* if($Result1->fields[6] != 0) {
		 $notafinal = getNumeric2Real($Result1->fields[6]);
		 }
		 else {
		 $notafinal = $Result1->fields[6];
		 }*/
		$notafinal = $Result1->fields[6];

		//FIM CODIGO PAPELETA

			
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
			$fcolor = '#000000';
		}

		//VERIFICA SE A CARGA HORARIA REALIZADA ESTA NULA
		if ($res == "") {
			$res = 0;
		}

		print ("<tr bgcolor=\"$st\">
        <td><font style=\"color: $fcolor; \">$periodo</font></td>
		<td><font style=\"color: $fcolor; \">$iddisc - $nomemateria</font></td>
		<td align=center><font style=\"color: $fcolor; \">$notafinal</font></td>
        <td align=center><font style=\"color: $fcolor; \">$faltasmateria</font></td>
        <td align=center><font style=\"color: $fcolor; \">$stfaltas</font></td>
        <td align=center><font style=\"color: $fcolor; \">$res</font></td>
        <td align=center><font style=\"color: $fcolor; \">$aulaprev</font></td>
        </tr>");

		$res = "";

		if ($st == '#F3F3F3') {
			$st = '#E3E3E3';
		}
		else {
			$st ='#F3F3F3';
		}

		$Result1->MoveNext();

	}//FIM WHILE
     
	?>
</table>
</page>
	<?php
	$content = ob_get_clean();
	require_once('../../lib/html2pdf/html2pdf.class.php');
	$pdf = new HTML2PDF('P','A4','en');
	$pdf->WriteHTML($content, isset($_GET['vuehtml']));
	$pdf->Output();

	?>
