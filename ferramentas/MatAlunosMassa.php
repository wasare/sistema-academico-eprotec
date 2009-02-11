<?php

//session_start();

ini_set('display_errors', 1);

//$qry = 'SELECT ref_pessoa, id FROM contratos WHERE ref_pessoa IN (1908,1523,592,1905,1906,1087,1893,1909,1899) and (ref_curso = 103 OR ref_curso = 104) ORDER BY ref_pessoa';

/*

UPDATE matricula  SET ref_curso = 501 WHERE ref_contrato ,ref_pessoa,ref_campus,ref_curso, ref_periodo,ref_disciplina,ref_disciplina_ofer, fl_exibe_displ_hist,dt_matricula,hora_matricula, fl_liberado) VALUES(3617,2972,1,502, '0701',501015,1529, 'S',date(now()),now(),NULL );

*/

$Aluno = file(dirname(__FILE__).'/csv/Alunos.csv');

$Diario = file(dirname(__FILE__).'/csv/Diarios.csv');


//print_r($Diario);


$Periodo = '0702';
$Curso = 501;
$Campus = 1;
$Exibe = 'S';

$qryMatricula = "BEGIN; <br />";


foreach($Aluno as $Registro) 
{

  $ItemAluno = explode(";", $Registro);

  $id = trim($ItemAluno[0]);
  $contrato = trim($ItemAluno[1]);

  foreach($Diario as $reg)
  {
    $ItemDiario = explode(";", $reg);

    $Disc = trim($ItemDiario[0]);
    $DiscOf = trim($ItemDiario[1]);

    $qryMatricula .= "INSERT INTO ";
    $qryMatricula .= " matricula( ref_contrato,ref_pessoa,ref_campus,ref_curso, ";
    $qryMatricula .= " ref_periodo,ref_disciplina,ref_disciplina_ofer, ";
    $qryMatricula .= " fl_exibe_displ_hist,dt_matricula,hora_matricula, ";
    $qryMatricula .= " fl_liberado) VALUES($contrato,$id,$Campus,$Curso,";
    $qryMatricula .= " '$Periodo',$Disc,$DiscOf,";
    $qryMatricula .= " '$Exibe',date(now()),now(),NULL ); <br />";

    }

}

echo '<br /><br />';


$qryMatricula .= 'COMMIT;';

echo "<br />$qryMatricula<br />";


?>