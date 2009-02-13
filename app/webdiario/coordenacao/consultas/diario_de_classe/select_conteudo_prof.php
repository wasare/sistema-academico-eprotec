<?php
include ('../../conf/webdiario.conf.php');
// CONECTA BD
////////////////$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");

      /* AJUSTA A FORMATAÇÃO DE DATAS PARA O PADRÃO POSTGRESQL */
      $data_postgres = gmdate("Y") . "-" . gmdate("m") . "-" . gmdate("d");

/* Seleciona o Período */
/* $sql1 = "SELECT DISTINCT
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
      a.login = '$us'
      AND
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id
      AND
      c.ref_periodo = d.id
      AND
      d.dt_inicial < '$data_postgres'
      AND
      d.dt_final > '$data_postgres'";
    */
$sql1 = "SELECT DISTINCT
      a.login,
      a.id_nome,
      b.ref_professor,
      c.ref_periodo,
      d.descricao
      FROM
      diario_usuarios a, disciplinas_ofer_prof b, disciplinas_ofer c, periodos d
      WHERE
      a.login = '$us'
      AND
      a.id_nome = b.ref_professor
      AND
      b.ref_disciplina_ofer =  c.id
      AND
      c.ref_periodo = d.id
      AND
      d.dt_inicial < '$data_postgres'
      AND
      d.dt_final > '$data_postgres'";
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
/* Seleciona os Cursos Referente ao Período
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
           */

	if ($_GET["getperiodo"] != "") {
		$sql3 = "SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                f.ref_professor = '$id' and
                o.id = f.ref_disciplina_ofer and
                o.ref_periodo = '$getperiodo' and
                d.id = o.ref_disciplina";
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
    <td><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Diário de Classe</strong></font></div></td>
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
		        $id = $row1["ref_professor"];
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

<p><input type=\"submit\" value=\"Gerar Diário de Classe\"></p>
"); 
}


?>
</form>
</body>
</head>
</html>
