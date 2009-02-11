<?php

session_start();

set_time_limit(0);

ini_set("display_errors", 1);

$Geral = file(dirname(__FILE__).'/csv/NotasExtra.csv');

$qryDiario = 'BEGIN;';

foreach ( $Geral as $Dados ) {

  $Item = explode(";",$Dados);

  $ref_pessoa = trim($Item[0]);
  $getperiodo = '0'.trim($Item[1]);
  $getcurso   = trim($Item[2]);
  $getofer   = trim($Item[3]);
  $grupo   = trim($Item[4]);

  $qryDiario .= ' INSERT INTO diario_notas(ra_cnec, ';
  $qryDiario .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
  $qryDiario .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
  $qryDiario .= ' rel_diario_formulas_grupo)';
  $qryDiario .= " VALUES($ref_pessoa,'7','-1','0',$ref_pessoa,'$getperiodo',$getcurso,";
  $qryDiario .= " $getofer,'$grupo');";

}

$qryDiario .= 'COMMIT;';

echo "<br />$qryDiario<br />";


?>