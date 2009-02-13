<?php
include("conf/webdiario.conf.php");

setcookie ("us", "0", time( )-9999);
setcookie ("login", "0", time( )-9999);
$_SESSION = array();
session_destroy();

$ip=$_SERVER["REMOTE_ADDR"];
$pagina=$_SERVER["PHP_SELF"];
$status="EFETUOU LOGOUT";
$usuario = trim($us);
$sql_store = htmlspecialchars("$usuario");
$Data = date("Y-m-d");
$Hora = date("H:i:s");
$sqllog = "insert into diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) values('$sql_store','$Data','$Hora','$ip','$pagina','$status','Senha Valida')";
$query1 =  pg_exec($dbconnect, $sqllog);

header("location: index.php");
?>