<?php

/** 
 * Verificacao de autenticacao de usuario 
 */
require_once(dirname(__FILE__).'/../../configs/config.php');


$SessionAuth = $_SESSION['SessionAuth'];

if(empty($SessionAuth)) {
	Header("Location: $LoginURL");
    exit;
}

?>