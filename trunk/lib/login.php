<?php 

require_once("config.php"); 

// load database abstraction layer
$no_login_check = true; // ! não fazer um check do login !
require("common.php");

$CookieDomain = "$sagu_cookie";
$SessionAuth  = "$uid:$pwd";
$SessionID    = getmypid();

$conn = new Connection;
$cid = @$conn->Open(true); // @  deixa de fazer o tratamento de erro padrao
@$conn->Close();

//LogSQL("*** CID=$cid ***");

// caso tem acesso log o LOGIN
if ( ! $cid )
{
  LogSQL("*** LOGIN RECUSADO (db=$LoginDB,uid=$uid,pwd=) ***");
  include("../msg_login_recusado.php");
  exit;
}

// gravar autenticação do usuário no cookie SessionAuth
SetCookie("SessionAuth","$SessionAuth",time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionID","$SessionID",time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionUsuario","$uid",time() + 12 * 3600,"/","$CookieDomain",0);

// SaguAssert(empty($home),"Nome do arquivo inicial nao especificado!");
LogSQL("*** LOGIN ACEITO (db=$LoginDB,uid=$uid,pwd=) ***");

Header("Location: $home?login_id=$uid");

?>
