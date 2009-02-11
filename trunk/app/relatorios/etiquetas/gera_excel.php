<?php
define(db_host, �localhost�);
define(db_user, �user�);
define(db_pass, �passwd�);
define(db_link, mysql_connect(db_host,db_user,db_pass));
define(db_name, �my_database�);
mysql_select_db(db_name);

//Segundo passo - Trazendo as informa��es da tabela vendas:
$select = �SELECT * FROM vendas ORDER BY datadavenda DESC�;
$export = mysql_query($select);
// aqui pego a quantidade de campos existentes na tabela, afim de formar a planilha
$fields = mysql_num_fields($export);

//Terceiro passo - Recuperando os nomes dos campos. Eles tamb�m ser�o os nomes dos campos da planilha:
for ($i = 0; $i < $fields; $i++) {
$header .= mysql_field_name($export, $i) . �\t�;
}

//Quarto passo - Trazendo as informa��es encontradas em cada linha de registro do banco:

while($row = mysql_fetch_row($export)) {
$line = '';
foreach($row as $value) {
if ((!isset($value)) OR ($value == ��)) {
$value = �\t�;
} else {
$value = str_replace('"', '""', $value);
$value = ��� . $value . ��� . �\t�;
}
$line .= $value;
}
// o trim retira os espa�os encontrados no come�o e no final de cada linha encontrada.
$dados .= trim($line).�\n�;
}
// substituindo todas as quebras de linha ao final de cada registro, que por padr�o seria \r por uma valor em branco, para que a formata��o fique leg�vel
$dados= str_replace(�\r�,�",$dados);

Quinto passo - Tratamento b�sico de erro:
// Caso n�o encontre nenhum registro, mostra esta mensagem.
if ($dados== ��) {
$dados = �\n Nenhum registro encontrado!\n�;
}

�ltimo passo - Cabe�alhos e instru��es para gera��o e download do arquivo:
header(�Content-type: application/octet-stream�);
// este cabe�alho abaixo, indica que o arquivo dever� ser gerado para download (par�metro attachment) e o nome dele ser� o contido dentro do par�metro filename.
header(�Content-Disposition: attachment; filename=relatorio_vendas.xls�);
// No cache, ou seja, n�o guarda cache, pois � gerado dinamicamente
header(�Pragma: no-cache�);
// N�o expira
header(�Expires: 0?);
// E aqui geramos o arquivo com os dados mencionados acima!
print �$header\n$dados�;
?> 