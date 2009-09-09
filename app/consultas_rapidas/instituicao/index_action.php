<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header("Cache-Control: no-cache");
require_once("../../../app/setup.php");


$sql = "SELECT i.id, i.nome 
		FROM instituicoes i
		WHERE 
		lower(to_ascii(\"nome\")) ilike lower(to_ascii('%".$_POST['nome']."%')) 
		ORDER BY to_ascii(nome) LIMIT 15;";

//Criando a classe de conex�o ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conex�o persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Exibindo a descricao do curso caso setado
$RsInstituicao = $Conexao->Execute($sql);

//inicio da tabela
$tabela = '<table width="90%" border="0">';
$tabela.= "  <tr bgcolor='#666666'>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>C&oacute;digo</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Descri&ccedil;&atilde;o</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Enviar</font></b></td>";
$tabela.= "  </tr>";


while(!$RsInstituicao->EOF){

    $tabela.= "<tr bgcolor='#DDDDDD'>";
    $tabela.= "   <td align=\"left\">" . $RsInstituicao->fields[0] . "</td>";
    //$tabela.= "   <td align=\"left\">" . iconv("iso-8859-1", "utf-8", $RsInstituicao->fields[1]) . "</td>";
    //$tabela.= "   <td align=\"left\"><a href=\"javascript:send('" . $RsInstituicao->fields[0] . "','". 
iconv("iso-8859-1", "utf-8", $RsInstituicao->fields[1]) ."'); \"><img src=\"../../../public/images/icons/apply.png\" alt=\"Enviar\" /></a></td>";
    $tabela.= "   <td align=\"left\">" . $RsInstituicao->fields[1] . "</td>";
    $tabela.= "   <td align=\"left\"><a href=\"javascript:send('" . $RsInstituicao->fields[0] . "','".
 $RsInstituicao->fields[1] ."'); \"><img src=\"../../../public/images/icons/apply.png\" alt=\"Enviar\" 
/></a></td>";
        $tabela.= "</tr>";

	$tabela.= "</tr>";
	$RsInstituicao->MoveNext();
}

$tabela.= "</table>";

echo $tabela;

//rs2html($RsNome, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"');

?>
