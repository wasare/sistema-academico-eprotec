<?php

require_once("../../../app/setup.php");
require_once("../../../lib/adodb5/tohtml.inc.php");
require_once("../../../core/reports/header.php");
require_once("../../../core/reports/carimbo.php");

$conn = new connection_factory($param_conn);

$header  = new header($param_conn);
$carimbo = new carimbo($param_conn);

$periodo = $_POST['periodo1'];

$sql_dispensas = '
SELECT 
	ref_pessoa AS "Matrícula", 
    p.nome AS "Aluno", 
    c.descricao || \' (\' || c.id || \')\' AS "Curso", 
    d.descricao_disciplina AS "Disciplina", 
	CASE WHEN m.ref_motivo_matricula = 2 THEN \'<font color="blue">AE</font>\'
         WHEN m.ref_motivo_matricula = 3 THEN \'<font color="green">CE</font>\'
         WHEN m.ref_motivo_matricula = 4 THEN \'<font color="red">EF</font>\'
    END AS "Motivo"   

	FROM
		matricula m, 
        pessoas p, 
        cursos c, 
        disciplinas d, 
        disciplinas_ofer o 
	WHERE
	    ref_motivo_matricula in (2,3,4) AND 
		m.dt_cancelamento is null AND
		ref_pessoa = p.id AND 
		m.ref_curso = c.id AND 
		ref_disciplina_ofer = o.id AND 
		o.ref_disciplina = d.id AND
		m.ref_periodo = \''. $periodo .'\'

	ORDER BY c.descricao, p.nome;';

$Result1 = $conn->Execute($sql_dispensas);

$total = $Result1->RecordCount();

if($total < 1){
    echo "<script>alert('Nenhum registro foi retornado!'); window.close();</script>";
}

//Informacoes de cabecalho
$info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Total de Registros: </strong>" . $total . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Período: </strong> <span>$periodo</span> <br />";
$legenda = '<font color="blue">AE = Aproveitamento de estudos</font>&nbsp;&nbsp;-&nbsp;&nbsp;';
$legenda .= '<font color="green">CE = Certifica&ccedil;&atilde;o de experi&ecirc;ncia</font>&nbsp;&nbsp;-&nbsp;&nbsp;<font color="red">EF = Dispensa de Educa&ccedil;&atilde;o F&iacute;sica</font>';

?>
<html>
<head>
        <title>Lista de di&aacute;rios</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../../public/styles/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
<div style="width: 760px;" align="center">
	<div align="center" style="text-align:center; font-size:12px;">
    	<?php echo $header->get_empresa($PATH_IMAGES); ?>
        <br /><br />
    </div> 
    <h2>RELAT&Oacute;RIO DE ALUNOS DISPENSADOS</h2>
    <?php 
    echo $info;
    rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"', FALSE, FALSE); 
    ?>
   	<?=$legenda?>
    <br /><br />
	<div class="carimbo_box">
		_______________________________<br>
		<span class="carimbo_nome">
			<?php echo $carimbo->get_nome($_POST['carimbo']);?>
		</span><br />
		<span class="carimbo_funcao">
			<?php echo $carimbo->get_funcao($_POST['carimbo']);?>
		</span>
	</div>
</div>
</body>
</html>
