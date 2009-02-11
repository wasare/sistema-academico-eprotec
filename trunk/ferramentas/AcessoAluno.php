<?php

//session_start();

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



$qryAcesso = "";

for($i = 3666 ; $i <= 3700 ; $i++) 
{
    $pw = str_pad($i, 5, "0", STR_PAD_LEFT);
    
    $qryAcesso .= 'INSERT INTO "AcessoAluno"("AlunoID", "cvSenha") ';
    $qryAcesso .= ' VALUES('.$i.', md5(\''.$pw.'\'));';
    $qryAcesso .= "<br /> <br />";
}


echo "<br />$qryAcesso<br />";

//print_r($aSenha);

?>