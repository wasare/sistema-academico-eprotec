<?php 

session_start();

$uid = $_POST['uid'];
$pwd = $_POST['pwd'];

$_SESSION['SessionAuth'] = "$uid:$pwd";

require_once("../../lib/common.php");

$conn = new Connection;
$cid = @$conn->Open(true);
@$conn->Close();

// caso tem acesso log o LOGIN
if (!$cid) {
    LogSQL("*** LOGIN RECUSADO (db=$LoginDB,uid=$uid,pwd=) ***");
    include("../../public/login_erro.php");
    exit;
}

LogSQL("*** LOGIN ACEITO (db=$LoginDB,uid=$uid,pwd=) ***");

Header("Location: ../../app/inicio.php?login_id=$uid");

?>