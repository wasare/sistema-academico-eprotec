<?php

//require_once(dirname(__FILE__) .'/../../../configuracao.php');

require_once(dirname(__FILE__) .'/../webdiario.conf.php');



$options="";
$tty="";
$error_msg = "";


if(($dbconnect = pg_Pconnect("host=$host user=$user password=$password dbname=$database")) == false)
{
   $error_msg="Não foi possível estabeler conexão com o Banco: " . $dbname;
}


?>
