<?php

/**
* SCRIPT QUE GERA UMA SQL PARA MIGRAR DADOS DO VESTIBULAR E PROCESSO SELETIVO
* PARA O BANCO DO SAGU
* 
* Autor: Santiago S. Pereira - santiago@cefetbambui.edu.br
* Banco do vestibular: SelecaoDB (O esquema refere-se ao periodo e graduação) 
* Banco do sagu: sagu
*
* Campos da tabela inscricao
* ==========================
* cvPessoaNome;
* dDataNascimento;
* cSexo;
* cvTelefoneContato;
* cvLogradouro;
* cvLogradouroNumero;  
* cvBairro;
* iCidadeID;
* cvCep;
* cvCpf
**/


ini_set('display_errors', 1);

$Geral = file(dirname(__FILE__).'/csv/tec_sagu_0901.csv');

$qryPessoas = "BEGIN;";

foreach($Geral as $Registro) 
{

    $Item = explode(";",$Registro);

    $nome = trim($Item[0]);
    $nasc = trim($Item[1]);
    $sexo = trim($Item[2]);
    $fone = trim($Item[3]);
    $rua = trim($Item[4]);
    $num = trim($Item[5]);
    $bairro = trim($Item[6]);
    $cidade = trim($Item[7]);
    $cep = trim($Item[8]);
    $cpf = trim($Item[9]);


    $qryPessoas .= "INSERT INTO ";
    $qryPessoas .= " pessoas (nome, rua, complemento, bairro, ref_cidade, cep, fone_particular, ";
    $qryPessoas .= " dt_nascimento, sexo, cod_cpf_cgc, dt_cadastro) VALUES (  ";
    $qryPessoas .= " '$nome','$rua','$num','$bairro',$cidade,";
    $qryPessoas .= " '$cep','$fone','$nasc', '$sexo', '$cpf', ";
    $qryPessoas .= " date(now()) ); <br />";

   echo '<br /><br />';

    
}
/*
nome  character varying(80)   
rua   character varying(50)   
complemento   character varying(50)   
bairro  character varying(40)   
cep   character varying(9)  
fone_particular   character varying(50)   
dt_cadastro   date  
dt_nascimento   date  
sexo  character(1)  
cod_cpf_cgc   character varying(18)   
ra_cnec   character varying(8)  
*/


$qryPessoas .= 'COMMIT;';

echo "<br />$qryPessoas<br />";

//print_r($aSenha);

?>