<?php
include_once('../../webdiario.conf.php');

/* AJUSTA A FORMATAÇÃO DE DATAS PARA O PADRÃO POSTGRESQL */
//$data_postgres = gmdate("Y") . "-" . gmdate("m") . "-" . gmdate("d");
$data_postgres = date("d/m/Y");

/* Seleciona o Período */
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

echo $sql3;

$queryid = pg_exec($dbconnect, $sqlid);

          while($linha = pg_fetch_array($queryid)) 	
         {
            $result = $linha["id_nome"];
            $identificacao = $result;
         }
/* Seleciona os Cursos Referente ao Período */
if ($_GET["getperiodo"] != "") 
{

    $sql3 = "SELECT DISTINCT
            a.id,
            a.descricao,
            d.descricao_disciplina,
            d.descricao_extenso,
            o.id as idof
            FROM cursos a, disciplinas_ofer b,
            disciplinas_ofer_compl c, disciplinas_ofer_prof d, disciplinas e
            WHERE d.ref_professor = '$id' 
            AND d.id = d.ref_disciplina_ofer 
            AND d.ref_periodo = '$getperiodo' 
            AND d.is_cancelada = '0' 
            AND e.id = d.ref_disciplina
            AND d.ref_disciplina_ofer = b.id 
            AND d.ref_disciplina_compl = c.id 
            AND b.ref_curso = a.id";
   // echo $sql3;
            
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
        de Faltas por Per&iacute;odo</strong></font></div></td>
  </tr>
</table><center>
<div align="left"><br>
  <?
		print("<form name=\"change_periodo\" method=\"get\" action=\"consultaclasse.php\">
		<input type=\"hidden\" name=\"id\" value=\"$identificacao\">
        <p>Selecione o per&iacute;odo desejado:</p>
		<p><select name=\"getperiodo\" class=\"select\" onchange=\"document.change_periodo.submit();\">
		<option>--- per&iacute;odo ---</option>");
	        while($row1 = pg_fetch_array($query1)) 	
               {
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
