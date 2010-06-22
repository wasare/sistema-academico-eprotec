<?php

header("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/excel.inc.php");


$periodo    = $_POST["periodo1"];
$aluno      = $_POST["aluno"];
$curso      = $_POST["codigo_curso"];
$resp_nome  = $_POST["resp_nome"];
$resp_cargo = $_POST["resp_cargo"];
$turma      = $_POST["turma"];


$colunas[] = "ID";

$sql = " SELECT DISTINCT p.id as \"ID\" ";

if(isset($_POST['nome'])){
	$colunas[] = "Nome";
	$sql .= ', p.nome as "Nome" ';
}

if (isset($_POST["turma2"])) {

	$colunas[] = "Turma";
	$sql .= ', t.turma as "Turma" ';
	$condicao_turma = "AND t.ref_curso = c.ref_curso AND t.ref_pessoa = p.id";
	$tabela_contrato = ", public.contratos t";
}

if ($turma != '') {

	$condicao_turma .= " AND t.turma = '$turma' ";
	$tabela_contrato = ", public.contratos t ";
}

if (isset($_POST["pai"])) {

	$colunas[] = "Pai";
	$sql .= ', f.pai_nome as "Pai"';
	$tabela_filiacao = "LEFT OUTER JOIN filiacao f ON(p.ref_filiacao = f.id)";
}

if (isset($_POST["mae"])) {

	$colunas[] = "Mãe";
	$sql .= ', f.mae_nome as "Mae" ';
	$tabela_filiacao = "LEFT OUTER JOIN filiacao f ON(p.ref_filiacao = f.id)";
}

if (isset($_POST["endereco"])) {

	$colunas[] = "Endereço";
	$sql .= ", p.rua || '  ' || CASE WHEN p.complemento IS NULL THEN ' ' ELSE p.complemento END AS \"Endereço\"";
}

if (isset($_POST["bairro"])) {

	$colunas[] = "Bairro";
	$sql .= ', p.bairro as "Bairro"';
}

if (isset($_POST["cidade"])) {

	$colunas[] = "Cidade";
	$sql .= ', m.nome || \'-\' || m.ref_estado as "Cidade"';
	$condicao_municipio = " p.ref_cidade = m.id AND ";
	$tabela_municipio = ", public.aux_cidades m";
}

if (isset($_POST["cep"])) {

	$colunas[] = "CEP";
	$sql .= ', p.cep as "CEP"';
}

if (isset($_POST["telefone"])) {

	$colunas[] = "Telefone";
	$sql .= ', p.fone_particular as "Tel. Part."
        , p.fone_profissional as "Tel. Prof."
        , p.fone_celular as "Tel. Cel."
        , p.fone_recado as "Tel. Rec."
      ';
}

if (isset($_POST["rg"])){

	$colunas[] = "RG";
	$sql .= ', p.rg_numero as "RG"';
}

if (isset($_POST["cpf"])){

	$colunas[] = "CPF";
	$sql .= ', p.cod_cpf_cgc as "CPF"';
}

if (isset($_POST["sexo"])){

	$colunas[] = "Sexo";
	$sql .= ', p.sexo as "Sexo"';
}

if (isset($_POST["data_nascimento"])) {

	$colunas[] = "Data Nascimento";
	$sql .= ', to_char(p.dt_nascimento, \'DD/MM/YYYY\') as "Data de Nascimento"';
}

$sql .= "
  FROM
  pessoas p $tabela_filiacao, matricula c $tabela_contrato $tabela_municipio
  WHERE
  c.ref_periodo = '$periodo' AND ";

if ($curso != '') {
	$sql .= " c.ref_curso = '$curso' AND";
}

$sql .= $condicao_municipio;

if ($aluno != '') {
	$sql .= " p.id = '$aluno' AND ";
}

$sql .= " c.ref_pessoa = p.id $condicao_turma ORDER BY 2";

$sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii(2));';


$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

$Result1 = $Conexao->Execute($sql);


$gerar= new sql2excel($colunas,$sql);

?>