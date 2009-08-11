<?php

require("../../../lib/common.php");
require("../../../configs/configuracao.php");
require("../../../lib/adodb/adodb.inc.php");


$id_pessoa = $_GET['codigo_pessoa'];

if($id_pessoa != ''){


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


	$Conexao = NewADOConnection("postgres");
	$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

	$RsContrato = $Conexao->Execute($sql);

	echo "<h4>Selecione o Contrato/Curso:</h4>";

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

}
else{
	$Result1 = "
	<p><font color='red'>
	<strong>Erro: Entre com um c&oacute;digo de aluno!</strong>
	</font></p>";
}

echo $Result1;

?>