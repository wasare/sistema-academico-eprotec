<?php


session_start();

set_time_limit(0);

ini_set("display_errors", 1);

$Geral = file(dirname(__FILE__).'/csv/cod_alunos.csv');

$qryDiario = 'BEGIN;<br/>';

foreach ( $Geral as $Dados ) {

  	$Item = explode(";",$Dados);
	$ref_pessoa = trim($Item[0]);
 	$qryDiario .= ' INSERT INTO diario_notas(ra_cnec,ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
  	$qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,rel_diario_formulas_grupo)';
  	$qryDiario .= " VALUES($ref_pessoa,'7','-1','0',$ref_pessoa,'0701',106,1706,'2485-0701-107012-1706');";
  	//grupo: professor-periodo-disciplina-diario
	$qryDiario .= "<br/><br/>";
}

$qryDiario .= 'COMMIT;';

echo "$qryDiario";


?>