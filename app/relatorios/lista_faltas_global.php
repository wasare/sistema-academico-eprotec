<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");
//require("../../lib/adodb/adodb-pager.inc.php");


//RECEBENDO OS DADOS DO FORMULARIO --
$periodo = $_POST["periodo1"];
$curso_id = $_POST["codigo_curso"];
$aluno_id = $_POST["aluno"];
$resp_nome = $_POST["resp_nome"];
$resp_cargo = $_POST["resp_cargo"];

//print_r($_POST); die;

//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//print_r($_SESSION);
//$Conexao->debug = true;

$sql = "
SELECT DISTINCT
        p.id as \"Matrícula\",
        p.nome as \"Nome\",
        SUM(d.carga_horaria) AS \"CH Matriculada\",
        SUM(m.num_faltas) as \"Total Faltas\",
        replace(to_number(SUM(m.num_faltas) / SUM(d.carga_horaria) * 100, 'FM999.99' ), '.', ',') as \"% Faltas\"
        FROM
                matricula m, disciplinas d, pessoas p, disciplinas_ofer o, periodos s
        WHERE
                m.ref_pessoa = p.id AND
                p.id IN (
                    SELECT DISTINCT ref_pessoa 
                        FROM matricula  
                        WHERE 
                            ref_periodo = '$periodo' AND ref_curso = $curso_id
                ) AND
                m.ref_curso = $curso_id AND
                m.ref_periodo = '$periodo' AND               
                m.dt_matricula >= '2004-01-01' AND
                m.ref_disciplina_ofer = o.id AND
                d.id = o.ref_disciplina AND                
		s.id = o.ref_periodo ";

if ( is_numeric($aluno_id) )
	$sql .= " AND p.id = $aluno_id ";

$sql .= "  GROUP BY p.id, p.nome, m.ref_periodo, m.ref_curso   ORDER BY 2";

//echo $sql; die;

$sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii("Nome"));';

//EXECUTANDO SQL DA CONSULTA PRINCIPAL
$Result1 = $Conexao->Execute($sql);

//$Conexao->ErrorMsg();

//numero de ocorrencias
$num_result = $Result1->RecordCount();


//Informacoes de cabecalho
$info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Período: </strong> <span>$periodo</span> <br><br>";

/*
$sqlCampus = "
SELECT nome_campus
FROM campus
WHERE id = '$campus';";

$RsCampus = $Conexao->Execute($sqlCampus);
 */

$sqlCurso = "
SELECT id || ' - ' || descricao
FROM cursos
WHERE id = '$curso_id';";

$RsCurso = $Conexao->Execute($sqlCurso);


if(is_numeric($curso_id)) 
	$info .= "<strong>Curso: </strong><span>" . $RsCurso->fields[0] . "</span>";

$info .= '<br /><br /><strong>Aten&ccedil;&atilde;o: </strong><span><font color="red">Os dados abaixo est&atilde;o de acordo 
com o lan&ccedil;amento realizado pelos respons&aacute;veis pelas informa&ccedil;&otilde;es.</font> </span>';
	
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
            <h2>RELAT&Oacute;RIO DE FALTAS GLOBAL</h2>
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
