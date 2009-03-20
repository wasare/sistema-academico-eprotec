<?php

include_once('../../webdiario.conf.php');


if (is_finalizado($_POST['ofer'])){
	header("Location: $erro");
	exit;
}



$CursoTipo = getCursoTipo($getofer);


//while($aluno = list($index,$value) = each($notas))
while($aluno = pg_fetch_array($qrynotas_parciais))
{

  /*
   $flag_extra = 0;
   $flag_diff = 0;
   $flag_media = 0;
   $flag_maior = 0;
   $flag_grava = 0;
  */
   
   //echo $i++;
   $nota = $notas[$aluno['ra_cnec']];
   $nota = str_replace(",",".",$nota);

   $ra_cnec2 = $aluno['ra_cnec'];
   $nota_parcial = $aluno['notaparcial'];
   $nota_atual = $aluno['notaatual'];
   $nota_extra = $aluno['notaextra'];   
   $nome_aluno = $aluno['nome'];


    if(!ereg("[0-9]*\.?[0-9]+$", $nota) || $nota == '') {

		   	$nota = -1;
    }

	 // NOTA EXTRA
    if($nota_extra > -1) { $flag_extra = 1; } else { $flag_extra = 0;} 

    // NOTA DIFERENTE
	if($nota != $nota_atual) { $flag_diff = 1; } else { $flag_diff = 1; }
	
   // CALCULA NOTA TOTAL

   // SE FOR NOTA DE RECUPERACAO / REAVALIACAO CALCULA CONFORME CRITERIOS DE CADA CURSO
   if($nota != -1) {
     /*
		if($CursoTipo == 1 || $CursoTipo == 7 || $CursoTipo == 8 || $CursoTipo == 9) {
				
				$NotaFinal = $nota;			
		}
        else {
		
	    	if($CursoTipo == 2 || $CursoTipo == 4 || $CursoTipo == 6) {
				
					$NotaFinal = (($nota_parcial + $nota) / 2);
			}
        }
     */
      if( $CursoTipo == 2 || $CursoTipo == 4 || $CursoTipo == 5 || $CursoTipo == 6 || $CursoTipo == 10 ) {

      	$NotaFinal = (($nota_parcial + $nota) / 2);
      }
      else
      {
      	$NotaFinal = $nota;
      }

   }
   else {
	   
	    $NotaFinal = $nota_parcial;
   }
    
   if($nota_parcial >= 60) { $flag_media = 1; } else {  $flag_media = 0;}
	
   if($NotaFinal > 100 || $nota > 100 ) { $flag_maior = 1;} else { $flag_maior = 0;}
	  
    $NotaReal = getNumeric2Real($nota);

    // VERIFICA CONDICOES PARA REGISTRAR A NOTA
	// GRAVA AS NOTAS NO BANCO DE DADOS
	// SO ATUALIZA A NOTA A MEDIA FOR MAIOR QUE 60 E
	// SE A NOTA FINAL OU A NOTA EXTRA NÃO FOR MAIOR QUE 100 E
	// SE A NOTA EXTRA ESTIVER SENDO ALTERADA
	if($flag_diff == 1 && $flag_media == 0 && $flag_maior == 0 && $nota != -1) { 
			
		$flag_grava = 1;
	}
    else { $flag_grava = 0;  }

      // GRAVA AS NOTAS NO BANCO DE DADOS
      // SO ATUALIZA A NOTA SE NAO EXISTIR A NOTA EXTRA E A SOMA FOR MENOR OU IGUAL A 100
	  if($flag_grava == 1 || $nota == -1) {

        	$sqlUpdate .= "UPDATE matricula
                             SET 
							nota_final = $NotaFinal 
                          WHERE 
                             ref_pessoa = '$ra_cnec2' AND
                             ref_disciplina_ofer = '$getofer' AND 
                             ref_periodo = '$getperiodo'; ";

			// AND ref_disciplina = '$getdisciplina'		 
         	$sqlUpdate .= "UPDATE 
                     diario_notas 
                  SET 
                     nota = $nota 
                  WHERE 
                     ref_diario_avaliacao = '$codprova' AND 
					 d_ref_disciplina_ofer = '$getofer' AND
					 ra_cnec = '$ra_cnec2';";

					 // d_ref_disciplina_ofer = '$getofer' AND
					 // rel_diario_formulas_grupo = '$grupo' AND
/*			 
         	$qry1 =  consulta_sql($sqlUpdate);

		 	if(is_string($qry1)) {

				echo $qry1;
				exit;
		 	}
		 	else {		
*/
             	if($nota > -1 || $flag_grava == 1 ) {
		      		$msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">Nota <font color=\"#FF0000\"><strong>$NotaReal</strong></font> registrada para o aluno(a) <strong>$nome_aluno</strong>($ra_cnec2)<br></font>";
             	}
//			}
      }
      else { 
	
	      if($flag_diff == 0) {

		    $msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"blue\" ><strong>Nenhuma altera&ccedil;&atilde;o: </strong></font> aluno(a) <strong>$nome_aluno</strong>($ra_cnec2) <br></font>";
		}
		else {
						  		  
		 // A NOTA DO ALUNO ULTRAPASSOU 100 OU Jï¿½ FOI LANï¿½ADA A NOTA EXTRA
		 if($nota != -1) {			 

        	if($flag_maior == 1 ) {

				 $msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"blue\" ><strong>Nota $NotaReal n&atilde;o registrada, poss&iacute;veis causas: </strong></font><font color=\"#FF0000\"><strong>NOTA EXTRA OU M&Eacute;DIA > 100 pontos</strong></font>: aluno(a) <strong>$nome_aluno</strong>($ra_cnec2) <br></font>";
			   
			}
		    else {
				if($flag_media == 1) {

            	$msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"blue\" ><strong>Nota $NotaReal n&atilde;o registrada, poss&iacute;veis causas: </strong></font><font color=\"#FF0000\"><strong>M&Eacute;DIA >= 60 pontos</strong></font>: aluno(a) <strong>$nome_aluno</strong>($ra_cnec2) <br></font>";
			   }
			}
		 }
       }
	}

      // print ($sqlupdatematricula."<BR>");
} // while  0
                 


$sqlUpdate .= 'COMMIT;';

$qry1 =  consulta_sql($sqlUpdate);

if(is_string($qry1)) {

     echo $qry1;
    exit;
}
else {
   echo $msg_registros;
   echo  '<br /><br /><h4><font color="#000000" face="Verdana, Arial, Helvetica, sans-serif"><font color="green"><strong>Informa&ccedil;&otilde;es Alteradas com Sucesso!</strong></font></h4> <br/>';
	
}


// GRAVA LOG                  
$ip = $_SERVER["REMOTE_ADDR"];
$pagina = $_SERVER["PHP_SELF"];
$status = "NOTA REGISTRADA";
$usuario = trim($us);
$sql_store = htmlspecialchars("$usuario");
$Data = date("Y-m-d");
$Hora = date("H:i:s");
$sqllog = "INSERT INTO diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) VALUES('$sql_store','$Data','$Hora','$ip','$pagina','$status','NA')";

$res = consulta_sql($sqllog);

if(is_string($res))
{
	echo $res;
	exit;
}

$vars = "id=".$id."&getperiodo=". $getperiodo."&disc=".@$getdisciplina."&ofer=".@$getofer;
?>

<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<p>&nbsp;</p>
            <center>
		<a href="lancanotas1.php?<?php echo $vars;?>" target="_self">LANCAR NOTAS</a> | <a href="../../prin.php?y=2007" target="_self">HOME</a>
            </center>
