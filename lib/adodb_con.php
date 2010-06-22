<?php


require("../../../common.php");
 
function getBRDate($date)
{ // YYYY-MM-DD a DD-MM-YYYY
list($year, $month, $day) = split("-", $date);
return $day . "/" . $month . "/" . $year;
}

session_start();


error_reporting(0);

ini_set(display_errors, '0');

//PATH ONDE SE ENCONTRA A CLASSE ADODB
require("adodb/adodb.inc.php");

//EFETUA A CONEXÃO
//$dsn = 'postgres://aluno:a1srv27@localhost/sagu?persist';

//$conn = &ADONewConnection($dsn);

$conn = NewADOConnection('postgres');

$conn->Connect('localhost', "$LoginUID", "$LoginPWD", "$LoginDB");

#$arg = "dbname=$LoginDB port=5432 user=$LoginUID password=$LoginPWD";

?>

