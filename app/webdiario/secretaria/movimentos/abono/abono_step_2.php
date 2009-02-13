<?php
include_once('../../conf/webdiario.conf.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="471" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Abono 
        de Faltas</strong></font></div></td>
  </tr>
  <tr> 
    <td height="51">
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Processo 
        de abono de Faltas</strong></font></p>
      <div align="center"></div></td>
  </tr>
</table>
</body>
</html>
<?php

$sqlprof = "SELECT DISTINCT
      id_nome,
      nome_completo
      FROM
      diario_usuarios
      order by nome_completo";
$queryprof = pg_exec($dbconnect, $sqlprof);


/* Seleciona o Período */
if ($_GET["idprofessor"] != "") 
{
/*
      $sql1 = "SELECT DISTINCT
      a.login,
      a.id_nome,
      b.ref_professor,
      b.ref_disciplina_ofer,
      c.id,
      c.ref_periodo,
      d.id,
      d.descricao
      FROM
      diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE
      a.id_nome = '$idprofessor'
      AND
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id
      AND
      c.ref_periodo = d.id";
*/

$sql1 = "SELECT DISTINCT
      d.id,
      d.descricao
      FROM
      diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE
      a.id_nome = '$idprofessor'  AND
      a.id_nome = b.ref_professor AND
      b.ref_disciplina_ofer =  c.id AND
      c.is_cancelada = 0 AND
      c.ref_periodo = d.id";

// echo $sql1;

$query1 = pg_exec($dbconnect, $sql1);
     
}

/* Seleciona os Cursos Referente ao Período */
if ($_GET["sendperiodo"] != "") 
{
	$sql2 = "SELECT DISTINCT
        a.id,
        a.descricao
        FROM
        cursos a,
        disciplinas_ofer b,
        disciplinas_ofer_compl c,
        disciplinas_ofer_prof d,
        periodos e
        WHERE
        d.ref_professor = '$idprofessor' AND
        b.ref_periodo = '$sendperiodo' AND
        d.ref_disciplina_ofer = b.id AND
        d.ref_disciplina_compl = c.id AND
        b.ref_curso = a.id ";
//echo $sql2;
       $query2 = pg_exec($dbconnect, $sql2);
}


if($_GET["sendcurso"] != "") 
{
		$sql3 = "SELECT DISTINCT
                 f.id,
                 f.descricao_disciplina,
                 f.descricao_extenso,
                 b.id as idof
                 FROM
                 disciplinas_ofer b,
                 disciplinas_ofer_compl c,
                 disciplinas_ofer_prof d,
                 disciplinas f
                 WHERE
                 d.ref_professor = '$idprofessor' AND
                 b.ref_periodo = '$sendperiodo' AND
                 d.ref_disciplina_compl = c.id AND
                 c.ref_disciplina_ofer = b.id AND
                 b.ref_disciplina = f.id
                 ";
		$query3 = pg_exec($dbconnect, $sql3);
}
	
if ($_GET["senddisciplina"] != "") 
{
   $sql4 = "SELECT DISTINCT
                 id_prof,
                 periodo,
                 curso,
                 disciplina,
                 dia
                 FROM
                 diario_seq_faltas
                 WHERE
                 id_prof = '$idprofessor' AND
                 periodo = '$sendperiodo' AND
                 curso = '$sendcurso' AND
                 disciplina = '$senddisciplina'
                 ";
//echo $sql4;
   $query4 = pg_exec($dbconnect, $sql4);
}
?>

<html>
<head>
<title>teste</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<body bgcolor="#FFFFFF" text="#000000"><center>
<div align="left"><br>
  <?php
         print("<form name=\"change_professor\" method=\"get\" action=\"abono_step_2.php\">
        <p>Selecione o professor:</p>
		<p><select name=\"idprofessor\" class=\"select\" onchange=\"document.change_professor.submit();\">
		<option>--- professor ---</option>");
	        while($rowprof = pg_fetch_array($queryprof)) 	
                {
        		$nomecompleto = $rowprof["nome_completo"];
		        $codigonome = $rowprof["id_nome"];
			print "<option value=$codigonome>$nomecompleto</option>";
                }
		print("</select></form>\n");


  
	 if ($_GET["idprofessor"] != "")  
         {
         print("<form name=\"change_periodo\" method=\"get\" action=\"abono_step_2.php\">
      	<input type=\"hidden\" name=\"idprofessor\" value=\"$idprofessor\">
        <p>Selecione o per&iacute;odo:</p>
		<p><select name=\"sendperiodo\" class=\"select\" onchange=\"document.change_periodo.submit();\">
		<option>--- per&iacute;odo ---</option>");
	        while($row1 = pg_fetch_array($query1)) 	{
        		$nomeperiodo = $row1["descricao"];
		        $codiperiodo = $row1["id"];
		        //echo $codiperiodo;
		        //$id = $row1["ref_professor"];
			print "<option value=$codiperiodo>$nomeperiodo</option>";
		        }
		print("</select></form>\n");
         }


	 if ($_GET["sendperiodo"] != "")  {
    	print("<!-- get periodo --><form enctype=\"multipart/form-data\" name=\"change_curso\" method=\"get\" action=\"abono_step_2.php\">
    	<input type=\"hidden\" name=\"idprofessor\" value=\"$idprofessor\">
        <input type=\"hidden\" name=\"sendperiodo\" value=\"$sendperiodo\">
		<p>Selecione o curso:</p>
		<p><select name=\"sendcurso\" class=\"select\" onchange=\"document.change_curso.submit();\">
		<option>--- curso ---</option>");
	        while($row2 = pg_fetch_array($query2))
	        	{
        		$nomecurso = $row2["descricao"];
                        $codicurso = $row2["id"];
			print "<option value=$codicurso>($codicurso)$nomecurso</option>";
		        }
		print("</select></form>");
	 }
	 
	 if ($_GET["sendcurso"] != "")  {
    	print("<!-- curso disciplina --><form enctype=\"multipart/form-data\" name=\"change_disciplina\" method=\"get\" action=\"abono_step_2.php\">
    	<input type=\"hidden\" name=\"idprofessor\" value=\"$idprofessor\">
		<input type=\"hidden\" name=\"sendperiodo\" value=\"$sendperiodo\">
     	<input type=\"hidden\" name=\"sendcurso\" value=\"$sendcurso\">
		<p>Selecione a disciplina:</p>
		<p><select name=\"senddisciplina\" class=\"select\" onchange=\"document.change_disciplina.submit();\">
		<option>--- disciplina ---</option>");
            while($row3 = pg_fetch_array($query3))
            {
        		$nc = $row3["descricao_extenso"];
        		$idnc = $row3["id"];
        		$idof = $row3["idof"];
             	print "<option value=$idnc:$idof>($idof) - $nc</option>";
            }
		print("</select></form>");
	 }

	 if ($_GET["senddisciplina"]  != "")  {
       	print("<!-- get disciplina -->
           <form name=\"envia\" enctype=\"multipart/form-data\" action=\"abono_step_3.php\" method=\"get\">
	    <input type=\"hidden\" name=\"idprofessor\" value=\"$idprofessor\">
		<input type=\"hidden\" name=\"sendperiodo\" value=\"$sendperiodo\">
		<input type=\"hidden\" name=\"sendcurso\" value=\"$sendcurso\">
		<input type=\"hidden\" name=\"senddisciplina\" value=\"$senddisciplina\">
		<p>Selecione a data de chamada:</p>
		<p><select name=\"senddata\" class=\"select\" onchange=\"document.envia.submit();\">
		<option>--- data de chamada ---</option>");
	        while($row4 = pg_fetch_array($query4))
	        	{
        		// $nc = br_date($row4["dia"]);
			$nc = $row4["dia"];
        		$idnc = $row4["dia"];
             	print "<option value=$idnc>$nc</option>\n";
		        }
		print("</select></form>");
	 }

?>
</form>
</body>
</head>
</html>
