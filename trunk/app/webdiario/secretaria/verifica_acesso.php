<?php

	session_start();

   if($_SESSION['nivel'] != 2) {
   
		setcookie ("us", "0", time( )-9999);
		setcookie ("login", "0", time( )-9999);
		$_SESSION = array();
		session_destroy();

		header("Location: ../");
		exit;
   }
?>
