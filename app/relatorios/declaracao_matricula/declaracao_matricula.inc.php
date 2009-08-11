<?php 
require("../../../lib/common.php");
require("../../../configs/configuracao.php");
require("../../../core/reports/carimbo.php");
require("../../../core/date.php");

$conn = new connection_factory($param_conn);
$date = new date();

$contrato 	= $_POST["id_contrato"];
$data 		= $_POST["data"];
$carimbo_id = $_POST['carimbo'];


/* Formatando a data */
if($data == ''){
	$data = date("d/m/Y");
}

$data = explode("/",$data,3);
$mes  = $date->mes($data[1]);

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

$RsEmpresa = $conn->Execute($sqlEmpresa);

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

$RsContrato = $conn->Execute($sqlContrato);

/* Formatando a data de nascimento */
if($RsContrato->fields[6] != ''){
	
	$data_nascimento = explode("-",$RsContrato->fields[6],3);
	$mes_nascimento = $date->mes($data_nascimento[1]);
}

$corpo = '        Declaro para os devidos fins que '.$RsContrato->fields[3].
', filho(a) de '.$RsContrato->fields[7].' e '.$RsContrato->fields[8].
', nascido(a) em '.$data_nascimento[2].' de '.$mes_nascimento.' de '.
$data_nascimento[0].', natural de '.$RsContrato->fields[4].'/'.$RsContrato->fields[5].
', encontra-se devidamente matriculado(a) no curso '.$RsContrato->fields[2].
', neste estabelecimento de ensino.
            Por ser verdade e estar de acordo com nossos arquivos, assino a presente.';

$data_declaracao = $RsContrato->fields[1].', '.$data[0].' de '.$mes.' de '.$data[2];

$carimbo = new carimbo($param_conn);
$carimbo_nome = $carimbo->get_nome($carimbo_id);
$carimbo_dados = $carimbo->get_funcao($carimbo_id);

$decretos = 'Obs.:
Decreto Nº 3.864/A de 24/01/61 - Criação da Escola
Decreto Nº 55.358 de 13/0264 - Transformado em Ginásio Agrícola
Decreto Nº 63.923 de 30/12/68 - Transformado em Colégio Agrícola
Decreto Nº 83.935 de 04/09/79 - Denominado Escola Agrotécnica Federal de Bambuí-MG
Lei Nº 8.731/93 de 16/11/1993 - Transformação em Autarquia
Transformado em Centro Federal de Educação Tecnológica de Bambuí, através do Decreto
Presidencial de 17/12/2002, publicado no DOU de 18/12/2002,Seção I, página 12, de acordo com o Decreto Federal nº 2406 de 27/11/1997; Art. 9º da Lei 9394/96.
Lei nº 11.892, de 29/12/2008, publicada no DOU de 30/12/2008,Seção I, págs.1-3, institui a Rede Federal de Educação Profissional, Científica e Tecnológica, cria os Institutos Federais de Educação, Ciência e Tecnologia.';

$empresa = $RsEmpresa->fields[3].' - '.$RsEmpresa->fields[4].'
'.$RsEmpresa->fields[5].' - '.$RsEmpresa->fields[6].' - '.
$RsEmpresa->fields[9].'-'.$RsEmpresa->fields[11];

?>