<?php

/**
* SCRIPT QUE BUSCA OS DADOS DE CADASTRO ON-LINE NO VESTIBULAR E PROCESSO SELETIVO
* E GERA UM UMA SQL
* 
* Autor: Santiago S. Pereira - santiago@cefetbambui.edu.br
* Banco do vestibular: SelecaoDB (O esquema refere-se ao periodo e graduação) 
*/

$Geral = file(dirname(__FILE__).'/csv/sup_vest_0901_formiga.csv');

$sqlBusca = '
SELECT 
  "cvPessoaNome",
  "dDataNascimento",
  "cSexo",
  "cvTelefoneContato",
  "cvLogradouro",
  "cvLogradouroNumero",
  "cvBairro",
  "iCidadeID",
  "cvCep",
  "cvCpf"
FROM
  "20091G"."Inscricao" 
WHERE
  "iInscricaoID" IN(';

foreach($Geral as $Registro) 
{

    $Item = explode(";",$Registro);

    $sqlBusca .= trim($Item[0]) . ", ";
}

$sqlBusca .= ');';

echo $sqlBusca;

?>