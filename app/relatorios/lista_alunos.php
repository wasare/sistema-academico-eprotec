<?php

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");
require("../../lib/adodb/tohtml.inc.php");


//RECEBENDO OS DADOS DO FORMULARIO --
$periodo = $_POST["periodo1"];
$aluno = $_POST["aluno"];
$curso = $_POST["codigo_curso"];
$resp_nome = $_POST["resp_nome"];
$resp_cargo = $_POST["resp_cargo"];
$turma = $_POST["turma"];



//MONTANDO A SQL PARA A CONSULTA --

$sql = " SELECT DISTINCT p.id as \"Código\""; 

if (isset($_POST["nome"])) $sql .= ', p.nome as "Nome" '; 

if (isset($_POST["turma2"])) { 

    $sql .= ', t.turma as "Turma" ';
    $condicao_turma = "AND t.ref_curso = c.ref_curso AND t.ref_pessoa = p.id";
    $tabela_contrato = ", public.contratos t";
}

if ($turma != '') { 

    $condicao_turma .= " AND t.turma = '$turma' ";
    $tabela_contrato = ", public.contratos t ";
}


//Dados de Filiacao
if (isset($_POST["pai"])) { 

    $sql .= ', f.pai_nome as "Pai"';
    //$condicao_filiacao = " p.ref_filiacao = f.id AND ";

    $tabela_filiacao = "LEFT OUTER JOIN filiacao f ON(p.ref_filiacao = f.id)";
    //$tabela_filiacao = ", public.filiacao f ";
}

if (isset($_POST["mae"])) {

    $sql .= ', f.mae_nome as "Mae" ';
    //$condicao_filiacao = " p.ref_filiacao = f.id AND ";

    $tabela_filiacao = "LEFT OUTER JOIN filiacao f ON(p.ref_filiacao = f.id)";
    //$tabela_filiacao = ", public.filiacao f ";
}

if (isset($_POST["endereco"])) {

    $sql .= ", p.rua || '  ' || CASE WHEN p.complemento IS NULL THEN ' ' ELSE p.complemento END AS \"Endereço\"";
    //$sql .= ", p.rua || '  ' || p.complemento as \"Endereço\"";
    //$sql .= ", p.complemento  as \"N.\"";
    //|| '  ' || p.complemento
}

if (isset($_POST["bairro"])) {
    $sql .= ', p.bairro as "Bairro"';
}

//Dados de Cidade
if (isset($_POST["cidade"])) {

    $sql .= ', m.nome || \'-\' || m.ref_estado as "Cidade"';
    $condicao_municipio = " p.ref_cidade = m.id AND ";
    $tabela_municipio = ", public.aux_cidades m";

}

if (isset($_POST["cep"])) {
    $sql .= ', p.cep as "CEP"';
}

if (isset($_POST["telefone"])) {

    $sql .= ', p.fone_particular as "Tel. Part."
        , p.fone_profissional as "Tel. Prof."
        , p.fone_celular as "Tel. Cel."
        , p.fone_recado as "Tel. Rec."
      ';

}

if (isset($_POST["rg"])) $sql .= ', p.rg_numero as "RG"'; 

if (isset($_POST["cpf"])) $sql .= ', p.cod_cpf_cgc as "CPF"'; 

if (isset($_POST["sexo"])) $sql .= ', p.sexo as "Sexo"'; 	

if (isset($_POST["data_nascimento"])) $sql .= ', to_char(p.dt_nascimento, \'DD/MM/YYYY\') as "Data de Nascimento"'; 

$sql .= " 

  FROM
  pessoas p $tabela_filiacao, matricula c $tabela_contrato $tabela_municipio
  WHERE
  c.ref_periodo = '$periodo' AND ";

if ($curso != '') $sql .= " c.ref_curso = '$curso' AND"; 

//$sql .= $condicao_filiacao; 
$sql .= $condicao_municipio;

if ($aluno != '')  $sql .= " p.id = '$aluno' AND ";

$sql .= " 
  c.ref_pessoa = p.id
$condicao_turma 

  ORDER BY 2";

$sql = 'SELECT * FROM ('. $sql .') AS T1 ORDER BY lower(to_ascii(2));';

//echo $sql;
//die();


//Criando a classe de conexão ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");


$RsCurso = $Conexao->Execute("SELECT descricao ||' (' || id || ') ' as \"Curso\" FROM cursos WHERE id = $curso;");
$info = "<h4>" . $RsCurso->fields[0] . "</h4>";

//Exibindo a descricao do periodo
$RsPeriodo = $Conexao->Execute("SELECT descricao FROM periodos WHERE id = '$periodo';");
$DescricaoPeriodo = $RsPeriodo->fields[0];


//EXECUTANDO SQL DA CONSULTA PRINCIPAL
$Result1 = $Conexao->Execute($sql);

//numero de ocorrencias
$num_result = $Result1->RecordCount();


//Informacoes de cabecalho
$info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Hora: </strong>" . date("H:i:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Total de Registros: </strong>" . $num_result . "&nbsp;&nbsp;-&nbsp;&nbsp;";
$info .= "<strong>Período: </strong> <span>$DescricaoPeriodo</span> <br><br>";

//Dados de rodape com assinatura
$rodape = '<span style="font-size: 12px;">' . $resp_nome . "</span><br>";
$rodape .= '<span style="font-size: 9px;"><strong>' . $resp_cargo . "</strong></span><br>";

?>
<html>
    <head>
        <title>Lista de Alunos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../Styles/style.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" marginwidth="20" marginheight="20">
        <div style="width: 760px;">
            <div align="center" style="text-align:center; font-size:12px;">
                <img src="../../images/armasbra.jpg" width="57" height="60"><br />
                MEC-SETEC<br />
                CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
                SETOR DE REGISTROS ESCOLARES
                <br /><br /><br />
            </div>
            <h2>RELAT&Oacute;RIO DE ALUNOS MATRICULADOS</h2>
            <?php echo $info; ?>
            <?php rs2html($Result1, 'width="90%" cellspacing="0" border="0" class="tabela_relatorio" cellpadding="0"'); ?>
            <p>
                <div align="center" style="width: 90%;">
                    <p>&nbsp;</p>
                    __________________________________________<br>
                <?php echo $rodape; ?> </div>
            </p>
        </div>
    </body>
</html>
