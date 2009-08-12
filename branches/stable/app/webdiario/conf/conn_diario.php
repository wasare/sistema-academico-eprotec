<?php

$host="dados.cefetbambui.edu.br";
$port="5432";
$dbname="sagu";
$options="";
$tty="";
$dbuser="usrsagu";
$dbpassword="x6S8YzrJBs";
$error_msg;


if(($dbconnect = pg_Pconnect("host=$host user=$dbuser password=$dbpassword dbname=$dbname")) == false)
{
   $error_msg="Não foi possível estabeler conexão com o Banco: " . $dbname;
}


?>
