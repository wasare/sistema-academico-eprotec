<?php
include ('../../conf/webdiario.conf.php');
// CONECTA BD
//////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");

/* Seleciona o Período */
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
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id
      AND
      c.ref_periodo = d.id";
$query1 = pg_exec($dbconnect, $sql1);

$sqlid = "SELECT DISTINCT
      a.id_nome
      FROM
      diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE
      a.login = '$us'
      AND
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id
      AND
      c.ref_periodo = d.id";
$queryid = pg_exec($dbconnect, $sqlid);

          while($linha = pg_fetch_array($queryid)) 	{
        		$result = $linha["id_nome"];
     			$identificacao = $result;
                }
/* Seleciona os Cursos Referente ao Período */
	if ($_GET["getperiodo"] != "") {
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
        d.ref_professor = '$id' AND
        b.ref_periodo = '$getperiodo' AND
        d.ref_disciplina_ofer = b.id AND
        d.ref_disciplina_compl = c.id AND
        b.ref_curso = a.id ";
		$query2 = pg_exec($dbconnect, $sql2);
	}
?>
<html>
<head>
<title>teste</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<body bgcolor="#FFFFFF" text="#000000"><table width="100%" border="0">
  <tr>
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Consulta 
        de Notas por Classe</strong></font></div></td>
  </tr>
</table><center>
<div align="left"><br>
  <?
		print("<form name=\"change_periodo\" method=\"get\" action=\"consultaclassen.php\">
		<input type=\"hidden\" name=\"id\" value=\"$identificacao\">
        <p>Selecione a classe desejada:</p>
		<p><select name=\"getperiodo\" class=\"select\" onchange=\"document.change_periodo.submit();\">
		<option>Escolha a classe desejada</option>");
	        while($row1 = pg_fetch_array($query1)) 	{
        		$nomeperiodo = $row1["descricao"];
		        $codiperiodo = $row1["ref_periodo"];
		        $id = $row1["ref_professor"];
			print "<option value=$codiperiodo>$nomeperiodo</option>";
		        }
		print("</select></form>");

?>
</form>
</body>
</head>
</html>
