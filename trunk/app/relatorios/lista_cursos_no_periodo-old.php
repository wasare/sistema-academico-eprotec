<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");


$periodo = $_POST["periodo1"];


//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");
  
//Debug
//$Conexao->debug=true;

/*
$sqlCursos = "
select distinct 
c.id as \"Cód.\", c.descricao as \"Descrição do Curso\"

from 
matricula m, cursos c

where
m.ref_periodo = '$periodo' AND
m.ref_curso = c.id
ORDER BY 2;";
 */

$sqlCursos = "
SELECT t1.id as \"Cód.\", t1.descricao AS \"Descrição do Curso\",  tipo_curso AS \"Tipo Curso\", t2.mat AS \"Alunos\"
            FROM
                ( 
                    SELECT DISTINCT
                        c.id , c.descricao, t.descricao as tipo_curso
                    FROM
                        matricula m, cursos c, tipos_curso t 
                    WHERE
                        m.ref_periodo = '$periodo' AND m.ref_curso = c.id AND c.ref_tipo_curso = t.id
                ) AS t1
                INNER JOIN 
                (
                    SELECT
                            ref_curso, count(mat) as mat 
                        FROM (
                                SELECT DISTINCT
                                    m.ref_curso, m.ref_pessoa as mat
                                FROM 
                                    matricula m, disciplinas_ofer o
                                WHERE 
                                    m.ref_periodo = '$periodo' AND
                                    m.ref_disciplina_ofer = o.id AND
                                    o.is_cancelada = 0
                            ) as T
                        GROUP BY ref_curso                 
                    ) AS t2         
                 ON (t1.id = t2.ref_curso)
               ORDER BY 3, 4 DESC;";



//echo $sqlCursos; die;

$sqlMatriculasCidades = "
SELECT DISTINCT 
count(p.id), m.nome, m.ref_estado 

FROM 
pessoas p , matricula c , public.aux_cidades m 

WHERE 
c.ref_periodo = '$periodo' AND 

c.ref_curso IN(
	select distinct 
	ref_curso
	from 
	matricula
	where
	ref_periodo = '$periodo') AND 

p.ref_cidade = m.id AND 
c.ref_pessoa = p.id 
GROUP BY m.nome, m.ref_estado
ORDER BY 2";


//EXECUTANDO SQL DA CONSULTA PRINCIPAL
$RsCursos = $Conexao->Execute($sqlCursos);

$RsMatriculasCidades = $Conexao->Execute($sqlMatriculasCidades);

//numero de ocorrencias
//$num_result = $RsCursos->RecordCount();

//Informacoes de cabecalho
  $info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
  $info .= "<strong>Per&iacute;odo: </strong> <span>$periodo</span> <br />";

  $info .= "<strong>Total de Matr&iacute;culas: </strong>" . date("d/m/Y") .'<br /><br />' ;

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
<h2>CURSOS COM ALUNOS MATRICULADOS NO PERÍODO <?=$periodo; ?></h2>
<?php 
        echo $info;
	rs2html($RsCursos, 'cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); 
?>
</div>

</body>
</html>
