<?php

require_once(dirname(__FILE__) .'/../webdiario.conf.php');

$host     = $webdiario_host;
$database = $webdiario_database;
$user     = $webdiario_user;
$password = $webdiario_password;
$port     = $webdiario_port;

$error_msg = "";


if(($dbconnect = pg_Pconnect("host=$host user=$user password=$password dbname=$database")) == false)
{
   $error_msg="Não foi possível estabeler conexão com o Banco: " . $dbname;
}


?>
