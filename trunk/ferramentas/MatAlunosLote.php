<?php

//session_start();

ini_set('display_errors', 1);


function smail($to, $sujet, $msg, $from, $pattern = false) 
{
   /*
   if (is_file($msg) && is_array($pattern)) 
   {
      foreach($pattern as $key => $output) 
      {
           $base[] = $key;
           $bnew[] = $output;
      }
      
      $fd = @fopen($msg, "r");
      
      while(!feof($fd)) 
      {
           $line = fgets($fd, 2048);
           $content .= str_replace($base, $bnew, $line);
      }
      fclose($fd);
   } 
   else 
{ */
       $content = $msg;
 //  }
  
   mail($to, $sujet, $content,
       "From: $from\r\n"
       ."Reply-To: $from\r\n"
       ."X-Mailer: PHP/" . phpversion());   
}

$TO = 'wanderson@cefetbambui.edu.br';
$FROM = 'diario@cefetbambui.edu.br';

//$qry = 'SELECT ref_pessoa, id FROM contratos WHERE ref_pessoa IN (1908,1523,592,1905,1906,1087,1893,1909,1899) and (ref_curso = 103 OR ref_curso = 104) ORDER BY ref_pessoa';

/*

UPDATE matricula  SET ref_curso = 501 WHERE ref_contrato ,ref_pessoa,ref_campus,ref_curso, ref_periodo,ref_disciplina,ref_disciplina_ofer, fl_exibe_displ_hist,dt_matricula,hora_matricula, fl_liberado) VALUES(3617,2972,1,502, '0701',501015,1529, 'S',date(now()),now(),NULL );

*/

$Aluno = file(dirname(__FILE__).'/csv/Aluno.csv');

$Diario = file(dirname(__FILE__).'/csv/Diario.csv');


//$aC501 = file(dirname(__FILE__).'/csv/C501.csv');
//$aC502 = file(dirname(__FILE__).'/csv/C502.csv');

$aC301 = file(dirname(__FILE__).'/csv/C301_3.csv');

$aDiarios = file(dirname(__FILE__).'/csv/Diarios.csv');
/*

foreach($aC501 as $Registro)
{
    $Item = explode(",",$Registro);
    $c = trim($Item[0]);
    $p = trim($Item[1]);
    $C501["$p"] = "$c";
}

foreach($aC502 as $Registro) 
{
    $Item = explode(",",$Registro);
    $c = trim($Item[0]);
    $p = trim($Item[1]);
    $C502["$p"] = "$c";
}
*/
foreach($aC301 as $Registro) 
{
    $Item = explode(",",$Registro);
    $p = trim($Item[0]);
    $c = trim($Item[1]);
    $C301["$p"] = "$c";
}


foreach($aDiarios as $Registro)
{
    $Item = explode(",",$Registro);
    $d = trim($Item[0]);
    $id = trim($Item[1]);
    $DD["$d"] = "$id";
}

/*
print_r($C501);

echo '<br /><br />';

print_r($C502);
*/

//print_r($DD);

$Periodo = '0701';
$Curso = 304;
$Campus = 1;
$Exibe = 'S';

$qryMatricula = "BEGIN;";


foreach($Aluno as $Registro) 
{

   $id = trim($Registro);

  foreach($Diario as $reg)
  {
    $DiscOf = trim($reg);

    $id = trim($Registro);

    if($Curso == 501) { $contrato = $C501["$id"]; }
    if($Curso == 502) { $contrato = $C502["$id"]; }
    if($Curso == 301) { $contrato = $C301["$id"]; }
    if($Curso == 304) { $contrato = $C301["$id"]; }

    if(is_numeric($contrato) ) {

    $Disc = $DD["$DiscOf"];

    $qryMatricula .= "INSERT INTO ";
    $qryMatricula .= " matricula( ref_contrato,ref_pessoa,ref_campus,ref_curso, ";
    $qryMatricula .= " ref_periodo,ref_disciplina,ref_disciplina_ofer, ";
    $qryMatricula .= " fl_exibe_displ_hist,dt_matricula,hora_matricula, ";
    $qryMatricula .= " fl_liberado) VALUES($contrato,$id,$Campus,$Curso,";
    $qryMatricula .= " '$Periodo',$Disc,$DiscOf,";
    $qryMatricula .= " '$Exibe',date(now()),now(),NULL ); <br />";

/*  
    $qryMatricula .= " UPDATE matricula SET ref_curso = 501 ";
    $qryMatricula .= " WHERE ref_contrato = $contrato AND ref_pessoa = $id AND ref_curso = $Curso ";
    $qryMatricula .= " AND ref_disciplina_ofer = $DiscOf; <br />";
*/
    }
    else  {
      $qryMatricula .= " <br />--Falta contrato aluno: $id<br /> <br />";
    }

   }


   echo '<br /><br />';

}


$qryMatricula .= 'COMMIT;';

echo "<br />$qryMatricula<br />";


?>