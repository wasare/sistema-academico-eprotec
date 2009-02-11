<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require_once("../../../lib/common.php");
require_once("../../../lib/config.php");
require_once("../../../configuracao.php");
require_once("../../../lib/adodb/adodb.inc.php");
//require("../../lib/adodb/tohtml.inc.php");

$s_periodo = '';

if( strlen($_SESSION['sa_periodo_id']) > 0 ) {

	$sa_periodo_id = $_SESSION['sa_periodo_id'];
	$s_periodo = ' WHERE ref_periodo = \'' . $sa_periodo_id . '\' ';
}
	

$sql = "SELECT DISTINCT c.id, descricao 
		FROM public.cursos c, matricula m
		WHERE 
		lower(to_ascii(\"descricao\")) like lower(to_ascii('%".$_POST['nome']."%')) 
		AND c.id IN ( SELECT DISTINCT ref_curso FROM matricula $s_periodo )
		AND c.id = m.ref_curso
		ORDER BY descricao LIMIT 15;";

//$sql = iconv("utf-8", "iso-8859-1", $sql);

//SELECT id, descricao FROM public.cursos c WHERE lower(to_ascii("descricao")) like lower(to_ascii('%%')) AND c.id IN ( SELECT DISTINCT ref_curso FROM matricula WHERE ref_periodo = '0802')  ORDER BY descricao LIMIT 50

//echo $sql;
//die;

//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Exibindo a descricao do curso caso setado
$RsCurso = $Conexao->Execute($sql);

//inicio da tabela
$tabela = '<table width="90%" border="0">';
$tabela.= "  <tr bgcolor='#666666'>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>C&oacute;digo</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Descri&ccedil;&atilde;o</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Enviar</font></b></td>";
$tabela.= "  </tr>";


while(!$RsCurso->EOF){

    $tabela.= "<tr bgcolor='#DDDDDD'>";
    $tabela.= "   <td align=\"left\">" . $RsCurso->fields[0] . "</td>";
    //$tabela.= "   <td align=\"left\">" . iconv("iso-8859-1", "utf-8", $RsCurso->fields[1]) . "</td>";
    //$tabela.= "   <td align=\"left\"><a href=\"javascript:send('" . $RsCurso->fields[0] . "','". 
iconv("iso-8859-1", "utf-8", $RsCurso->fields[1]) ."'); \"><img src=\"../../../images/icons/apply.png\" alt=\"Enviar\" /></a></td>";
    $tabela.= "   <td align=\"left\">" . $RsCurso->fields[1] . "</td>";
    $tabela.= "   <td align=\"left\"><a href=\"javascript:send('" . $RsCurso->fields[0] . "','".
 $RsCurso->fields[1] ."'); \"><img src=\"../../../images/icons/apply.png\" alt=\"Enviar\" 
/></a></td>";
        $tabela.= "</tr>";

	$tabela.= "</tr>";
	$RsCurso->MoveNext();
}

$tabela.= "</table>";

echo $tabela;

//rs2html($RsNome, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"');

?>
