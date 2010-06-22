<?php

include_once('../../webdiario.conf.php');


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

$grupo = ($id . "-" . $getperiodo . "-" . $getdisciplina . "-" . $getofer);
$grupo_novo = ("%-" . $getperiodo . "-%-" . $getofer);

$curso = getCurso($getperiodo,$getdisciplina,$getofer);


if(!is_inicializado($getperiodo,$getofer)) 
{
    if (inicializaDiario($getdisciplina,$getofer,$getperiodo,$id))
    {
        echo '<script type="text/javascript">  window.alert("Diário iniciado com sucesso!"); </script>';
       	echo '<html> <body> <script type="text/javascript"> self.location.href = "lancanotas2.php?id=' . $id. '&disc=' . $getdisciplina . '&ofer=' . $getofer. '&getperiodo=' . $getperiodo. '&curso=' . $curso. '" </script> </body> </html>';
    }
    else
    {
        // FIXME: informar ao administrador/desenvolvedor quando ocorrer erro
        echo '<script language=javascript>  window.alert("Falha ao inicializar o diário!!!!!!!"); </script>';
        exit;   
    }
} 
else 
{ 
    echo '<html> <body> <script type="text/javascript"> self.location.href = "lancanotas2.php?id=' . $id. '&disc=' . $getdisciplina . '&ofer=' . $getofer. '&getperiodo=' . $getperiodo. '&curso=' . $curso. '" </script> </body> </html>';
}

?>
