<?php

$var = explode(":",$_GET[getdisciplina]);
$getdisciplina = $var[0];
$getofer = $var[1];

function calcula($equation)
{
     //  $equation = preg_replace("/[^0-9+\-.*\/()%]/","",$equation);
       $equation = preg_replace("/[^0-9+\-.*\/()%]/","",$equation);
       $equation = preg_replace("/([+-])([0-9]+)(%)/","*(1\$1.\$2)",$equation);
       // você poderia usar o str_replace nesta linha seguinte
       // se você realmente, realmente quiser um ajuste-fino ajuste esta equação
//       $equation = preg_replace("/([0-9]+)(%)/",".\$1",$equation);
       $equation = preg_replace("/([0-9]*)(%)/",".\$1",$equation);
       if ( $equation == "" ) {
               $return = 0;
       } else {
               eval("\$return=" . $equation . ";");
       }
       return $return;
}

include_once ('../../conf/webdiario.conf.php');
// CONECT NO BANCO

/////////////Seleciona Fórmula

$sqlformula = "SELECT DISTINCT formula FROM diario_formulas WHERE grupo = '$grupo'";
$queryformula = pg_exec($dbconnect, $sqlformula);
while($linhaformula = pg_fetch_array($queryformula)) 	
{
   $formula = $linhaformula["formula"];
}
                $numregistro = pg_num_rows($queryformula);

                $formula123=$formula;
                
                             $vlrprova=1;
                             reset ($notadaprova);
                             while (list($index,$value) = each($notadaprova)) {
                             $vlrnota = $notadaprova[$index];

                $formula123 = str_replace("P".$vlrprova , $vlrnota , $formula123);
                $formula123 = str_replace("," , "." , $formula123);
              //  $nota=str_replace(",",".",$nota);
                $vlrprova++;
                }

                @$resultadototal = substr(calcula($formula123),0,3);

                $resmedias = media($resultadototal);
                
                           
                print ('<div align="center"><strong><font color="#000000" size="3" face="Verdana, Arial, Helvetica, sans-serif"><br /><br />F&Oacute;RMULA '.$formula123.'</font><font face="Verdana, Arial, Helvetica, sans-serif">
  =</font></strong> <strong><font color="#FF0000" size="5" face="Verdana, Arial, Helvetica, sans-serif">'.$resmedias.'</font></strong></div>');
               
               print ('<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<p align="center">C&aacute;lculo com arrendondamento </p> <p align="center">&nbsp;</p> <center>');
  
               if($resmedias > 100)
               {
                  print ('<strong><font color="#FF0000" size="3" face="Verdana, Arial, Helvetica, sans-serif">ATEN&Ccedil;&Atilde;O!!!<br /> Se fosse um lan&ccedil;amento real estaria incorreto pois '. $resmedias.' &eacute; maior que 100!!</font></strong><br /> <br /><br />');
               }



 //fim do while list
        ///// pg_close($dbconnect);
         
         /////GRAVA LOG

         $ip=$_SERVER["REMOTE_ADDR"];
         $pagina=$_SERVER["PHP_SELF"];
         $status="SIMULAÇÃO DE FÓRMULA";
         $usuario = trim($us);
		 $sql_store = htmlspecialchars("$usuario");
		 $Data = date("Y-m-d");
		 $Hora = date("H:i:s");
         $sqllog = "insert into diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) values('$sql_store','$Data','$Hora','$ip','$pagina','$status','NA')";
         pg_exec($dbconnect, $sqllog);
         
?>

<?php
  
   $getdisciplina = "$getdisciplina:$getofer";
   print ('<a href="lanca3.php?id='.$id.'&getcurso='.$getcurso.'&getdisciplina='.$getdisciplina.'&getperiodo='.$getperiodo.'" target="_self">LANCAR NOTAS</a> | <a href="../../prin.php?y=2003" target="_self">HOME</a>'); 

?>
            
</center>

