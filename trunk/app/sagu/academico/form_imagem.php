<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

require("../../../lib/config.php"); 
require("../../../lib/common.php"); 


require_once('bitmap.inc.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

ini_set("memory_limit","25M");


// the upload function
//function upload(){
// check if a file was submitted
if(!isset($_FILES['imgfile'])) {
	        echo '<h3>Por favor, selecione a foto e complete o formul&aacute;rio</h3></p>';
}
else {

	$msg_error = '';
	// VALIDA CAMPOS
	if(empty($_POST['usuario']) || !is_string($_POST['usuario'])) {

		$msg_error .= 'Usu&aacute;rio do banco inv&aacute;lido!<br />';
	}
	
	if(empty($_POST['senha'])) {

	   $msg_error .= 'Senha do banco inv&aacute;lida!<br />';
	}


	if(empty($_POST['pessoa']) || !is_numeric($_POST['pessoa'])) {

        $msg_error .= 'Registro da pessoa inv&aacute;lido!<br />';
    }
					
	$err_num = strlen($msg_error);



    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $pessoa = $_POST['pessoa'];

    $db = pg_connect("host=localhost port=5432 dbname=sagu user=$usuario password=$senha");


    if(!$db) {

	   echo '<script language=javascript>
               window.alert("Falha ao conectar a base de dados !");
               javascript:window.history.back(1);
               </script>';
               break;	       
    }
	

	// VALIDA COMPOS
 
    if(is_uploaded_file($_FILES['imgfile']['tmp_name']) && $err_num == 0) {

		$maxsize = $_POST['MAX_FILE_SIZE'];

		$tmpfilesize = $_FILES['imgfile']['size'];
        $tmpfilename = $_FILES['imgfile']['tmp_name'];
        $tmpfiletype = $_FILES['imgfile']['type'];
 
        // check the file is less than the maximum file size
        if($tmpfilesize < $maxsize) {
        // prepare the image for insertion

			//echo    $tmpfiletype;

            $file_type = exif_imagetype($tmpfilename);
            /*
                1   IMAGETYPE_GIF
                2   IMAGETYPE_JPEG
                3   IMAGETYPE_PNG
                6   IMAGETYPE_BMP
            */

                // create image from uploaded image
                switch ($file_type) {
                    case IMAGETYPE_JPEG:
                        $img = imagecreatefromjpeg($tmpfilename);
                        break;
                    case IMAGETYPE_GIF:
                        $img = imagecreatefromgif($tmpfilename);
                        break;
                    case IMAGETYPE_PNG:
                        $img = imagecreatefrompng($tmpfilename);
                        break;
                    case IMAGETYPE_BMP:
                        $img = imagecreatefrombmp($tmpfilename);
                        break;
                }

                //resize image
                $imginfo = getimagesize($tmpfilename);
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

/*                //write to db
				$usuario = $_POST['usuario'];
				$senha = $_POST['senha'];
				$pessoa = $_POST['pessoa'];
				
                $db = pg_connect("host=localhost port=5432 dbname=sagu user=$usuario password=$senha");


				if(!$db)
*/
					
				
				$select = 'SELECT * FROM pessoas_fotos WHERE ref_pessoa = '.$pessoa.';';

				$rs = pg_query($select);

				if(pg_num_rows($rs) != 0 && $_POST['troca'] == 1) {

					$sql = 'UPDATE pessoas_fotos SET foto = \''. $imgdata .'\' WHERE ref_pessoa = '. $pessoa .';';
					
				}
				else {
					$sql = "INSERT INTO pessoas_fotos (ref_pessoa, foto) VALUES ($pessoa, '" . $imgdata . "');";
				}

				
                pg_exec($db, $sql);

				//echo $sql;

				
				if(!$db) { 
					echo '<h4><font color="red">Falha ao salvar o arquivo!</font></h4>';
				} else {
					echo '<p><font color="green" size="3">Imagem carregada com sucesso!</font></p>';					}

				pg_close($db); 
        } 
		else {
		echo '<h4><font color="red">Falha ao carregar o arquivo!</font></h4><br />';
         echo    '<div>O arquivo excedeu o limite máximo de tamanh d '.$maxsize.'!</div>
          <div>O arquivo '.$_FILES['imgfile']['name'].' possui '.$_FILES['imgfile']['size'].' bytes</div>
          <hr />';
		}
		
	}
    else {
         // if the file is not less than the maximum allowed, print an error
		 	echo '<h4><font color="red">Falha ao carregar o arquivo!</font></h4>';
            echo '<h5><font color="red">'.$msg_error.'</font></h5>';
         }
}


/*

// check if a file was submitted
if(!isset($_FILES['imgfile'])) {
        echo '<h3>Por favor, selecione a foto e complete o formul&aacute;rio</h3></p>';
}
else {
    try {
		   upload();
            // give praise and thanks to the php gods
			echo '<p><font color="green" size="3">Imagem carregada com sucesso!</font></p>';
        }
        catch(Exception $e) {
            echo $e->getMessage();
			echo '<h4><font color="red">Falha ao carregar o arquivo!</font></h4>';

			echo '<br /><br />'.$msg;
        }
    }*/
?>
 
<html>
    <head><title>Cadastro de Fotos no SAGU</title></head>
    <body>
 
        <form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
            
		    Arquivo:
			<input name="imgfile" type="file" /> (formatos: jpeg, png, gif ou bmp)

			<br /><br />

			Usu&aacute;rio do Banco:
			<input name="usuario" value="" type="text" size="15" />

			<br /><br />
			Senha do Banco:
			
			<input name="senha" value="" type="password" size="15"/>

			<br /><br />
			Registro da Pessoa:
			<input name="pessoa" value=""  type="text" size="5" /> 
			<input name="troca" value="1"  type="checkbox" checked="checked" /> Substituir a foto atual (caso exista)?
			

			<br /><br />	
						
            <input type="submit" value="Enviar" />
        </form>

		<!--<img title="IMAGEM 1" src="view.php?image_id=1" alt="IMAGEM 1" border="0">-->

				
		<?php
			
			if(@isset($_FILES['imgfile']) && @$err_num == 0) {
			
			  echo '<h4>&Uacute;ltima imagem carregada:</h4>';

              echo '<img title="'. $_POST["pessoa"].'" src="foto.php?id='. $_POST["pessoa"].'" alt="'. $_POST["pessoa"].'" border="1" width="120" />';
            }
			
		?>

    </body>
</html>