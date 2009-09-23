<?php

require_once("../../../app/setup.php");
require_once("../../../core/reports/header.php");

$conn    = new connection_factory($param_conn);
$header  = new header($param_conn);

$id_pessoa = $_GET['id_pessoa'];

$sql = '
SELECT
    p.id,
    p.identificacao,
    p.titulo_academico,
    p.nome,
    p.rua,
    p.complemento,
    p.bairro,
    p.cep,
    p.ref_cidade,
    p.fone_particular,
    p.fone_profissional,
    p.fone_celular,
    p.fone_recado,
    p.email,
    p.email_alt,
    p.estado_civil,
    p.dt_cadastro,
    p.tipo_pessoa,
    p.obs,
    p.dt_nascimento,
    p.sexo,
    p.credo,
    p.nome_fantasia,
    p.cod_inscricao_estadual,
    p.rg_numero,
    p.rg_cidade,
    p.rg_data,
    p.ref_filiacao,
    p.ref_cobranca,
    p.ref_assistmed,
    p.ref_naturalidade,
    p.ref_nacionalidade,
    p.ref_segurado,
    p.cod_cpf_cgc,
    p.titulo_eleitor,
    p.conta_laboratorio,
    p.conta_provedor,
    p.regc_livro,
    p.regc_folha,
    p.regc_local,
    p.regc_nasc_casam,
    p.ano_1g,
    p.cidade_1g,
    p.ref_curso_1g,
    p.escola_1g,
    p.ano_2g,
    p.cidade_2g,
    p.ref_curso_2g,
    p.escola_2g,
    p.graduacao,
    p.cod_passivo,
    p.senha,
    p.fl_dbfolha,
    p.ref_pessoa_folha,
    p.fl_documentos,
    p.fl_documentos_fora,
    p.fl_quitacao_eleitoral,
    p.fl_segurado,
    p.nome2,
    p.fl_cartao,
    p.deficiencia,
    p.cidade,
    p.nacionalidade,
    p.in_sagu,
    p.cod_externo,
    p.deficiencia_desc,
    p.dt_responsavel,
    p.rg_orgao,
    p.placa_carro,
    p.fl_dados_pessoais,
    p.seguro_meses,
    p.ra_cnec,
    p.tipo_sangue
FROM 
    pessoas p, cidade c
WHERE 
    p.id = ' . $id_pessoa . ' AND
    p.ref_cidade = c.id
; ';

$RsPessoa = $conn->Execute($sql);

?>
<html>
    <head>
        <title>Lista de Alunos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="../../../public/styles/formularios.css" rel="stylesheet" type="text/css" />
    </head>
    <body marginwidth="20" marginheight="20">
        <div style="width: 760px;" align="center">
            <div align="center" style="text-align:center; font-size:12px;">
                <?php echo $header->get_empresa($PATH_IMAGES); ?>
                <br /><br />
            </div>
            <h2>Informa&ccedil;&otilde;es pessoais</h2>
            <div class="box_geral">

                <img src="<?=$BASE_URL?>core/pessoa_foto.php?id=<?=$RsPessoa->fields['id'];?>"
                     border="1"
                     alt=""
                     title="<?=$RsPessoa->fields['nome'];?>"
                     width="120" />

                <h2><?=$RsPessoa->fields['nome'];?></h2>
                <strong>Data de registro:</strong>
                <?=$RsPessoa->fields['dt_cadastro'];?>
                <strong>N&uacute;mero de registro:</strong>
                <?=$RsPessoa->fields['id'];?>
                <br />
                <strong>Sexo:</strong>
                <?=$RsPessoa->fields['sexo'];?>
                <strong>Data de nascimento</strong>
                <?=$RsPessoa->fields['dt_nascimento'];?>
                <strong>Estado civil:</strong>
                <?=$RsPessoa->fields['estado_civil'];?>
                <strong>Credo:</strong>
                <?=$RsPessoa->fields['credo'];?>
                <br />
                <strong>Naturalidade:</strong>
                <?=$RsPessoa->fields['ref_naturalidade'];?>
                <strong>Nacionalidade:</strong>
                <?=$RsPessoa->fields['ref_naturalidade'];?>
                <strong>Tipo sanguineo:</strong>
                <?=$RsPessoa->fields['tipo_sangue'];?>
                <br /><br />
                <strong>Endere&ccedil;o:</strong> 
                <?=$RsPessoa->fields['rua'];?> <?=$RsPessoa->fields['complemento'];?>
                <br />
                <strong>Bairro:</strong> 
                <?=$RsPessoa->fields['bairro'];?>
                <strong>Cidade:</strong> 
                <?=$RsPessoa->fields['ref_cidade'];?>
                <strong>CEP:</strong>
                <?=$RsPessoa->fields['cep'];?>
                <p>
                    <strong>E-mail:</strong>
                    <?=$RsPessoa->fields['email'];?>
                    <strong>E-mail alternativo:</strong>
                    <?=$RsPessoa->fields['email_alt'];?>
                </p>
                <h3>Telefone</h3>
                <strong>Particular:</strong>
                <?=$RsPessoa->fields['fone_particular'];?>
                <strong>Profissional:</strong>
                <?=$RsPessoa->fields['fone_profissional'];?>
                <strong>Celular:</strong>
                <?=$RsPessoa->fields['fone_celular'];?>
                <strong>Recado:</strong>
                <?=$RsPessoa->fields['fone_recado'];?>
                <h3>Documentos</h3>
                <strong>RG:</strong> 
                <?=$RsPessoa->fields['rg_numero'];?>
                <?=$RsPessoa->fields['rg_cidade'];?>
                <?=$RsPessoa->fields['rg_data'];?>
                <?=$RsPessoa->fields['rg_orgao'];?>
                <strong>CPF:</strong> 
                <?=$RsPessoa->fields['cod_cpf_cgc'];?>
                <strong>Título de eleitor:</strong>
                <?=$RsPessoa->fields['titulo_eleitor'];?>
                <br />
                <h3>Filia&ccedil;&atilde;o</h3>
                <strong>Pai:</strong>
                <br />
                <strong>M&atilde;e:</strong>
                <h3>Observa&ccedil;&atilde;o</h3>
                <?=$RsPessoa->fields['obs'];?>
            </div>
        </div>
    </body>
</html>
