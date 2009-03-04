<?php

/**
* Captura dados do contrato
* @author Santiago Silva Pereira
* @version 1
* @since 23-01-2009
**/

//ARQUIVO DE CONFIGURACAO E CLASSE ADODB
header ("Cache-Control: no-cache");
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");


/**
 * @var string com o codigo do aluno
 */
$id_pessoa = $_GET['codigo_pessoa'];


$sql = "SELECT 
			a.ref_curso,
			b.descricao,
			a.id,
            a.turma
		FROM 
			contratos a,
			cursos b
        WHERE 
			a.ref_pessoa = '$id_pessoa' AND
        	a.dt_desativacao is null AND
        	a.ref_curso = b.id";


//Criando a classe de conex�o ADODB
$Conexao = NewADOConnection("postgres");

//Setando como conex�o persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//Exibindo a descricao do curso caso setado
$RsContrato = $Conexao->Execute($sql);

echo "<h4>Selecione o curso:</h4>";

$cont = 0;

while(!$RsContrato->EOF){

    if($cont == 0){
        $Result1.= '<input type="radio" name="id_contrato" id="id_contrato" value="';
        $Result1.= $RsContrato->fields[2].'" checked /> ';
        $Result1.= $RsContrato->fields[0].' - <b>'.$RsContrato->fields[1];
        $Result1.= '</b> - Turma: '.$RsContrato->fields[3].'<br>';
    }
    else{
        $Result1.= '<input type="radio" name="id_contrato" id="id_contrato" value="';
        $Result1.= $RsContrato->fields[2].'" /> ';
        $Result1.= $RsContrato->fields[0].' - <b>'.$RsContrato->fields[1];
        $Result1.= '</b> - Turma: '.$RsContrato->fields[3].'<br>';
    }
    $cont += 1;

	$RsContrato->MoveNext();
}


$Result1 .= '<br />
             <input type="checkbox" name="checar_turma" id="checar_turma" value="1" /> Filtrar disciplinas por turma.';//somente para matricula regular;

echo $Result1;

?>