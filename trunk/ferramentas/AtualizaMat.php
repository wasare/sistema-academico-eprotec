<?php

//session_start();

ini_set(display_errors, "1");

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

   
$Geral = file(dirname(__FILE__).'/csv/AtualizaMat.csv');

$DiscOf = 987; 

$qryAtualiza = "";
/*
$qryAtualiza .= "SELECT ref_contrato, ref_pessoa, ref_disciplina, "; 
$qryAtualiza .= " ref_disciplina_ofer FROM matricula"; 
$qryAtualiza .= " WHERE ref_pessoa IN ( <br /><br />";
*/
foreach($Geral as $Registro) 
{    
    $Item = explode(",",$Registro);
    
    $id = $Item[0];
    $of = $Item[1];
    $disc = $Item[2];
    $periodo = $Item[3];
    $pessoa = $Item[4];
    $contrato = $Item[5];
    $curso = $Item[6];
/*
    $qryAtualiza .= "UPDATE ";
    $qryAtualiza .= "  matricula SET ref_disciplina_ofer = $of ";
    $qryAtualiza .= " WHERE ";
    $qryAtualiza .= "id = $id  AND ";
    $qryAtualiza .= "ref_pessoa = $pessoa  AND ";
    $qryAtualiza .= "ref_disciplina = $disc   AND ";
    $qryAtualiza .= "ref_curso = $curso   AND ";
    $qryAtualiza .= "ref_periodo = $periodo   AND ";
    //$qryAtualiza .= "turma = $turma   AND ";
   // $qryAtualiza .= "contrato = $contrato   AND ";
    $qryAtualiza .= "ref_disciplina_ofer = $DiscOf; <br /><br />";
*/
  
/*
    $qryAtualiza .= "UPDATE ";
    $qryAtualiza .= "  matricula SET ref_disciplina_ofer = $DiscOf ";
    $qryAtualiza .= " WHERE ";
    $qryAtualiza .= "ref_pessoa = $Item[0]  AND ";
    $qryAtualiza .= "ref_disciplina = 503005   AND ";
    $qryAtualiza .= "ref_curso = 505   AND ";
    $qryAtualiza .= "ref_disciplina_ofer = 119; <br /><br />";
    
*/

    $qryAtualiza .= "UPDATE ";
    $qryAtualiza .= "  matricula SET ref_disciplina_ofer = $of ";
    $qryAtualiza .= " WHERE ";
    $qryAtualiza .= "id = $id  AND ";
   // $qryAtualiza .= "ref_pessoa = $pessoa  AND ";
   // $qryAtualiza .= "ref_disciplina = $disc   AND ";
   // $qryAtualiza .= "ref_curso = $curso   AND ";
   // $qryAtualiza .= "ref_periodo = $periodo   AND ";
    //$qryAtualiza .= "turma = $turma   AND ";
   // $qryAtualiza .= "contrato = $contrato   AND ";
    $qryAtualiza .= "ref_disciplina_ofer = $DiscOf; <br /><br />";
    
}

//$sql = "select * from matricula where id in ( $id )";
//$qryAtualiza .= " );";
//echo $sql;
echo "<br />$qryAtualiza<br />";

//print_r($aSenha);

?>