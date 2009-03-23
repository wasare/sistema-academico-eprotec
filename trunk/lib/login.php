<?php 

require_once("config.php");

$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
$home = $_POST['home'];

$CookieDomain = "$sagu_cookie";
$SessionID    = getmypid();
$_SESSION['SessionAuth'] = "$uid:$pwd";


require_once("common.php");

$conn = new Connection;
$cid = @$conn->Open(true); // @ deixa de fazer o tratamento de erro padrao
@$conn->Close();

// caso tem acesso log o LOGIN
if (!$cid) {
    LogSQL("*** LOGIN RECUSADO (db=$LoginDB,uid=$uid,pwd=) ***");
    include("../msg_login_recusado.php");
    exit;
}

SetCookie("SessionID","$SessionID",time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionUsuario","$uid",time() + 12 * 3600,"/","$CookieDomain",0);

LogSQL("*** LOGIN ACEITO (db=$LoginDB,uid=$uid,pwd=) ***");

Header("Location: $home?login_id=$uid");

?>