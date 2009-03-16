<?php 

require_once("config.php");


//Dados de index.php
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
$home = $_POST['home'];


// load database abstraction layer
//$no_login_check = true; // ! não fazer um check do login!

$CookieDomain = "$sagu_cookie";// lib/config.php
$SessionAuth  = "$uid:$pwd";
$SessionID    = getmypid();

setcookie('no_login_check',true,time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionAuth","$SessionAuth",time() + 12 * 3600,"/","$CookieDomain",0);

require_once("common.php");

$conn = new Connection;
$cid = @$conn->Open(true); // @ deixa de fazer o tratamento de erro padrao
@$conn->Close();

// caso tem acesso log o LOGIN
if ( ! $cid )
{
    LogSQL("*** LOGIN RECUSADO (db=$LoginDB,uid=$uid,pwd=) ***");
    include("../msg_login_recusado.php");
    exit;
}



// gravar autenticação do usuário no cookie SessionAuth
//SetCookie("SessionAuth","$SessionAuth",time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionID","$SessionID",time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionUsuario","$uid",time() + 12 * 3600,"/","$CookieDomain",0);

// SaguAssert(empty($home),"Nome do arquivo inicial nao especificado!");
LogSQL("*** LOGIN ACEITO (db=$LoginDB,uid=$uid,pwd=) ***");

Header("Location: $home?login_id=$uid");

?>
