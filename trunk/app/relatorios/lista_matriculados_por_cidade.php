<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");
//require("../../lib/adodb/adodb-pager.inc.php");



$periodo = $_POST["periodo1"];

//echo $periodo;die;

//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Debug
//$Conexao->debug=true;


$sqlCursos = "
select distinct 
c.id as \"Cód.\", c.descricao as \"Descrição do Curso\"

from 
matricula m, cursos c

where
m.ref_periodo = '$periodo' AND
m.ref_curso = c.id
ORDER BY 2;";


//EXECUTANDO SQL DA CONSULTA PRINCIPAL
$RsCursos = $Conexao->Execute($sqlCursos);

//numero de ocorrencias
//$num_result = $Result1->RecordCount();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Untitled Document</title>
        <link href="../../Styles/style.css" rel="stylesheet" type="text/css">
    </head>

    <body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
        <div style="width: 760px; padding:6px;">
            <div align="center" style="text-align:center; font-size:12px;">
                <img src="../../images/armasbra.jpg" width="57" height="60"><br />
                MEC-SETEC<br />
                CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
                SETOR DE REGISTROS ESCOLARES
                <br /><br /><br />
            </div>
            <h2>MATRÍCULAS/CIDADES DE ALUNOS POR CURSO NO PERÍODO <?=$periodo; ?></h2>
            <?php

            while(!$RsCursos->EOF) {

                echo "<h3>" . $RsCursos->fields[0] . " - " . $RsCursos->fields[1] . "</h3>";

                $id_curso = $RsCursos->fields[0];

                $sqlCursoCidade = "
                SELECT
                COUNT(p.id) as \"Quant\", a.nome as \"Cidade\", a.ref_estado as \"UF\"

                FROM
                pessoas p LEFT JOIN aux_cidades a ON(p.ref_cidade = a.id)

                WHERE
                p.id IN (
                    SELECT DISTINCT
                    ref_pessoa
                    FROM matricula
                    WHERE
                    ref_periodo = '$periodo' AND
                    ref_curso = '$id_curso'
                )
                GROUP BY a.nome, a.ref_estado
                ORDER BY a.nome";

                $RsCursoCidade = $Conexao->Execute($sqlCursoCidade);
                rs2html($RsCursoCidade, 'cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"');

                $RsCursos->MoveNext();

            }

            ?>
        </div>
    </body>
</html>