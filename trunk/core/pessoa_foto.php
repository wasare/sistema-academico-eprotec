<?php

function pessoa_foto($id) {

    require_once(dirname(__FILE__)."/../config/configuracao.php");

    if(isset($id) && is_numeric($id)) {
        $db = pg_connect(
            ' host     ='.$param_conn['host'].
            ' port     ='.$param_conn['port'].
            ' dbname   ='.$param_conn['database'].
            ' user     ='.$param_conn['user'].
            ' password ='.$param_conn['password']
        );

        $sql = 'SELECT foto
            FROM  pessoas_fotos
            WHERE ref_pessoa = '.$_GET['id'].'; ';

        $rs = pg_query($db, $sql);
        $numrows = pg_numrows($rs);

        if($numrows != 0 ) {

            $image = pg_unescape_bytea(pg_fetch_result($rs, 0, 0));
        }
        else {
            $image = file_get_contents($BASE_DIR.'/public/images/user.gif');
        }

        header("Content-type: image/jpeg");
        echo $image;
        pg_close($db);
    }
    else {
        echo 'Falha ao buscar imagem!';
    }
}

pessoa_foto($_GET['id']);

?>
