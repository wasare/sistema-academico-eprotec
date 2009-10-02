<?php

include_once('../../webdiario.conf.php');


/*
print_r($_GET);

echo '<br />';

print_r($_POST);

echo '<br />';

print_r($_SESSION);
*/


$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];
$ref_prof = $id;

$oferecida = $getofer;
$periodo = $getperiodo;
$disciplina = $getdisciplina;


$vars = "id=".$id."&getperiodo=". $getperiodo."&disc=".@$getdisciplina."&ofer=".@$getofer;


//echo $vars;

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

$sql4 = "SELECT
         a.descricao_extenso, a.carga_horaria, b.nome
         FROM disciplinas a, pessoas b
         WHERE a.id = '$getdisciplina'; ";
/*
if($ref_prof != 0)
{
       $sql4 .=  "AND b.id = $ref_prof;";
}

else
{
      $sql4 .=  ";";
}
*/
/*
// VARS
$sql9 = "SELECT
         a.descricao as cdesc,
         b.descricao_extenso || '  ' || '(' || d.id || ')' as descricao_extenso,
         c.descricao as perdesc,
         d.ref_curso
         FROM
          cursos a,
          disciplinas b,
          periodos c,
          disciplinas_ofer d  
         WHERE d.ref_periodo = '$getperiodo' 
         AND b.id = '$getdisciplina' 
         AND c.id = '$getperiodo'
         AND d.id = '$getofer' 
         AND a.id = d.ref_curso;";

$query9 = pg_exec($dbconnect, $sql9);
         
while($linha9 = pg_fetch_array($query9)) 
{
   $getcurso = $linha9['ref_curso'];
}
*/

$getcurso = getCurso($getperiodo,$getdisciplina,$getofer);

$sql1 ="SELECT id,
               dia,
               conteudo,
	       flag
               FROM
               diario_seq_faltas
               WHERE
               periodo = '$getperiodo' AND
               ref_disciplina_ofer = '$getofer' 
               ORDER BY dia desc ;";
// id_prof = '$id' AND			   
// disciplina = '$getdisciplina' AND

//   pg_close($dbconnect);



/*
$sql1= "SELECT 
  idof, dia, flag, count(T2.dia) AS num
FROM
  (
    SELECT 
      DISTINCT
        dia, ref_disciplina_ofer AS idof, flag
      FROM 
        diario_seq_faltas 
      WHERE 
        id_prof = '$id' AND 
        periodo = '$getperiodo' AND 
        disciplina = '$getdisciplina' AND 
        ref_disciplina_ofer = '$getofer' 
  ) AS T1 
  
LEFT OUTER JOIN
  ( 
    SELECT 
      DISTINCT
        a.data_chamada AS dia, a.ref_disciplina_ofer
      FROM 
        diario_chamadas a, pessoas b, diario_seq_faltas c
      WHERE 
        a.ref_professor = '$id' AND 
        a.ref_periodo = '$getperiodo' AND 
        a.ref_disciplina = '$getdisciplina' AND 
        a.ref_disciplina_ofer = '$getofer' AND 
        c.ref_disciplina_ofer = '$getofer'
  ) AS T2
USING(dia)
GROUP BY idof, dia, flag
ORDER BY dia;";

*/

$sql1 ="SELECT id,
               dia,
               conteudo,
           flag
               FROM
               diario_seq_faltas
               WHERE
               periodo = '$getperiodo' AND
               ref_disciplina_ofer = '$getofer'
               ORDER BY dia DESC ;";
// id_prof = '$id' AND
// disciplina = '$getdisciplina' AND

$qry1 = consulta_sql($sql1);

if(is_string($qry1))
{
    echo $qry1;
    exit;
}

$qry4 = consulta_sql($sql4);

if(is_string($qry4))
{
   echo $qry4;
   exit;
}

$qry5 = consulta_sql($sql5);

if(is_string($qry5))
{
    echo $qry5;
    exit;
}

?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<table width="100%" border="0">
  <tr> 
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Altera&ccedil;&atilde;o de Faltas</strong></font></div></td>
  </tr>
  <tr> 
  <td>
<?php

	echo getHeaderDisc($oferecida);
					   
?>
  </td>
  </tr>
  <tr>
    <td>
	<div align="left"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
	 <h2>Chamadas Realizadas</h2></font>
	</div>
   <div align="left"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<h3><a href="../../consultas/faltas_completo.php?<?php echo $vars;?>">Ver Relat&oacute;rio Completo de Faltas Lan&ccedil;adas</a></h3></font>
		    </div>
	</td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#666666"> 
    <td align="center">
    	<div align="center"><font color="#FFFFFF">&nbsp;</font><b><font color="#FFFFFF">DATA</font></b></div>
    </td>
    <td align="center"><font color="#FFFFFF"><b>AULAS</b></font></td>
    <td width="84%"><font color="#FFFFFF"><b>&nbsp;&nbsp;A&Ccedil;&Atilde;O</b></font></td>
  </tr>
<?php 

$st = '';
	
while( $linha1 = pg_fetch_array($qry1) ) 
{
	// $result2 = br_date($linha1["dia"]);  idof, dia, flag, count(T2.dia) AS num

    $result2 = $linha1["dia"];
    $result = $linha1["conteudo"];
    $result3 = $linha1["id"];
    $result4 = $linha1["flag"];
	
	if ( $st == '#F3F3F3') 
	{
		$st = '#E3E3E3';
	} 
	else 
	{	
		$st ='#F3F3F3';
	} 

	echo '<tr bgcolor="'.$st.'"> <td align="center">'.$result2.'</td> 
				<td align="center"> '.$result4.'</td> ';

	echo '<td> <a href="altera_faltas.php?chamada='.$result4.'&flag='.$result3.'&'.$vars.'">Alterar Faltas</a> </td>';
    
	echo '</tr>';
}
?>


</table>
<br><br>
</body>
</html>
