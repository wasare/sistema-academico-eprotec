<?php
# write by lucas@cneccapivari.br
# this is a free software, you can modify follow the GNU/GPL

include_once('../conf/webdiario.conf.php');

$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

$ref_prof = $id;

//print_r($_SESSION);
if(isset($_SESSION['select_prof']) && is_numeric($_SESSION['select_prof']) ) {

	$id = $_SESSION['select_prof'];
}



$sql3 = "SELECT DISTINCT dia FROM diario_seq_faltas WHERE periodo = '$getperiodo' AND ref_disciplina_ofer = '$getofer' ORDER BY dia;"; 

// id_prof = '$id'
// AND disciplina = '$getdisciplina'


/*		 
echo $sql3;
exit;
*/         
$sql4 = "SELECT * FROM
(
SELECT DISTINCT 
  b.id, b.nome, a.num_faltas
         FROM matricula a LEFT OUTER JOIN pessoas b ON (a.ref_pessoa = b.id) 
         WHERE
            a.ref_periodo = '$getperiodo' AND
           a.ref_disciplina_ofer = '$getofer'
) AS T1
LEFT OUTER JOIN

(
SELECT DISTINCT
          c.ra_cnec, data_chamada, count(c.ra_cnec) as faltas          FROM diario_chamadas c
         WHERE
            c.ref_periodo = '$getperiodo' AND
           c.ref_disciplina_ofer = '$getofer'
 GROUP BY c.ra_cnec, data_chamada

) AS T2 ON (id = ra_cnec)

ORDER BY lower(to_ascii(nome)), data_chamada;";



$sql4 = "SELECT 
         b.nome, b.ra_cnec, a.ordem_chamada, a.nota_final, a.num_faltas
         FROM matricula a, pessoas b
         WHERE
            a.ref_periodo = '$getperiodo' AND
            a.ref_disciplina_ofer = '$getofer' AND
            a.ref_pessoa = b.id
         ORDER BY lower(to_ascii(nome));" ;

// a.ref_disciplina = '$getdisciplina' AND

$sql5 = "SELECT dia, CASE 
                        WHEN faltas IS NULL THEN '0' 
                        ELSE faltas
                    END AS faltas
FROM
(
SELECT DISTINCT
          c.ra_cnec, data_chamada, count(c.ra_cnec) as faltas          FROM diario_chamadas c
         WHERE
            c.ref_periodo = '$getperiodo' AND
           c.ref_disciplina_ofer = '$getofer' AND
           c.ra_cnec = %s
        GROUP BY c.ra_cnec, data_chamada
) AS T1
FULL OUTER JOIN
(
SELECT DISTINCT dia FROM diario_seq_faltas WHERE periodo = '$getperiodo' AND ref_disciplina_ofer = '$getofer' ORDER BY dia
) AS T2 ON (data_chamada = dia)

ORDER BY dia;";


// id_prof = '$id' AND
/*
$sql5 = " SELECT 
            DISTINCT 
               a.id, a.descricao as descricao, b.id, c.descricao as periodo 
            FROM 
               cursos a, disciplinas_ofer b, periodos c 
            WHERE 
               b.ref_periodo = '$getperiodo' AND 
               c.id = '$getperiodo' AND 
               b.ref_curso = a.id AND 
               b.id = '$getofer';";
         
//echo $sql5;
*/       
$qry3 = consulta_sql($sql3);

if(is_string($qry3))
{
	echo $qry3;
	exit;
} 
else {

	$num_chamadas = pg_numrows($qry3);

	if($num_chamadas == 0) {

		echo '<script language="javascript">window.alert("Nenhuma chamada realizada para este diário!"); javascript:window.history.back(1); </script>';
      exit;
	}
		
}




$qry4 = consulta_sql($sql4);

if(is_string($qry4))
{
   echo $qry4;
   exit;
}


$sql6 = "SELECT
         a.descricao_extenso, a.carga_horaria, b.nome
         FROM disciplinas a, pessoas b
         WHERE a.id = '$getdisciplina'; ";

if($ref_prof != 0)
{
       $sql4 .=  "AND b.id = $ref_prof;";
}

else
{
      $sql4 .=  ";";
}



$qry6 = consulta_sql($sql6);

if(is_string($qry6))
{
    echo $qry6;
    exit;
}


$sqlflag ="SELECT
                  SUM(CAST(flag AS INTEGER)) AS carga
               FROM
                  diario_seq_faltas
               WHERE
                  periodo = '$getperiodo' AND
                  ref_disciplina_ofer = $getofer; ";

// disciplina = $getdisciplina AND

       $qryflag = consulta_sql($sqlflag);

        if(is_string($qryflag))
        {
            echo $qryflag;
            exit;
        }

       $rowflag = pg_fetch_array($qryflag);

       $result = $rowflag['carga'];

       if( $result < 1 )
       {
         $result = 0;
       }


?>

<html>
<head>
<title>faltas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
</head>


    <div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Relat&oacute;rio de Faltas</strong></font></div>

<?php
/*	
while($row5 = pg_fetch_array($qry5)) 
{
   $classe = $row5['descricao'];
   $periodo = $row5['periodo'];
   $ofcod = $row5['id'];
   break;
   print("Curso: <b>$classe</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />");
   print("Per&iacute;odo: <b>$periodo</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
}

*/
while($row4 = pg_fetch_array($qry6)) 
{
	$dis = $row4['descricao_extenso'];
	$prof = $row4['nome'];
	$cargap = $row4['carga_horaria'];
	break;
	print("Disciplina: <b>$dis ($ofcod)</b><br>");
	print("Professor(a): <b>$prof</b><br><br>");
}
	
echo getHeaderDisc($getofer);


$FaltaMax = $cargap * 0.25;
//echo $FaltaMax;

?>
</font>
<table >
	<tr bgcolor="#cccccc">
		<td width="5%">N&ordm;</td>
		<td width="10%">Matr&iacute;cula</td>
		<td width="60%">Nome</td>
        <?php

				while($d = pg_fetch_array($qry3)) {
   					
					echo '<td align="center">'.$d['dia'].'</td>';								
				}

		?>
		<td align="center">Total</td>
	</tr>
<?php


$i = 0;
$No = 1;

$r1 = '#F3F3F3';
$r2 = '#E3E3E3';

											
while($row3=pg_fetch_array($qry4)) {
	
   $nome_f = $row3["nome"];
   $ra = $row3["ra_cnec"];
   $racnec = str_pad($ra, 5, "0", STR_PAD_LEFT) ;
   $falta_total = $row3['num_faltas'];

   
   if($falta_total > $FaltaMax) { 
	   
	   $falta_total = "<font size=\"3\" color=\"red\"><b>$falta_total</b></font>";
   }
   else {
	   if($falta_total > 0) {

		          $falta_total = "<font size=\"3\"><b>$falta_total</b></font>";
	   }
   }

   if ( ($i % 2) == 0) {
	   
      $rcolor = $r1;
   }
   else {

      $rcolor = $r2;
   }
   
   print("<tr bgcolor=\"$rcolor\">\n"); 
   print ("<td align=\"center\">".$No++."</td>\n ");
   print(" <td width=\"10%\" align=\"center\">$racnec</td>\n <td width=\"60%\">$nome_f</td>\n "); 
   

//   -- RECUPERA AS NOTAS PARCIAIS POR ALUNO

	$qryfaltas = consulta_sql(sprintf($sql5,$ra));

	if(is_string($qryfaltas)) {
		
		echo $qryfaltas;
		exit;
	}


//	print_r(pg_fetch_array($qryfaltas));


	while($row = pg_fetch_array($qryfaltas)) {

		/*print_r($row);
		echo '<br /><br />';
		*/
		$N = $row['faltas'];

		if ($N != 0 ) {  $N = '<font size=3><b>'.$N.'</b></font>'; }

		print ("<td align=\"center\">$N</td>\n ");
	}


   //if ($falta_total != 0 ) {  $falta_total = '<font size=3><b>'.$falta_total.'</b></font>'; }
   
   print ("<td align=\"center\">$falta_total</td>\n ");
   
   print("</tr>\n ");
   
   $i++;
}

?>


</table>
<hr width="60%" size="1" align="left" color="#FFFFFF">

<?php
	
print("Aulas dadas: <b>$result</b>&nbsp;&nbsp;&nbsp;");
print("Aulas previstas na estrututa curricular: <b>$cargap</b><br>");


?>
<br><br>
<input type="button" value="Imprimir" onClick="window.print()">
&nbsp;&nbsp;&nbsp;
<input type="button" name="cancelar" value="Voltar" onclick="javascript:window.history.back(1);" />

</body>
</html>
