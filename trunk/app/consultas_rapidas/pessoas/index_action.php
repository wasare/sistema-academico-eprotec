<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../../lib/common.php");
require("../../../lib/config.php");
require("../../../configuracao.php");
require("../../../lib/adodb/adodb.inc.php");
//require("../../../lib/adodb/tohtml.inc.php");


$sql = "SELECT id, nome 
		FROM public.pessoas
		WHERE 
		lower(to_ascii(\"nome\")) like lower(to_ascii('%".$_POST['nome']."%')) 
		ORDER BY nome LIMIT 50";


//Criando a classe de conex�o ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conex�o persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Exibindo a descricao do curso caso setado
$RsNome = $Conexao->Execute($sql);

//inicio da tabela
$tabela = '<table width="90%" border="0">';
$tabela.= "  <tr bgcolor='#666666'>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Nome</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Enviar</font></b></td>";
$tabela.= "  </tr>";


while(!$RsNome->EOF){

    $tabela.= "<tr bgcolor='#DDDDDD'>";
	$tabela.= "   <td align=\"left\">" . iconv("iso-8859-1", "utf-8", $RsNome->fields[1]) . "</td>";
    $tabela.= "   <td align=\"left\"><a href=\"javascript:send(" .$RsNome->fields[0]. ", '". iconv("iso-8859-1", "utf-8", $RsNome->fields[1]) . "')\"><img src=\"../../../images/icons/apply.png\" alt=\"Enviar\" /></a></td>";
	$tabela.= "</tr>";
	$RsNome->MoveNext();
}

$tabela.= "</table>";

echo $tabela;

//rs2html($RsNome, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"');

?>