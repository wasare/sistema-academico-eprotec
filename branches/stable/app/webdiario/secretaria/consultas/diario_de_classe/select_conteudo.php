<?php
include ('../../webdiario.conf.php');
// CONECTA BD
///////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("N�o foi possivel conectar � fonte de dados");

/* Seleciona o Per�odo */
$sql1 = "SELECT DISTINCT
      c.ref_periodo,
      d.descricao
      FROM
      diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id
      AND
      c.ref_periodo = d.id ORDER BY 2";
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
/* Seleciona os Cursos Referente ao Per�odo */
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

/* Seleciona Disciplinas por Classe */

	if ($_GET["getperiodo"] != "") {
	$sql3 = "select a.descricao_disciplina, a.id, a.carga_horaria from disciplinas a, disciplinas_ofer b where b.ref_periodo = '$getperiodo' and a.id = b.ref_disciplina";
	$query3 = pg_exec($dbconnect, $sql3);
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
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Di�rio de Classe</strong></font></div></td>
  </tr>
</table><center>
<div align="left"><br>
  <?
		print("<form name=\"change_periodo\" method=\"get\" action=\"select_conteudo.php\">
		<input type=\"hidden\" name=\"id\" value=\"$identificacao\">
        <p>Selecione a classe desejada:</p>
		<p><select name=\"getperiodo\" class=\"select\" onchange=\"document.change_periodo.submit();\">
		<option>Escolha a classe desejada</option>");
	        while($row1 = pg_fetch_array($query1)) 	{
        		$nomeperiodo = $row1["descricao"];
		        $codiperiodo = $row1["ref_periodo"];
		   ///////////     $id = $row1["ref_professor"];
			print "<option value=$codiperiodo>$nomeperiodo</option>";
		        }
		print("</select></form>");

if ($_GET["getperiodo"] != "") {
	$getperiodo=$_GET["getperiodo"];
	print("<form name=\"change_disciplina\" method=\"get\" action=\"select_conteudo.php\">
        <input type=\"hidden\" name=\"id\" value=\"$identificacao\">
        <input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">
        <p>Selecione a disciplina desejada:</p>
                <p><select name=\"getdisciplina\" class=\"select\" onchange=\"document.change_disciplina.submit();\">
                <option>Selecione a disciplina desejada<option>");
                while($row3 = pg_fetch_array($query3))  {
                        $descricao = $row3["descricao_disciplina"];
                        $id = $row3["id"];
                        $carga_total = $row3["carga_horaria"];       
                        print "<option value=$id>$descricao</option>";
                        }
                print("</select></form><br>");
}

if ($_GET["getdisciplina"] != "") {
	$getperiodo=$_GET["getperiodo"];
	$getdisciplina=$_GET["getdisciplina"];
        print("<form name=\"send\" method=\"post\" action=\"make_conteudo.php\">
        <input type=\"hidden\" name=\"id\" value=\"$identificacao\">
        <input type=\"hidden\" name=\"getperiodo\" value=\"$getperiodo\">
	<input type=\"hidden\" name=\"getdisciplina\" value=\"$getdisciplina\">

<p><input type=\"submit\" value=\"Gerar Di�rio de Classe\"></p>
"); 
}


?>
</form>
</body>
</head>
</html>
