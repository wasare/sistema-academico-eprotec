<?php
include_once('../../webdiario.conf.php');

if (is_finalizado($_POST['ofer'])){
    header("Location: $erro");
    exit;
}


//print_r($_POST); die

$notas = $_POST['notas'];
$ra_cnec = $_POST['ra_cnec'];
//$grupo = $_POST['grupo'];
//$grupo_novo = $_POST['grupo_novo'];
$codprova = $_POST['codprova'];
$prova = $codprova;

$getdisciplina = $_POST['disc'];
$getofer = $_POST['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

$getcurso = $_POST['curso'];

$msg_registros = '';

$sqlUpdate = 'BEGIN;';

function calcula($equation)
{
   $equation = preg_replace("/[^0-9+\-.*\/()%]/","",$equation);
   $equation = preg_replace("/([+-])([0-9]+)(%)/","*(1\$1.\$2)",$equation);
   // voce poderia usar o str_replace nesta linha seguinte
   // se voce realmente, realmente quiser um ajuste-fino ajuste esta equacao
   $equation = preg_replace("/([0-9]+)(%)/",".\$1",$equation);
   if($equation == "" ) 
   {
      $return = 0;
   } 
   else 
   {
      eval("\$return=" . $equation . ";");
   }
   return $return;
   
}

?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
</head>

<body>
<table width="90%" height="73" border="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvet
ica, sans-serif"><strong>Lan&ccedil;amento / Altera&ccedil;&atilde;o da <?php if($prova == 7) { echo '<font color="blue"> Nota Extra</font>.'; } else { echo 'Nota <font color="blue"> P'.$prova.'</font>.';} ?></strong></font></div>
</td>
  </tr>
</table>

<?php

echo getHeaderDisc($getofer);

echo '<br />';

reset($notas);
reset($ra_cnec);

$sql12 = 'SELECT * FROM (';
$sql12 .= "SELECT   DISTINCT
                    matricula.ordem_chamada, pessoas.nome, pessoas.id, SUM(d.nota) AS notaparcial
            FROM
                matricula
            INNER JOIN pessoas ON (matricula.ref_pessoa = pessoas.id)
            INNER JOIN diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                            d.ra_cnec = matricula.ref_pessoa AND
                                            d.id_ref_periodos = '$getperiodo' AND
											d.d_ref_disciplina_ofer = '$getofer' AND
                                            d.ref_diario_avaliacao <> '$prova'  AND
                                            d.ref_diario_avaliacao <> '7')
            WHERE
                (matricula.ref_disciplina_ofer = '$getofer') AND
                (matricula.dt_cancelamento is null) AND
				(matricula.ref_motivo_matricula = 0)
			GROUP BY
					 matricula.ordem_chamada, pessoas.nome, pessoas.id, pessoas.ra_cnec
            ORDER BY pessoas.nome ";

$sql12 .= ') AS T1 INNER JOIN (';

//AND d.rel_diario_formulas_grupo = '$grupo'
// d.rel_diario_formulas_grupo = '$grupo' AND

$sql12 .= "SELECT DISTINCT
               pessoas.ra_cnec, d.nota AS notaatual
            FROM
               matricula INNER JOIN
               pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN
               diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                 d.ra_cnec = matricula.ref_pessoa AND d.id_ref_periodos = '$getperiodo' AND d.d_ref_disciplina_ofer = '$getofer' AND d.ref_diario_avaliacao = '$prova')
            WHERE
               (matricula.ref_disciplina_ofer = '$getofer') AND (matricula.dt_cancelamento is null) AND (matricula.ref_motivo_matricula = 0)";


// AND d.rel_diario_formulas_grupo = '$grupo'

$sql12 .= ') AS T2 ON (T2.ra_cnec = T1.id) INNER JOIN (';


$sql12 .= "SELECT DISTINCT
               pessoas.ra_cnec AS ref_pessoa, d.nota AS notaextra
            FROM
               matricula INNER JOIN
               pessoas ON (matricula.ref_pessoa = pessoas.id) INNER JOIN
               diario_notas d ON (id_ref_pessoas = pessoas.id AND
                                 d.ra_cnec = matricula.ref_pessoa AND d.id_ref_periodos = '$getperiodo' AND d.d_ref_disciplina_ofer = '$getofer' AND d.ref_diario_avaliacao = '7')
            WHERE
               (matricula.ref_disciplina_ofer = '$getofer') AND (matricula.dt_cancelamento is null) AND (matricula.ref_motivo_matricula = 0)";
// AND d.rel_diario_formulas_grupo = '$grupo'
$sql12 .= ') AS T3 ON (T3.ref_pessoa = T2.ra_cnec) ORDER BY to_ascii(nome);';



//echo $sql12;die;

$qrynotas_parciais = consulta_sql($sql12);

// WHILE 0
if(is_string($qrynotas_parciais))
{
   echo $qrynotas_parciais;
   exit;
}

if($prova == 7) {

	include_once('grava_nota_extra.php');
	exit;
}


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

        	$nota = 0;
    }

	 // NOTA EXTRA
    if($nota_extra > -1) { $flag_extra = 1; } else { $flag_extra = 0; }

    // NOTA DIFERENTE
	if($nota != $nota_atual) { $flag_diff = 1; } else { $flag_diff = 0; }

   	// CALCULA NOTA TOTAL
	//$NotaFinal = ($nota_parcial + $nota);


   if($flag_diff == 1) { 
	   //1 && $prova != 7) {

        $NotaFinal = ($nota_parcial + $nota);
   }
   else {
		
		$NotaFinal = ($nota_parcial + $nota_atual);
   }

   if($NotaFinal > 100) { $flag_maior = 1;} else { $flag_maior = 0; }
	  
   $NotaReal = getNumeric2Real($nota);

    // VERIFICA CONDICOES PARA REGISTRAR A NOTA
/*	if($prova == 6) {
		
		if($flag_extra == 0 && $flag_maior == 0) {
				
			$flag_grava = 1;
		}
	}
	else {
*/
		// SE NOTA EXTRA NÃO FOI LANCADA 
		// E A NOTA FOR DIFERENTE DA ANTERIOR E NÃO FOR MAIOR QUE 100 GRAVA
		if($flag_extra == 0 && $flag_diff == 1 && $flag_maior == 0) { 
			
				$flag_grava = 1; 
		}
		else {  $flag_grava = 0; }
		
//	}

   

      // GRAVA AS NOTAS NO BANCO DE DADOS
      // SO ATUALIZA A NOTA SE NAO EXISTIR A NOTA EXTRA E A SOMA FOR MENOR OU IGUAL A 100
	  if($flag_grava == 1) {

        	$sqlUpdate .= "UPDATE matricula
                             SET 
							nota_final = $NotaFinal 
                          WHERE 
                             ref_pessoa = '$ra_cnec2' AND
                             ref_disciplina_ofer = '$getofer' AND 
                             ref_periodo = '$getperiodo' AND
                             ref_motivo_matricula = 0; ";
		// AND ref_disciplina = '$getdisciplina' 
         	$sqlUpdate .= "UPDATE 
                     diario_notas 
                  SET 
                     nota = $nota 
                  WHERE 
				     d_ref_disciplina_ofer = '$getofer' AND
                     ref_diario_avaliacao = '$codprova' AND 
                     ra_cnec = '$ra_cnec2';";
				// rel_diario_formulas_grupo = '$grupo' AND
		      
					 
					 
					 
			$msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">Nota <font color=\"#FF0000\"><strong>$NotaReal</strong></font> registrada para o aluno(a) <strong>$nome_aluno</strong>($ra_cnec2)<br></font>";
      }
      else { 
		

		    if($flag_extra == 1) {

					$msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"blue\" ><strong>Nota $NotaReal n&atilde;o registrada, motivo: </strong></font><font color=\"#FF0000\"><strong>NOTA EXTRA J&Aacute; LAN&Ccedil;ADA!</strong></font>: aluno(a) <strong>$nome_aluno</strong>($ra_cnec2) <br></font>";	
			}
			else {
				if($flag_maior == 1) {
				
					$msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"blue\" ><strong>Nota $NotaReal n&atilde;o registrada, causa: </strong></font><font color=\"#FF0000\"><strong>M&Eacute;DIA > 100 pontos</strong></font>: aluno(a) <strong>$nome_aluno</strong>($ra_cnec2) <br></font>";				
				}
				else {
                
 		        	if($flag_diff == 0) {
                  		$msg_registros .= "<font color=\"#000000\" size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\"><font color=\"blue\" ><strong>Nota $NotaReal Mantida</strong></font>: aluno(a) <strong>$nome_aluno</strong>($ra_cnec2) <br></font>";
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


/* INICIO GRAVAR NOTA DISTRIBUIDA*/

$nota_distribuida = str_replace(",",".",$_POST["valor_avaliacao"]);

if (empty($nota_distribuida) OR !is_numeric($nota_distribuida))
	$nota_distribuida = 0;
   

$sqlSelecaoTotalNotas = "
SELECT 
sum(nota_distribuida) as nota_distribuida 
FROM 
diario_formulas 
WHERE grupo ILIKE '%-$getofer'  AND 
prova <> '$prova'";

//echo $sqlSelecaoTotalNotas;

$qrySelecaoTotalNotas =  consulta_sql($sqlSelecaoTotalNotas);

if(is_string($qrySelecaoTotalNotas)) {
	echo $qrySelecaoTotalNotas;
    exit;
}
else{
   	
   	$val1 = pg_fetch_row($qrySelecaoTotalNotas);
	
	$total_nota_distribuida = $val1[0] + $nota_distribuida;
	
	//echo $total_nota_distribuida;

    $msg_nota_distribuida = '';	

   	if($total_nota_distribuida > 100)
   	{
   		$msg_nota_distribuida = '<font color="red"><b>Erro: Não foi possível gravar, resultado do somatório das notas superior a 100!</b></font>';
   	}
   	else
   	{
        // somente grava nota distribuida para valores válidos
		if (!empty($nota_distribuida) AND is_numeric($nota_distribuida))
		{

			$sqlAtualizaNotaDistribuida = "
					UPDATE diario_formulas SET nota_distribuida = $nota_distribuida 
					WHERE grupo ILIKE '%-$getofer' AND prova = '$prova' ";
		
			$qryAtualizaNotaDistribuida =  consulta_sql($sqlAtualizaNotaDistribuida);
		
			if(is_string($qrySelecaoTotalNotas)) {
				echo $qry1;
				exit;
			}
			else 
			{
				$msg_nota_distribuida = "<font color=\"green\" ><b>Nota distribuida alteradas com sucesso!</b></font><br>
					Valor da Nota Distribuida: ". getNumeric2Real($nota_distribuida) ." pontos";
			}
		}
        //^ somente grava nota distribuida para valores válidos ^ //
	}
	
	echo $msg_nota_distribuida;
	
}

/* FIM GRAVAR NOTA DISTRIBUIDA*/
?>
<p>&nbsp;</p>
<center>
<a href="lancanotas1.php?<?php echo $vars;?>" target="_self">LANCAR NOTAS</a> | <a href="../../prin.php?y=2007" target="_self">HOME</a>
</center>
</body>
</html>
