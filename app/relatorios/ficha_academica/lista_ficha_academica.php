<?php

require_once('../../../app/setup.php');
require_once('../../../core/situacao_academica.php');
require_once("../../../core/reports/header.php");
  
$conn = new connection_factory($param_conn);

$header  = new header($param_conn);

$aluno_id    = $_GET['aluno'];
$curso_id    = $_GET['cs'];
$contrato_id = $_GET['contrato'];

$btnOK = TRUE;	

if(!isset($aluno_id) OR !is_numeric($aluno_id) OR empty($aluno_id)) 
	$btnOK = FALSE;
	
	
if(!isset($curso_id) OR !is_numeric($curso_id) OR empty($curso_id))
	$btnOK = FALSE;

if(!isset($contrato_id) OR !is_numeric($contrato_id) OR empty($contrato_id))
	$btnOK = FALSE;


if (!$btnOK)
     die('Erro de valida&ccedil;&atilde;o de dados!');


	$sql1 = "SELECT DISTINCT
    d.id, 
    s.descricao as periodo, 
    d.descricao_disciplina as descricao, 
    d.carga_horaria, 
    m.ref_periodo, 
    CAST(m.num_faltas AS INTEGER) as faltas, 
    CAST(m.nota_final AS FLOAT) as nota_final, 
    m.nota as nota, 
    m.ref_disciplina_ofer as oferecida,
    m.ref_motivo_matricula,
    professor_disciplina_ofer_todos(o.id),
    get_carga_horaria_realizada(o.id) as carga_horaria_realizada
    FROM 
        matricula m, disciplinas d, disciplinas_ofer o, periodos s, contratos c
    WHERE 
        m.ref_curso = $curso_id AND 
        c.id = $contrato_id AND
        m.ref_contrato = $contrato_id AND
        c.id = m.ref_contrato AND
        m.ref_periodo = s.id AND
        m.ref_disciplina_ofer = o.id AND 
        d.id = o.ref_disciplina AND
        o.is_cancelada = '0' AND
        s.id = o.ref_periodo
    ORDER BY 2, 3";

	
$ficha_academica = $conn->adodb->getAll($sql1);
	
$contMatriculada = count($ficha_academica);

if ($contMatriculada == 0)
	die('Nenhum dado encontrado para o aluno informado!');

?>
<html>
<head>
<title>SA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?=$BASE_URL .'public/styles/relatorio.css'?>" rel="stylesheet" type="text/css">

<style media="print">
<!--
.nao_imprime {display:none}

table.relato {
    font: 0.7em verdana, arial, tahoma, sans-serif;
    border: 0.0015em solid;
    border-collapse: collapse;
    border-spacing: 0px;
}

.relato td, th {
    font: 0.7em verdana, arial, tahoma, sans-serif;
    border: 0.0015em solid;
    padding: 2px;
    border-collapse: collapse;
    border-spacing: 1px;
}
-->
</style>


</head>
<body>
	<div>
		<div align="center" style="text-align:center; font-size:12px;">
        	<?php echo $header->get_empresa($PATH_IMAGES); ?>
            <br /><br />
        </div> 
	<h2>Ficha Acad&ecirc;mica</h2>
	<font color="#000000" size="2"> <b>Matr&iacute;cula: </b><?php echo($aluno_id);?> <b> Nome: </b><?php echo($_GET['nome']);?> </font><br>
	<font color="#000000" size="2"> <b>Curso: </b><?php echo($_GET['curso']);?><br />
	<b>Data: </b> <?php echo date("d/m/Y"); ?> <b>Hora: </b><?php echo date("H:i"); ?> </font><br>
	<br>
	<table cellpadding="0" cellspacing="0" class="relato">
	  <tr bgcolor="#666666">
	    <th><div align="center"><font color="#FFFFFF"><b>Per&iacute;odo</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>Componente Modular</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>M&eacute;dia</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>Faltas</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>% Faltas</b></font></div></th>
		<th><div align="center"><font color="#FFFFFF"><b>CH Realizada</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>CH Prevista</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>Matr&iacute;cula</b></font></div></th>
	    <th><div align="center"><font color="#FFFFFF"><b>Situa&ccedil;&atilde;o</b></font></div></th>
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
  
//nota total matriculada
$notaMatriculada = 0;
//percentual de faltas
$percFaltasMatriculada = 0;
//carga horaria realizada
$chRealizadaMatriculada = 0;


foreach ($ficha_academica as $disc) {
	$fcolor = '#000000';
// id	periodo	descricao	carga_horaria	ref_periodo	faltas	nota_final	nota	oferecida	ref_motivo_matricula	professor_disciplina_ofer_todos	carga_horaria_realizada
	$nome_materia = $disc['id'] .' - '. $disc['descricao'];
    $periodo = $disc['periodo'];
    $faltas_materia = $disc['faltas'];
    $ref_periodo = $disc['ref_periodo'];
    $carga_prevista = $disc['carga_horaria'];
    $carga_realizada = $disc['carga_horaria_realizada'];
    $oferecida = $disc['oferecida'];
    $ref_motivo_matricula = $disc['ref_motivo_matricula'];
    $nota_final = $disc['nota_final'];
	$professor = $disc['professor_disciplina_ofer_todos'];

    // APROVEITAMENTO DE ESTUDOS 2
    // CERTIFICACAO DE EXPERIENCIAS 3
    // EDUCACAO FISICA 4
    switch ($ref_motivo_matricula) {
            case 0:
                $matricula = 'CI';
                break;
            case 2:
                $matricula = 'AE';
                break;
            case 3:
                $matricula = 'CE';
                break;
            case 4:
                $matricula = 'DEF';
                break;
    }

    $situacao = '';
    // verifica aprovacao a qualquer tempo considerando qualquer disciplina equivalente, dispensa, etc, em relacao ao contrato
    if(verificaAprovacaoContrato($aluno_id,$curso_id,$contrato_id,$oferecida))
		$situacao = 'A'; 
    else
	    $situacao = 'R';

    // verifica aprovacao considerando exatamente a disciplina matriculada ou dispensada em relacao ao contrato
    if(verificaAprovacaoContratoDisciplina($aluno_id,$curso_id,$contrato_id,$oferecida))
        $situacao = 'A';
    else
        $situacao = 'R'; 
   
    if(!verificaPeriodo($ref_periodo))
        $situacao = 'M';

    if(verificaEquivalencia($curso_id,$oferecida))
        $matricula .= ' / DE';

	if($nota_final == ''){
		$nota_final = ' - ';
	}  
   
	$pfaltas = 0;
	$stfaltas = 0;
	if (!empty($carga_realizada)) {
    	$perfaltas = ($faltas_materia * 100) / $carga_realizada;
        $pfaltas = substr($perfaltas,0,5);
		
		$stfaltas = $pfaltas;
        //$stfaltas = getNumeric2Real($pfaltas) . ' %';
    }
    else {
		//$pfaltas = '-'; 
		$stfaltas = $pfaltas;
		$carga_realizada = 0;
	}
    
	
    if ($situacao == 'R') { 
		$fcolor = '#FF0000';
	}
   
    //  DADOS PARA CONTABILIZAR MEDIAS
    if ($situacao == 'A') 
	{
		$contAprovado++;
		//total notas aprovado
		$notaAprovado += $nota_final;
		//total percentual de faltas
		$percFaltasAprovado += $stfaltas;
		//Total carga horaria realizada
		$chRealizadaAprovado += $carga_realizada;
	}

     //total notas matriculada
     $notaMatriculada += $nota_final;
     //total percentual de faltas
     $percFaltasMatriculada += $stfaltas;
     //Total carga horaria realizada
     $chRealizadaMatriculada += $carga_realizada;
	
	if ($st == '#F3F3F3') {
   		$st = '#FFFFFF';
	}
	else {
		$st ='#F3F3F3';
	}

	if (strstr($stfaltas,'.'))
		$stfaltas = number_format($stfaltas,'2',',','.');	
    
	if (strstr($nota_final,'.'))
        $nota_final = number_format($nota_final,'1',',','.');
	
    echo 
	"<tr bgcolor=\"$st\">
        <td><font color=$fcolor>$periodo</font></td>
		<td><span id=\"$oferecida\" title=\"Di&aacute;rio: $oferecida  - Professor(es): $professor\"><font color=$fcolor>$nome_materia</font></span></td>
		<td align=center><font color=$fcolor>$nota_final</font></td>
        <td align=center><font color=$fcolor>$faltas_materia</font></td>
        <td align=center><font color=$fcolor>$stfaltas</font></td>
        <td align=center><font color=$fcolor>$carga_realizada</font></td>
        <td align=center><font color=$fcolor>$carga_prevista</font></td>
        <td align=center><font color=$fcolor>$matricula</font></td>
        <td align=center><font color=$fcolor>$situacao</font></td>
        </tr>";
}//FIM FOREACH



//INFORMACOES --

//Media nas disciplinas aprovadas
$notaMediaAprovado = @number_format($notaAprovado / $contAprovado,'2',',','.');

//Media percentual de faltas das disciplinas aprovadas
$percFaltasAprovado = @($percFaltasAprovado / $contAprovado);

//Convertendo para o padrao decimal - Media percentual de faltas das disciplinas aprovadas
$percFaltasAprovado = number_format($percFaltasAprovado,'2',',','.');


//Media nas disciplinas matriculadas
$notaMediaMatriculada = @number_format($notaMatriculada / $contMatriculada,'2',',','.');

//Media percentual de faltas das disciplinas matriculadas
$percFaltasMatriculada = $percFaltasMatriculada / $contMatriculada;

//Convertendo para o padrao decimal - Media percentual de faltas das disciplinas matriculada
$percFaltasMatriculada = number_format($percFaltasMatriculada,'2',',','.');
                 
?>
</table>
<div align="left" class="relato">
    <h4>Legenda</h4>
    <strong>CI</strong> - Disciplina Cursada na Institui&ccedil;&atilde;o<br />
    <strong>AE</strong> - Aproveitamento de Estudos <br />
    <strong>CE</strong> - Certifica&ccedil;&atilde;o Experi&ecirc;ncia <br />
    <strong>DEF</strong> - Dispensado de Educa&ccedil;&atilde;o f&iacute;sica<br /><br />
    <strong>A</strong> - Aprovado<br />
    <strong>R</strong> - Reprovado <br />
    <strong>M</strong> - Matriculado <br /><br />
    <strong>DE</strong> - Disciplina Equivalente<br />
</div>
</div>

<br /><br />

<table border="0" cellspacing="0" cellpadding="0" class="relato">
  <tr bgcolor="666666">
    <th height="24" colspan="2">
    	<b>Informa&ccedil;&otilde;es:</b><br>    
    </th>
  </tr>
  <tr>
    <td>M&eacute;dia da nota nas disciplinas aprovadas:</td>
    <td align="right">&nbsp;<?php echo $notaMediaAprovado; ?></td>
  </tr>
  <tr>
    <td>M&eacute;dia percentual de faltas das disciplinas aprovadas: </td>
    <td align="right">&nbsp;<?php echo $percFaltasAprovado . ' %'; ?></td>
  </tr>
  <tr>
    <td>Total carga hor&aacute;ria realizada nas disciplinas aprovadas: </td>
    <td align="right">&nbsp;<?php echo $chRealizadaAprovado;?></td>
  </tr>
  <tr>
    <td>M&eacute;dia da nota nas disciplinas matriculadas:</td>
    <td align="right">&nbsp;<?php echo $notaMediaMatriculada; ?></td>
  </tr>
  <tr>
    <td>M&eacute;dia percentual de faltas das disciplinas matriculadas: </td>
    <td align="right">&nbsp;<?php echo $percFaltasMatriculada . ' %'; ?></td>
  </tr>
  <tr>
    <td>Total carga hor&aacute;ria realizada nas disciplinas matriculadas: </td>
    <td align="right">&nbsp;<?php echo $chRealizadaMatriculada;?></td>
  </tr>
</table>
<br />
<br />
<br /><br />
<div class="nao_imprime">
<input type="button" value="Imprimir" onClick="window.print()">
&nbsp;&nbsp;&nbsp;
<input type="button" name="fechar" id="fechar" value="Fechar" onclick="javascript:window.close();" />
</div>
<br /><br />
</body>
</html>
