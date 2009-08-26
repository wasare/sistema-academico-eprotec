<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configs/configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");


$periodo = $_POST["periodo1"];

//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");
  

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

 //html2pdf
  ob_start();

?>
<link href="../../public/styles/style.css" rel="stylesheet" type="text/css">
<page backtop="10mm" backbottom="10mm" >
<page_header></page_header>
<page_footer>
<table style="width: 700px;">
  <tr>
    <td style="text-align: left; width: 50%">&nbsp;</td>
    <td style="text-align: right; width: 50%">página [[page_cu]]/[[page_nb]]</td>
  </tr>
</table>
</page_footer>
<span style="text-align:center; font-size:12px;">
	<img src="../../public/images/armasbra.jpg" width="57" height="60"><br />
	MEC-SETEC<br />
	CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
    SETOR DE REGISTROS ESCOLARES
    <br /><br /><br />
</span>
<h2>MATRÍCULAS/CIDADES DE ALUNOS POR CURSO NO PERÍODO <?=$periodo; ?></h2>
<?php

while(!$RsCursos->EOF) {

	echo "<h3>" . $RsCursos->fields[0] . " - " . $RsCursos->fields[1] . "</h3>";
	
	$id_curso = $RsCursos->fields[0];
	
	$sqlCursoCidade = "
	SELECT
	COUNT(p.id) as \"Quant\", a.nome as \"Cidade\", a.ref_estado as \"UF\"
	
	FROM 
	pessoas p LEFT JOIN cidade a ON(p.ref_cidade = a.id)
	
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
</page>
<?php

  	$content = ob_get_clean();
  	require_once('../../lib/html2pdf/html2pdf.class.php');
  	$pdf = new HTML2PDF('P','A4','en');
  	$pdf->WriteHTML($content, isset($_GET['vuehtml']));
  	$pdf->Output(); 

?>
