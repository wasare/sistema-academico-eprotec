<?php

	session_start();

   //$_SESSION['nivel'] != 2;
   
   if( ($_SESSION['cursosc'] == '' || !isset($_SESSION['cursosc']) || empty($_SESSION['cursosc'])) && $_SESSION['nivel'] != 2) {

		setcookie ("us", "0", time( )-9999);
		setcookie ("login", "0", time( )-9999);
		$_SESSION = array();
		session_destroy();

		header("Location: ../");
		exit;
   }
?>
