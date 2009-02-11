<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php


require("../../../lib/config.php"); 
require("../../../lib/common.php"); 


require_once('bitmap.inc.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);



//echo  exif_imagetype('./fotos-tmp/1238-soraya.bmp');

if(isset($_POST['enviado']) && $_POST['enviado'] == 1) {


 //write to db
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$msg_error = '';
    // VALIDA CAMPOS
if(empty($usuario) || !is_string($usuario)) {

        $msg_error .= 'Usu&aacute;rio do banco inv&aacute;lido!<br />';
}

if(empty($senha)) {

       $msg_error .= 'Senha do banco inv&aacute;lida!<br />';
}


$err_num = strlen($msg_error);


if($err_num != 0) {

	echo '<h4><font color="red">Falha ao carregar o arquivo!</font></h4>';
    echo '<h5><font color="red">'.$msg_error.'</font></h5>';
	exit;
}


set_time_limit(0);

$d = dir('./fotos-tmp');

while(false !== ($e = $d->read())){
       /*
        if(strpos($e,'.png')){
            $r = imagecreatefrompng('./'.$e);
            imagejpeg($r,str_replace('.png','.jpg',$e),75);
        }*/
        $t = explode("-", $e);

					
 
       if(is_file('./fotos-tmp/'.$e)) {

           $pessoa = $t[0];


		   $file_type = exif_imagetype('./fotos-tmp/'.$e);
			/*
				1	IMAGETYPE_GIF
				2	IMAGETYPE_JPEG
				3	IMAGETYPE_PNG
				6	IMAGETYPE_BMP
			*/

                // create image from uploaded image
                switch ($file_type) {
                    case IMAGETYPE_JPEG:
                        $img = @imagecreatefromjpeg('./fotos-tmp/'.$e);
                        break;
                    case IMAGETYPE_GIF:
                        $img = @imagecreatefromgif('./fotos-tmp/'.$e);
                        break;
                    case IMAGETYPE_PNG:
                        $img = @imagecreatefrompng('./fotos-tmp/'.$e);
                        break;
                    case IMAGETYPE_BMP:
                        $img = @imagecreatefrombmp('./fotos-tmp/'.$e);
                        break;
				}
         		
                //resize image
                $imginfo = getimagesize('./fotos-tmp/'.$e);
                $width = $imginfo[0];
                $height = $imginfo[1];
                $maxsize = 600;


                if (($width > $maxsize) || ($height > $maxsize)) {
                    $ratio = max($width, $height) / $maxsize;
                    $newwidth = floor($width / $ratio);
                    $newheight = floor($height / $ratio);
                    $newimg = imagecreatetruecolor($newwidth, $newheight);
                    imagecopyresampled($newimg, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                    $img = $newimg;
                }

                //prepare image for database
                ob_start();
                imagejpeg($img, '', 80);
                $imgdata = pg_escape_bytea(ob_get_contents());
                // pg_unescape_bytea()
                ob_end_clean();

                //write to db
				
                $db = pg_connect("host=localhost port=5432 dbname=sagu user=$usuario password=$senha");

				$select = 'SELECT * FROM pessoas_fotos WHERE ref_pessoa = '.$pessoa.';';

				$rs = pg_query($select);

				if(pg_num_rows($rs) != 0 && $_POST['troca'] == 1) {

					$sql = 'UPDATE pessoas_fotos SET foto = \''. $imgdata .'\' WHERE ref_pessoa = '. $pessoa .';';
					
				}
				else {
					$sql = "INSERT INTO pessoas_fotos (ref_pessoa, foto) VALUES ($pessoa, '" . $imgdata . "');";
				}

				
               pg_exec($db, $sql);

			
		        if($img) {
			        imagedestroy($img);
				    unset($imgdata);
				}	

				if(!$db) { 
					echo '<h4><font color="red">Falha ao salvar o arquivo!</font></h4>';
				} else {
					echo '<p><font color="green" size="3">Imagem carregada com sucesso!</font></p>';				}

				pg_close($db); 

		} // is_file end
		
	} // while end

} // enviado end



?>
 
<html>
    <head><title>Cadastro de Fotos no SAGU</title></head>
    <body>
 
        <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

			<br /><br />


            <input type="hidden" name="enviado" value="1" />

			Usu&aacute;rio do Banco:
			<input name="usuario" value="" type="text" size="15" />

			<br /><br />
			Senha do Banco:
			
			<input name="senha" value="" type="password" size="15"/>

			<br /><br />
			<input name="troca" value="1"  type="checkbox" checked="checked" /> Substituir a foto atual (caso exista)?
			

			<br /><br />	
						
            <input type="submit" value="Enviar" />
        </form>


    </body>
</html>
