<?php
if (!IsSet($_SESSION['login'])) {
header("location:$erro");
         exit;
	   } else { ?>
<?php
require_once('../webdiario.conf.php');

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
      a.login = '$us'
      AND
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


	if ($_GET["getcurso"] != "") {
		$sql3 = "SELECT DISTINCT
                 f.id,
                 f.descricao_disciplina,
                 f.descricao_extenso
                 FROM
                 disciplinas_ofer b,
                 disciplinas_ofer_compl c,
                 disciplinas_ofer_prof d,
                 disciplinas f
                 WHERE
                 d.ref_professor = '$id' AND
                 b.ref_periodo = '$getperiodo' AND
                 d.ref_disciplina_compl = c.id AND
                 c.ref_disciplina_ofer = b.id AND
                 b.ref_disciplina = f.id
                 ";
		$query3 = pg_exec($dbconnect, $sql3);
	}
?>
<html>
<head>
<title>teste</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/forms.css" type="text/css">
<link rel="stylesheet" href="../css/gerals.css" type="text/css">
<body bgcolor="#FFFFFF" text="#000000"><center>
<div align="left"><br>
  <?    print('
  <table width="471" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Lançamento
        de Faltas</strong></font></div></td>
  </tr>
  <tr>
    <td> <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
        Você está em processo de lançamento de faltas.<br>
        Entre com os dados abaixo:</strong></font></p>
      <div align="center"></div></td>
  </tr>
</table>

  ');
		print("<form name=\"change_periodo\" method=\"get\" action=\"faltas.php\">
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
	 if ($_GET["getperiodo"] != "")  {
        $periodo = $_GET["getperiodo"];
    	print("<form enctype=\"multipart/form-data\" name=\"change_curso\" method=\"get\" action=\"faltas.php\">
    	<input type=\"hidden\" name=\"id\" value=\"$id\">
		<input type=\"hidden\" name=\"getperiodo\" value=\"$periodo\">
		<p>Selecione o curso:</p>
		<p><select name=\"getcurso\" class=\"select\" onchange=\"document.change_curso.submit();\">
		<option>Escolha o curso</option>");
	        while($row2 = pg_fetch_array($query2))
	        	{
        		$nomecurso = $row2["descricao"];
       		    $codicurso = $row2["id"];
			print "<option value=$codicurso>$nomecurso</option>";
		        }
		print("</select></form>");
	 }
	 
	 if ($_GET["getcurso"]  != "")  {
        $periodo = $_GET["getperiodo"];
        $curso = $_GET["getcurso"];
       	print("<form name=\"envia\" enctype=\"multipart/form-data\" action=\"historico.php\" method=\"get\">D
	    <input type=\"hidden\" name=\"id\" value=\"$id\">
		<input type=\"hidden\" name=\"getperiodo\" value=\"$periodo\">
		<input type=\"hidden\" name=\"getcurso\" value=\"$curso\">
		<p>Selecione a disciplina:</p>
		<p><select name=\"getdisciplina\" class=\"select\" onchange=\"document.envia.submit();\">
		<option>Escolha a disciplina</option>");
	        while($row3 = pg_fetch_array($query3))
	        	{
        		$nc = $row3["descricao_extenso"];
        		$idnc = $row3["id"];
             	print "<option value=$idnc>$nc</option>";
		        }
		print("</select></form>");
	 }
?>
</form>
</body>
</head>
</html>
<?PHP } ?>
