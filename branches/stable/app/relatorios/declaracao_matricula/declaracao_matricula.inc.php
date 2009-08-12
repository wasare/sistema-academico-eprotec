<?php 

require("../../../lib/common.php");
require("../../../configuracao.php");
require("../../../lib/adodb/adodb.inc.php");


$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");



$contrato = $_POST["id_contrato"];
$data = $_POST["data"];
$carimbo = $_POST['carimbo'];



function mes($mes_num){


	switch ($mes_num) {
		case 1:
			$mes = "janeiro";
			break;
		case 2:
			$mes = "fevereiro";
			break;
		case 3:
			$mes = "mar&ccedil;o";
			break;
		case 4:
			$mes = "abril";
			break;
		case 5:
			$mes = "maio";
			break;
		case 6:
			$mes = "junho";
			break;
		case 7:
			$mes = "julho";
			break;
		case 8:
			$mes = "agosto";
			break;
		case 9:
			$mes = "setembro";
			break;
		case 10:
			$mes = "outubro";
			break;
		case 11:
			$mes = "novembro";
			break;
		case 12:
			$mes = "dezembro";
			break;
	}
	return $mes;
}


/* Formatando a data */

if($data == ''){
	$data = date("d/m/Y");
}

$data = explode("/",$data,3);
$mes = mes($data[1]);



/* Dados de Assinatura/Carimbo */

$sqlCarimbo = "
SELECT 
	id, nome, texto, ref_setor
FROM 
	carimbos 
WHERE	id = $carimbo;";

$RsCarimbo = $Conexao->Execute($sqlCarimbo);

if (!$RsCarimbo){
	print $Conexao->ErrorMsg();
	die();
}



/* Dados da Empresa */

$sqlEmpresa = "
SELECT 
	c.razao_social, 
	c.sigla, 
	c.logotipo, 
	c.rua, 
	c.complemento, 
	c.bairro, 
	c.cep, 
	c.ref_cidade, 
	c.cgc,
	a.nome,
	a.cep, 
	a.ref_estado
FROM 
	configuracao_empresa c, aux_cidades a
WHERE
	c.id = 1 AND
	a.id = c.ref_cidade;";

$RsEmpresa = $Conexao->Execute($sqlEmpresa);

if (!$RsEmpresa){
	print $Conexao->ErrorMsg();
	die();
}



/* Dados do aluno e curso */

$sqlContrato = "
SELECT 
	a.id, 
	b.cidade_campus,
	c.descricao,
	d.nome,
	e.nome,
	e.ref_estado,
	d.dt_nascimento,
	f.pai_nome,
	f.mae_nome
FROM 
	contratos a, campus b, cursos c, pessoas d, aux_cidades e, filiacao f
WHERE
	a.id = $contrato AND
	a.ref_campus = b.id AND
	a.ref_curso = c.id AND
	a.ref_pessoa = d.id	AND
	ref_naturalidade = e.id AND
	d.ref_filiacao = f.id;";

$RsContrato = $Conexao->Execute($sqlContrato);

if (!$RsContrato){
	print $Conexao->ErrorMsg();
	die();
}

/* Formatando a data de nascimento */

if($RsContrato->fields[6] != ''){
	
	$data_nascimento = explode("-",$RsContrato->fields[6],3);
	$mes_nascimento = mes($data_nascimento[1]);
}


$corpo = '        Declaro para os devidos fins que '.$RsContrato->fields[3].
', filho(a) de '.$RsContrato->fields[7].' e '.$RsContrato->fields[8].
', nascido(a) em '.$data_nascimento[2].' de '.$mes_nascimento.' de '.
$data_nascimento[0].', natural de '.$RsContrato->fields[4].'/'.$RsContrato->fields[5].
', encontra-se devidamente matriculado(a) no curso '.$RsContrato->fields[2].
', neste estabelecimento de ensino.
            Por ser verdade e estar de acordo com nossos arquivos, assino a presente.';

$data_declaracao = $RsContrato->fields[1].', '.$data[0].' de '.$mes.' de '.$data[2];
$carimbo_nome = $RsCarimbo->fields[1];
$carimbo_dados = $RsCarimbo->fields[2];

$decretos = 'Obs.:
Decreto N� 3.864/A de 24/01/61 - Cria��o da Escola
Decreto N� 55.358 de 13/0264 - Transformado em Gin�sio Agr�cola
Decreto N� 63.923 de 30/12/68 - Transformado em Col�gio Agr�cola
Decreto N� 83.935 de 04/09/79 - Denominado Escola Agrot�cnica Federal de Bambu�-MG
Lei N� 8.731/93 de 16/11/1993 - Transforma��o em Autarquia
Transformado em Centro Federal de Educa��o Tecnol�gica de Bambu�, atrav�s do Decreto
Presidencial de 17/12/2002, publicado no DOU de 18/12/2002,Se��o I, p�gina 12, de acordo com o Decreto Federal n� 2406 de 27/11/1997; Art. 9� da Lei 9394/96.
Lei n� 11.892, de 29/12/2008, publicada no DOU de 30/12/2008,Se��o I, p�gs.1-3, institui a Rede Federal de Educa��o Profissional, Cient�fica e Tecnol�gica, cria os Institutos Federais de Educa��o, Ci�ncia e Tecnologia.';

$empresa = $RsEmpresa->fields[3].' - '.$RsEmpresa->fields[4].'
'.$RsEmpresa->fields[5].' - '.$RsEmpresa->fields[6].' - '.
$RsEmpresa->fields[9].'-'.$RsEmpresa->fields[11];


?>