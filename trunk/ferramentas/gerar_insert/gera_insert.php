<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 

 * SQL para gerar o csv:

SELECT DISTINCT
  matricula.ref_pessoa, "AlunoID"
FROM
  matricula LEFT OUTER JOIN "AcessoAluno" ON
    (matricula.ref_pessoa = "AcessoAluno"."AlunoID")
WHERE
  matricula.ref_periodo LIKE '08%' AND
  "AlunoID" IS NULL
ORDER BY ref_pessoa

 */

$Geral = file(dirname(__FILE__).'/alunos_sem_acesso.csv');

$sql = "BEGIN;<br>";

foreach($Geral as $Registro)
{
    $item = explode(";",$Registro);
    $id = trim($item[0]);
    /*
    $nasc = trim($Item[1]);
     */
    $sql .= 'INSERT INTO "AcessoAluno" VALUES('.$id.',md5(\'0'.$id.'\'));<br>';

}

$sql .= 'COMMIT;';

echo "<br />$sql<br />";

?>