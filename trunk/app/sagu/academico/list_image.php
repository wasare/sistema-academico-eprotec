<?php
    $d = dir('./alunos-jpg');

    while(false !== ($e = $d->read())){
       /*
        if(strpos($e,'.png')){
            $r = imagecreatefrompng('./'.$e);
            imagejpeg($r,str_replace('.png','.jpg',$e),75);
        }*/
        $t = explode("-", $e);

		echo $e.'<br />';

		echo $t[0].'<br />';
    }
?>

