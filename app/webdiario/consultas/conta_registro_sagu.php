<?php

require_once('../conf/conn_diario.php');
    

$sql1 ="select relname from pg_class where relkind = 'r'";

$query1 = pg_exec($dbconnect, $sql1);

$numRegistros = 0;

while($linha1 = pg_fetch_array($query1)) 
{
      $tbName = $linha1["relname"];
      $result = explode("_", $tbName);
      if($result[0] != "pg" AND $result[0] != "sql")
      {
         $qry = "SELECT * FROM \"$tbName\"";
         //echo $qry;
         $qryexec = pg_exec($dbconnect, $qry);
         $numRegistros +=  pg_numrows($qryexec);
      }
}


echo "<h3>N&uacute;mero de Registros no SAGU: <font color=\"red\">$numRegistros</font></h3>";





?>
