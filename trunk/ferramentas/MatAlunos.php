<?php

//session_start();

ini_set('display_errors', 1);

//echo "nome";

//exit;

function Gera_Senha()
{
   
$numero_grande = mt_rand(9999,999999);

if($numero_grande < 0)
{
   $numero_grande *= (-1);
}
$num_insc = $numero_grande;

return $num_insc;
}


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

//$Alunos = array(1908,1523,592,1905,1906,1087,1893,1909,1899);

$qry = 'SELECT ref_pessoa, id FROM contratos WHERE ref_pessoa IN (1908,1523,592,1905,1906,1087,1893,1909,1899) and (ref_curso = 103 OR ref_curso = 104) ORDER BY ref_pessoa';

$Geral = file(dirname(__FILE__).'/csv/MatTmp.csv');

print_r($Geral);

//$Aluno = 1304;

$Periodo = '0602';
$Curso = 301;
$Campus = 1;
$Exibe = 'S';

$Disc = 505020;
$DiscOf = 1076;
$Prof = 2958;



/*
UPDATE 
      matricula
    SET
      ref_disciplina_ofer = 1054
   WHERE 
      ref_disciplina_ofer = 254 AND
      ref_curso = 605 AND
      ref_disciplina = 605021;


select * from matricula where ref_pessoa IN (2181,2183,2185,2195,2196,2197) and ref_curso = 601 and ref_periodo IN ('06021', '06022')

select * diario_notas where ra_cnec IN (2181,2183,2185,2195,2196,2197) and id_ref_curso = 601 and id_ref_periodos IN ('06021', '06022')


$qryMatricula .= ' INSERT INTO diario_notas(ra_cnec, ';
      $qryMatricula .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
      $qryMatricula .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
      $qryMatricula .= ' rel_diario_formulas_grupo)';
      $qryMatricula .= " VALUES($id,$i,0,0,$id,'$Periodo',$Curso,";
      $qryMatricula .= " $DiscOf,'$Grupo');<br />";


*/


$Grupo = $Prof.'-'.$Periodo.'-'.$Disc.'-'.$DiscOf;

$NumNotas = 6;

$qryMatricula = "BEGIN;";

/*
$qryAtualiza .= "SELECT ref_contrato, ref_pessoa, ref_disciplina, "; 
$qryAtualiza .= " ref_disciplina_ofer FROM matricula"; 
$qryAtualiza .= " WHERE ref_pessoa IN ( <br /><br />";
*/

foreach($Geral as $Registro) 
{

    $Item = explode(",",$Registro);

    $id = $Item[0];

    $contrato = $Item[1];

    $qryMatricula .= "INSERT INTO ";
    $qryMatricula .= " matricula( ref_contrato,ref_pessoa,ref_campus,ref_curso, ";
    $qryMatricula .= " ref_periodo,ref_disciplina,ref_disciplina_ofer, ";
    $qryMatricula .= " fl_exibe_displ_hist,dt_matricula,hora_matricula, ";
    $qryMatricula .= " fl_liberado) VALUES($contrato,$id,$Campus,$Curso,";
    $qryMatricula .= " '$Periodo',$Disc,$DiscOf,";
    $qryMatricula .= " '$Exibe',date(now()),now(),NULL ); <br />";

   for($i = 1 ; $i <= $NumNotas; $i++)
   {
      $qryMatricula .= ' INSERT INTO diario_notas(ra_cnec, ';
      $qryMatricula .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
      $qryMatricula .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
      $qryMatricula .= ' rel_diario_formulas_grupo)';
      $qryMatricula .= " VALUES($id,$i,0,0,$id,'$Periodo',$Curso,";
      $qryMatricula .= " $DiscOf,'$Grupo');<br />"; 
   }


   echo '<br /><br />';

    


   // $qryAtualiza .= "ref_pessoa = $pessoa  AND ";
   // $qryAtualiza .= "ref_disciplina = $disc   AND ";
   // $qryAtualiza .= "ref_curso = $curso   AND ";
   // $qryAtualiza .= "ref_periodo = $periodo   AND ";
    //$qryAtualiza .= "turma = $turma   AND ";
   // $qryAtualiza .= "contrato = $contrato   AND ";
   // $qryAtualiza .= "ref_disciplina_ofer = $DiscOf; <br /><br />";
    
}

/*

$sql = "insert into matricula (" .
         "    ref_contrato," .
         "    ref_pessoa," .
         "    ref_campus," .
         "    ref_curso," .
         "    ref_periodo," .
         "    ref_disciplina," .
         "    ref_curso_subst," .
         "    ref_disciplina_subst," .
         "    ref_disciplina_ofer," .
         "    complemento_disc, " .
         "    fl_exibe_displ_hist, " .
         "    dt_matricula," .
         "    hora_matricula, " .
    	 "    status_disciplina)" .
         "  values (" .
         "    '$id_contrato'," .
         "    '$aluno_id'," .
         "    '$ref_campus_ofer'," .
         "    '$ref_curso'," .
         "    '$periodo_id'," .
         "    '$ref_disciplina'," .
         "    '$ref_curso_subst'," .
         "    '$ref_disciplina_subst'," .
         "    '$id_ofer',"  .
         "    get_complemento_ofer('$id_ofer'), " .
         "    'S'," .
         "    date(now())," .
         "    now(), " .
	     "    '$status_disciplina')";0
*/
//$sql = "select * from matricula where id in ( $id )";
//$qryAtualiza .= " );";
//echo $sql;

/*
$qryMatricula = "BEGIN;";
 $id = 1304;
    $contrato = 732;

    $qryMatricula .= "INSERT INTO ";
    $qryMatricula .= " matricula( ref_contrato,ref_pessoa,ref_campus,ref_curso, ";
    $qryMatricula .= " ref_periodo,ref_disciplina,ref_disciplina_ofer, ";
    $qryMatricula .= " fl_exibe_displ_hist,dt_matricula,hora_matricula, ";
    $qryMatricula .= " fl_liberado) VALUES($contrato,$id,$Campus,$Curso,";
    $qryMatricula .= " '$Periodo',$Disc,$DiscOf,";
    $qryMatricula .= " '$Exibe',date(now()),now(),NULL ); <br />";
    
   for($i = 1 ; $i <= $NumNotas; $i++)
{
      $qryMatricula .= ' INSERT INTO diario_notas(ra_cnec, ';
      $qryMatricula .= ' ref_diario_avaliacao,nota,peso,id_ref_pessoas,';
      $qryMatricula .= ' id_ref_periodos,id_ref_curso,d_ref_disciplina_ofer,';
      $qryMatricula .= ' rel_diario_formulas_grupo)';
      $qryMatricula .= " VALUES($id,$i,0,0,$id,'$Periodo',$Curso,";
      $qryMatricula .= " $DiscOf,'$Grupo');<br />";
      
}
   
   echo '<br /><br />';

*/
$qryMatricula .= 'COMMIT;';

echo "<br />$qryMatricula<br />";

//print_r($aSenha);

?>