<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configs/configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");


//RECEBENDO OS DADOS DO FORMULARIO --
$periodo = $_POST["periodo1"];
$tipo = $_POST["tipo"];
$campus = $_POST["campus"];
$resp_nome = $_POST["resp_nome"];
$resp_cargo = $_POST["resp_cargo"];


//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


// APROVEITAMENTO DE ESTUDOS 2
// CERTIFICACAO DE EXPERIENCIAS 3
// EDUCACAO FISICA 4


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


//echo $sql_dispensas; die;


//EXECUTANDO SQL DA CONSULTA PRINCIPAL
$Result1 = $Conexao->Execute($sql_dispensas);

//numero de ocorrencias
$num_result = $Result1->RecordCount();


//Informacoes de cabecalho
$info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Período: </strong> <span>$periodo</span> <br />";
$legenda = '<font color="blue">AE = Aproveitamento de estudos</font>&nbsp;&nbsp;-&nbsp;&nbsp;';
$legenda .= '<font color="green">CE = Certifica&ccedil;&atilde;o de experi&ecirc;ncia</font>&nbsp;&nbsp;-&nbsp;&nbsp;<font color="red">EF = Dispensa de Educa&ccedil;&atilde;o F&iacute;sica</font>';


$sqlCampus = "
SELECT nome_campus
FROM campus
WHERE id = '$campus';";

$RsCampus = $Conexao->Execute($sqlCampus);


$sqlTipo = "
SELECT descricao
FROM tipos_curso
WHERE id = '$tipo';";

$RsTipo = $Conexao->Execute($sqlTipo);

if($campus != '') 
	$info .= "<strong>Campus: </strong><span>" . $RsCampus->fields[0] . "</span>&nbsp;&nbsp;-&nbsp;&nbsp;";

if($tipo != '') 
	$info .= "<strong>Tipo de curso: </strong><span>" . $RsTipo->fields[0] . "</span>";
	
$info .="<br><br>";

//Dados de rodape com assinatura
$rodape = '<span style="font-size: 12px;">' . $resp_nome . "</span><br>";
$rodape .= '<span style="font-size: 9px;"><strong>' . $resp_cargo . "</strong></span><br>";

?>
<html>
    <head>
        <title>Lista de di&aacute;rios</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../public/styles/style.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
        <div style="width: 760px;">
            <div align="center" style="text-align:center; font-size:12px;">
                <img src="../../public/images/armasbra.jpg" width="57" height="60"><br />
                MEC-SETEC<br />
                CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
                SETOR DE REGISTROS ESCOLARES
                <br /><br /><br />
            </div>
            <h2>RELAT&Oacute;RIO DE ALUNOS DISPENSADOS</h2>
            <?php echo $info; ?>
            <?php rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"', FALSE, FALSE); ?>
             <p>
             <?=$legenda?>
                <div align="center" style="width: 90%;">
                    <p>&nbsp;</p>
                    __________________________________________<br>
                <?php echo $rodape; ?> </div>
            </p>
        </div>
    </body>
</html>
