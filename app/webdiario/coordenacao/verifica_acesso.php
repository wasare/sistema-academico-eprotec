<?php

	session_start();

   if($_SESSION['cursosc'] == '' || !isset($_SESSION['cursosc']) || empty($_SESSION['cursosc']) ) {
   
		setcookie ("us", "0", time( )-9999);
		setcookie ("login", "0", time( )-9999);
		$_SESSION = array();
		session_destroy();

		header("Location: ../");
		exit;
   }
?>
