<?php

//	require("/var/www/sagu/html/conf/config.php3"); 
//	require("/var/www/sagu/html/conf/common.php3"); 

include_once ('../webdiario.conf.php');

// just so we know it is broken
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// some basic sanity checks
if(isset($_GET['p']) && is_numeric($_GET['p'])) {
 
	//connect to the db
    //$db = pg_connect("host=localhost port=5432 dbname= user= password=");

   	
        
        // get the image from the db
        $id_pessoa = $_GET['p'];
        
		$sql = 'SELECT foto  FROM  pessoas_fotos WHERE ref_pessoa = '.$id_pessoa.';';
	
		$rs = consulta_sql($sql);	

        //$rs = pg_query($db, $sql);
		
        $numrows = pg_numrows($rs);

		if($numrows != 0 ) {
		
	        $image = pg_unescape_bytea(pg_fetch_result($rs, 0, 0));
		}
		else
		{
			$image = file_get_contents('fotosagu.jpg');

		}
        
        // set the header for the image
        header("Content-type: image/jpeg");
		

        echo $image;
 
        // close the db link
        //pg_close($db);
    }
    else {
        echo 'Falha ao buscar imagem!';
    }
?>
