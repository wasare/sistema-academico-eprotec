<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");


$sql = "SELECT 
		nome_completo, login, nivel, id_nome, ativo
		FROM public.diario_usuarios
		WHERE 
		lower(to_ascii(\"nome_completo\")) like lower(to_ascii('%".$_POST['nome']."%')) 
		ORDER BY \"nome_completo\" LIMIT 15";

//$sql = iconv("utf-8", "iso-8859-1", $sql);


//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Exibindo a descricao do curso caso setado
$RsNome = $Conexao->Execute($sql);

//inicio da tabela
$tabela = '<table width="90%" border="0">';
$tabela.= "  <tr bgcolor='#666666'>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Nome Completo</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Login</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>N&iacute;vel</font></b></td>";
$tabela.= "    <td width=''><b><font color='#FFFFFF'>Alterar</font></b></td>";
$tabela.= "  </tr>";

while(!$RsNome->EOF){

	if($RsNome->fields[4] == 't'){
		
		$cor_linha = '#DDDDDD';
		$situacao = " ";
		
	} else {
		$cor_linha = '#999999';
		$situacao = " (Desativado)";
	}
    $tabela.= "<tr bgcolor=\"$cor_linha\" >";
	$tabela.= "<td align=\"left\">" . iconv("iso-8859-1", "utf-8", $RsNome->fields[0]) . $situacao . "</td>";
    $tabela.= "<td align=\"left\">" . $RsNome->fields[1]."</td>";
	
	if($RsNome->fields[2] == 1) $tabela.= "<td align=\"left\">Professor</td>";
	
	if($RsNome->fields[2] == 2) $tabela.= "<td align=\"left\">Secretaria</td>";
	
	$tabela.= "<td align=\"center\">";
	$tabela.= "<a href=\"alterar.php?id_pessoa=".$RsNome->fields[3]."\"><img src=\"../../images/icons/edit.png\" alt=\"Editar\" /></a>";
	//$tabela.= "		<a href=\"excluir_action.php?id_pessoa=".$RsNome->fields[3]."\"><img src=\"../images/icons/bar_menu/cancel.png\" alt=\"Editar\" /></a>";
	$tabela.= "</td>";
	$tabela.= "</tr>";
	
	$RsNome->MoveNext();
}

$tabela.= "</table>";

echo $tabela;


//rs2html($RsNome, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"');

?>
