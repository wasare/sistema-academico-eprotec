<?php

require_once('../../app/setup.php');

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$IEnome?> - web di&aacute;rio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

</head>

<body>
<div align="left">
<br />
	<font style="color: #FFFFCC; size: 1.3em; text-align: left;"> 
    <a href="<?=$BASE_URL .'app/web_diario/docs/gs851w32.exe'?>">1 - GhostScript</a>
	<br /><br />
    <a href="<?=$BASE_URL .'app/web_diario/docs/gsv48w32.exe'?>">2 - GhostView</a>
	</font>
</div>
</body>
</html>
