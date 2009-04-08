<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configuracao.php");
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


$sql = "
SELECT DISTINCT
    o.id AS \"Diário\",
    d.descricao_disciplina || ' (' || d.id ||') ' AS \"Disciplina\",
    t.descricao AS \"Tipo\",
    o.turma AS \"Turma\",
    p.nome AS \"Professor\",
	s.abreviatura AS \"Curso\",
    -- m.nome_campus AS \"Campus\",

    CASE WHEN o.fl_digitada = TRUE THEN '<font color=\"red\">Finalizado</font>'
         WHEN o.fl_concluida = TRUE THEN '<font color=\"blue\">Concluído</font>'
         ELSE '<font color=\"green\">Aberto</font>'
    END AS \"Situação\"

FROM
    disciplinas_ofer_prof f, 
    disciplinas_ofer o,
    disciplinas d,
    pessoas p,
    cursos s,
    tipos_curso t,
    campus m

WHERE
    f.ref_professor = p.id AND
    o.ref_curso = s.id AND ";
	
if($tipo != '') 
	$sql .= " t.id = '$tipo' AND ";

$sql .= " s.ref_tipo_curso = t.id AND
    o.id = f.ref_disciplina_ofer AND
    o.ref_periodo = '$periodo' AND
    o.is_cancelada = 0 AND
    d.id = o.ref_disciplina AND ";

if($campus != '') 
	$sql .= " o.ref_campus = '$campus' AND ";

$sql .= " o.ref_campus = m.id
		ORDER BY \"Disciplina\";";



//EXECUTANDO SQL DA CONSULTA PRINCIPAL
$Result1 = $Conexao->Execute($sql);

//numero de ocorrencias
$num_result = $Result1->RecordCount();


//Informacoes de cabecalho
$info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Período: </strong> <span>$periodo</span> <br><br>";


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
        <link href="../../Styles/style.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
        <div style="width: 760px;">
            <div align="center" style="text-align:center; font-size:12px;">
                <img src="../../images/armasbra.jpg" width="57" height="60"><br />
                MEC-SETEC<br />
                CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
                SETOR DE REGISTROS ESCOLARES
                <br /><br /><br />
            </div>
            <h2>RELAT&Oacute;RIO DE ANDAMENTO DOS DI&Aacute;RIOS</h2>
            <?php echo $info; ?>
            <?php rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"', FALSE, FALSE); ?>
             <p>
                <div align="center" style="width: 90%;">
                    <p>&nbsp;</p>
                    __________________________________________<br>
                <?php echo $rodape; ?> </div>
            </p>
        </div>
    </body>
</html>
