<?php
include ('../conf/webdiario.conf.php');

$var = explode(":",$_GET[getdisciplina]);
$getdisciplina = $var[0];
$getofer = $var[1];

// CONECT NO BANCO
////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname");

// VARS

$sql9 = "SELECT
         a.descricao as cdesc,
         b.descricao_extenso,
         c.descricao as perdesc,
         d.ref_curso
         FROM
          cursos a,
          disciplinas b,
          periodos c,
          disciplinas_ofer d  where
          d.ref_periodo = '$getperiodo' AND
          b.id = '$getdisciplina' AND
          c.id = '$getperiodo' AND
          a.id = d.ref_curso";

$query9 = pg_exec($dbconnect, $sql9);
         while($linha9 = pg_fetch_array($query9)) {
               $getcurso   = $linha9["ref_curso"];
                }


$sql1 ="SELECT
               a.nome,
               b.descricao_extenso,
               b.carga_horaria,
               c.descricao
               FROM
               pessoas a, disciplinas b, cursos c
               WHERE
               a.id = '$id' AND
               b.id = '$getdisciplina' AND
               c.id = '$getcurso'";
$query1 = pg_exec($dbconnect, $sql1);
   pg_close($dbconnect);
   
   while($linha1 = pg_fetch_array($query1)) {
                  $result1 = $linha1["nome"];
				  $result2 = $linha1["descricao"];
				  $result3 = $linha1["descricao_extenso"];
				  $result4 = $linha1["carga_horaria"];
                            			   }
?>
<html>
<head>
<title>Diario</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<table width="100%" border="0">
  <tr>
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Consulta 
        de Carga Hor&aacute;ria</strong></font></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#666666"> 
    <td width="50%" align="center"><div align="left"><font color="#FFFFFF">&nbsp;<b>PROFESSOR 
        : <?PHP print $result1; ?></b></font></div></td>
    <td width="50%"><font color="#FFFFFF"><b>CURSO : <?PHP print $result2; ?></b></font></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#666666"> 
    <td align="center"><div align="left"><font color="#FFFFFF">&nbsp;<b>DISCIPLINA 
        : <?PHP print $result3; ?></b></font></div></td>
    <td width="50%"><font color="#FFFFFF"><b>CARGA HOR&Aacute;RIA : <?PHP print ($result4." Horas"); ?></b></font></td>
  </tr>
</table>
</body>
</html>
