<?php

include_once('../conf/webdiario.conf.php');

$getdisciplina = $_GET['disc'];
$getofer = $_GET['ofer'];
$getperiodo = $_SESSION['periodo'];
$id = $_SESSION['id'];

if(isset($_SESSION['select_prof']) && is_numeric($_SESSION['select_prof']) ) {

     $id = $_SESSION['select_prof'];
}


$ref_prof = $id;

//conteudoaula.php?id=2545&getperiodo=0602&disc=101015&ofer=1317


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
/*
$sql4 = "SELECT
         a.descricao_extenso, a.carga_horaria, b.nome_completo
	          FROM disciplinas a, diario_usuarios b
		           WHERE a.id = '$getdisciplina' ";

if($ref_prof != 0)
{
    $sql4 .=  "AND id_nome = $ref_prof";
}
else
{
    $sql4 .=  "AND login = '$us'";
}
		
*/

$sql4 = "SELECT
         a.descricao_extenso, a.carga_horaria, b.nome
         FROM disciplinas a, pessoas b
         WHERE a.id = '$getdisciplina' ";

if($ref_prof != 0)
{
       $sql4 .=  "AND b.id = $ref_prof;";
}
else
{
      $sql4 .=  ";";
}

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
         WHERE d.ref_periodo = '$getperiodo' AND 
		 c.id = '$getperiodo' AND 
		 d.id = '$getofer'  AND 
		 a.id = d.ref_curso;";

// AND b.id = '$getdisciplina'
		 
$query9 = pg_exec($dbconnect, $sql9);
         
while($linha9 = pg_fetch_array($query9)) 
{
   $getcurso = $linha9['ref_curso'];
}

$sql1 ="SELECT id,
               dia,
               conteudo,
	       flag
               FROM
               diario_seq_faltas
               WHERE
               periodo = '$getperiodo' AND
               ref_disciplina_ofer = '$getofer' 
               ORDER BY dia desc;";

// id_prof = '$id' AND
// disciplina = '$getdisciplina' AND
//   pg_close($dbconnect);

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
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">

<body>
<table width="100%" border="0">
  <tr> 
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Conte&uacute;do de Aula</strong></font></div></td>
  </tr>
  <tr> 
  <td>
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


while($row4 = pg_fetch_array($qry4))
{
    $dis = $row4['descricao_extenso'];
    $prof = $row4['nome'];
    $cargap = $row4['carga_horaria'];
    break;
    print("Disciplina: <b>$dis ($ofcod)</b><br>");
    print("Professor(a): <b>$prof</b><br><br>");
}


print("Curso: <b>$classe</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />");
print("Disciplina: <b>$dis ($ofcod)</b><br>");
print("Per&iacute;odo: <b>$periodo</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />");
print("Professor(a): <b>$prof</b><br><br>");
*/
echo getHeaderDisc($getofer);
						   
?>
  </td>
  </tr>
  <tr>
    <td><div align="left"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>*Para alterar o conte&uacute;do de aula clique no dia desejado !</strong></font></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#666666"> 
    <td align="center">
    	<div align="center"><font color="#FFFFFF">&nbsp;</font><b><font color="#FFFFFF">DATA</font></b></div>
    </td>
    <td align="center"><font color="#FFFFFF"><b>AULAS</b></font></td>
    <td width="84%"><font color="#FFFFFF"><b>CONTE&Uacute;DO DE AULA</b></font></td>
  </tr>
<?php 

$st = '';
	
while( $linha1 = pg_fetch_array($qry1) ) 
{
	// $result2 = br_date($linha1["dia"]);
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

	print ('<tr bgcolor="'.$st.'">
                <td align="center"><a href="../movimentos/altera_conteudo_aula.php?flag='.$result3.'&'.$vars.'">'.$result2.'</a></td>
				<td align="center">'.$result4.'</td> 
				<td>'.$result.'</td>
			</tr>');
	}
?>

</table>
<br><br>
<input type="button" value="Imprimir" onClick="window.print()">
</body>
</html>
