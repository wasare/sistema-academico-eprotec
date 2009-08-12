<?php

  //ARQUIVO DE CONFIGURACAO E CLASSE ADODB
  header ("Cache-Control: no-cache");
  require("../../lib/common.php");
  require("../../configuracao.php");
  require("../../lib/adodb/adodb.inc.php");
  require("../../lib/adodb/tohtml.inc.php");
  require("../../lib/aluno.inc.php");
  

$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

$aluno_id = $_GET['aluno'];
	
	if(isset($aluno_id) && is_numeric($aluno_id) && $aluno_id != "") {
		$btnOK = TRUE;
	}
    else
		$btnOK = FALSE;
	
	$curso_id = $_GET['cs'];
	
	if(isset($curso_id) && is_numeric($curso_id) && $curso_id != "") {
		$btnOK = TRUE;
	}
	else
		$btnOK = FALSE;

    $contrato_id = $_GET['contrato'];

    if(isset($contrato_id) && is_numeric($contrato_id) && $contrato_id != "") {
        $btnOK = TRUE;
    }
	else
		$btnOK = FALSE;

if (!$btnOK)
     die('Erro de valida&ccedil;&atilde;o de dados!');
		
	$sql1 = "SELECT DISTINCT
	d.id, 
	s.descricao as periodo, 
	d.descricao_disciplina as descricao, 
	d.carga_horaria, 
	m.ref_periodo, 
	m.num_faltas as faltas, 
	m.nota_final as nota_final, 
	m.nota as nota, 
	m.ref_disciplina_ofer as oferecida,
    m.ref_motivo_matricula
	FROM 
		matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s, contratos c  
	WHERE 
		m.ref_pessoa = p.id AND 
		p.ra_cnec = $aluno_id AND 
		m.ref_curso = $curso_id AND 
        c.id = $contrato_id AND
        m.ref_contrato = $contrato_id AND
        c.id = m.ref_contrato AND
		m.ref_periodo = s.id AND
		m.ref_disciplina_ofer = o.id AND 
		d.id = o.ref_disciplina AND
		s.id = o.ref_periodo
	ORDER BY 2, 3";
//-- m.dt_matricula >= '2004-01-01' AND	
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
    <br /><br />
</div>
<h2>Ficha Acad&ecirc;mica</h2>
<font color="#000000" size="2"> <b>Matr&iacute;cula: </b><?php echo($aluno_id);?> <b> Nome: </b><?php echo($_GET['nome']);?> </font><br>
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
    <th width="12%"><div align="center"><font color="#FFFFFF"><b>CH Realizada</b></font></div></th>
    <th width="12%"><div align="center"><font color="#FFFFFF"><b>CH Prevista</b></font></div></th>
    <th width="5%"><div align="center"><font color="#FFFFFF"><b>Matr&iacute;cula</b></font></div></th>
    <th width="6%"><div align="center"><font color="#FFFFFF"><b>Situa&ccedil;&atilde;o</b></font></div></th>
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

	$nomemateria = $Result1->fields[0] .' - '. $Result1->fields[2];
    $periodo = $Result1->fields[1];
    $faltasmateria = $Result1->fields[5];
    $ref_periodo = $Result1->fields[4];
    $aulaprev = $Result1->fields[3];
    $oferecida = $Result1->fields[8];
    $ref_motivo_matricula = $Result1->fields[9];
    $notafinal = $Result1->fields[6];

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

	if($notafinal == ''){
		$notafinal = ' - ';
	}  
               
    if ($faltasmateria == 0) { 
		$faltasmateria='-';
	}
 
 
 	// SQL COM CARGA HORARIA REALIZADA
	$sqlflag ="
	SELECT SUM(CAST(flag AS INTEGER)) AS carga
    FROM 
    diario_seq_faltas 
    WHERE 
    periodo = '$ref_periodo' AND 
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
    if ($situacao == 'R') { 
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
		<td><span id=\"$oferecida\" title=\"Di&aacute;rio: $oferecida\"><font color=$fcolor>$nomemateria</font></span></td>
		<td align=center><font color=$fcolor>$notafinal</font></td>
        <td align=center><font color=$fcolor>$faltasmateria</font></td>
        <td align=center><font color=$fcolor>$stfaltas</font></td>
        <td align=center><font color=$fcolor>$res</font></td>
        <td align=center><font color=$fcolor>$aulaprev</font></td>
        <td align=center><font color=$fcolor>$matricula</font></td>
        <td align=center><font color=$fcolor>$situacao</font></td>
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
<div align="left">
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
<br />
<br />
</body>
</html>
