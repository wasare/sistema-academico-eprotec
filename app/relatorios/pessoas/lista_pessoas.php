<?php

require_once("../../../app/setup.php");
require_once("../../../core/reports/header.php");
require_once("../../../core/date.php");

$conn    = new connection_factory($param_conn);
$header  = new header($param_conn);
$data    = new date();

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
    c1.nome || \' - \'|| c1.ref_estado as ref_cidade,
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
    c2.nome || \' - \'|| c2.ref_estado as rg_cidade,
    p.rg_data,
    p.ref_filiacao,
    p.ref_cobranca,
    p.ref_assistmed,
    c3.nome || \' - \'|| c3.ref_estado as ref_naturalidade,
    n1.nacionalidade as ref_nacionalidade,
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
    p.tipo_sangue,
    f.pai_nome,
    f.mae_nome
FROM 
    pessoas p, cidade c1, cidade c2, cidade c3, pais n1, filiacao f
WHERE 
    p.id = ' . $id_pessoa . ' AND
    p.ref_cidade = c1.id AND
    p.rg_cidade  = c2.id AND
    p.ref_nacionalidade = n1.id AND
    p.ref_filiacao = f.id
; ';

$RsPessoa = $conn->Execute($sql);

?>
<html>
    <head>
        <title>SA</title>
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
            <div class="panel">

                <img src="<?=$BASE_URL?>core/pessoa_foto.php?id=<?=$RsPessoa->fields['id'];?>"
                     border="1"
                     alt=""
                     title="<?=$RsPessoa->fields['nome'];?>"
                     width="120" />

                <h2><?=$RsPessoa->fields['nome'];?></h2>
                <strong>Data de registro:</strong>
                <?=$data->convert_date($RsPessoa->fields['dt_cadastro']);?>
                <br />
                <strong>N&uacute;mero de registro:</strong>
                <?=$RsPessoa->fields['id'];?>
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
                <br />
                <br />
                <strong>Sexo:</strong>
                <?php
                if($RsPessoa->fields['sexo'] == 'M'){
                    echo 'Masculino';
                }elseif($RsPessoa->fields['sexo'] == 'F'){
                    echo 'Feminino';
                }
                ?>
                <strong>Data de nascimento</strong>
                <?=$data->convert_date($RsPessoa->fields['dt_nascimento']);?>
                <strong>Estado civil:</strong>
                <?php
                if($RsPessoa->fields['estado_civil'] == 'C'){
                    echo 'Casado';
                }elseif($RsPessoa->fields['estado_civil'] == 'S'){
                    echo 'Solteiro';
                }elseif($RsPessoa->fields['estado_civil'] == null or $RsPessoa->fields['estado_civil'] == ''){
                    echo 'N/D';
                }
                ?>
                <strong>Credo:</strong>
                <?=$RsPessoa->fields['credo'];?>
                <br />
                <strong>Naturalidade:</strong>
                <?=$RsPessoa->fields['ref_naturalidade'];?>
                <strong>Nacionalidade:</strong>
                <?=$RsPessoa->fields['ref_nacionalidade'];?>
                <strong>Tipo sanguineo:</strong>
                <?=$RsPessoa->fields['tipo_sangue'];?>
                <p>
                    <strong>E-mail:</strong>
                    <?=$RsPessoa->fields['email'];?>
                    <strong>E-mail alternativo:</strong>
                    <?=$RsPessoa->fields['email_alt'];?>
                </p>
                <h3>Telefone</h3>
                <strong>Particular:</strong>
                <?=$RsPessoa->fields['fone_particular'];?>
                <br />
                <strong>Profissional:</strong>
                <?=$RsPessoa->fields['fone_profissional'];?>
                <br />
                <strong>Celular:</strong>
                <?=$RsPessoa->fields['fone_celular'];?>
                <br />
                <strong>Recado:</strong>
                <?=$RsPessoa->fields['fone_recado'];?>
                <h3>Documentos</h3>
                <strong>RG:</strong> 
                <?=$RsPessoa->fields['rg_numero'];?>
                <?=$RsPessoa->fields['rg_cidade'];?>
                <?=$data->convert_date($RsPessoa->fields['rg_data']);?>
                <?=$RsPessoa->fields['rg_orgao'];?>
                <strong>CPF:</strong> 
                <?=$RsPessoa->fields['cod_cpf_cgc'];?>
                <strong>Título de eleitor:</strong>
                <?=$RsPessoa->fields['titulo_eleitor'];?>
                <br />
                <h3>Filia&ccedil;&atilde;o</h3>
                <strong>Pai:</strong> 
                <?=$RsPessoa->fields['pai_nome'];?>
                <br />
                <strong>M&atilde;e:</strong>
                <?=$RsPessoa->fields['mae_nome'];?>
                <h3>Observa&ccedil;&atilde;o</h3>
                <?=$RsPessoa->fields['obs'];?>
            </div>
        </div>
    </body>
</html>
