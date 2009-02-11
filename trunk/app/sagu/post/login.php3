<? require("../../../lib/config.php"); ?>
<?php

/************************* escolher bd a acessar *************************/
//require("../../../lib/properties.php");

//$properties->Set('userid',$uid);
//$properties->Save();
/*************************************************************************/

// load database abstraction layer
$no_login_check = true;                     // ! não fazer um check do login !
require("../../../lib/common.php");

// check form parameters
//CheckFormParameters(array("uid","pwd","home"));

$CookieDomain = "$sagu_cookie";
$SessionAuth  = "$uid:$pwd";
$SessionID    = getmypid();

// fazer uma conexão teste para descobrir se o usuário tem acesso 
$conn = new Connection;
$cid = @$conn->Open(true); // @  deixa de fazer o tratamento de erro padrao
@$conn->Close();

//LogSQL("*** CID=$cid ***");

// caso tem acesso log o LOGIN
if ( ! $cid )
{
  LogSQL("*** LOGIN RECUSADO (db=$LoginDB,uid=$uid,pwd=) ***");
  include("../refused.phtml");
  exit;
}

// gravar autenticação do usuário no cookie SessionAuth
SetCookie("SessionAuth","$SessionAuth",time() + 12 * 3600,"/","$CookieDomain",0);
SetCookie("SessionID","$SessionID",time() + 12 * 3600,"/","$CookieDomain",0);

// SaguAssert(empty($home),"Nome do arquivo inicial nao especificado!");
LogSQL("*** LOGIN ACEITO (db=$LoginDB,uid=$uid,pwd=) ***");

Header("Location: $home?login_id=$uid");
?>
