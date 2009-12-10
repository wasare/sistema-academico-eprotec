<?php
include_once('../../webdiario.conf.php');

/* AJUSTA A FORMATA��O DE DATAS PARA O PADR�O POSTGRESQL */
//$data_postgres = gmdate("Y") . "-" . gmdate("m") . "-" . gmdate("d");
//$data_postgres = date("d/m/Y");

/* Seleciona o Per�odo */
$query1 = getPeriodos($us);

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
	if($_GET["getperiodo"] != "") 
         {
		$sql3 = "SELECT DISTINCT
                        d.id,
                        d.descricao_disciplina,
                        d.descricao_extenso
                        FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                        WHERE f.ref_professor = '$id' 
                        AND o.id = f.ref_disciplina_ofer 
                        AND o.ref_periodo = '$getperiodo' 
                        AND d.id = o.ref_disciplina";
                $sql3 = "SELECT DISTINCT
                d.id,
                d.descricao_disciplina,
                d.descricao_extenso,
                o.id as idof
                FROM disciplinas_ofer_prof f, disciplinas_ofer o, disciplinas d
                WHERE
                f.ref_professor = '$id' AND
                o.id = f.ref_disciplina_ofer AND
                o.ref_periodo = '$getperiodo' AND
                o.is_cancelada = '0' AND
                d.id = o.ref_disciplina";
		$query3 = pg_exec($dbconnect, $sql3);
	}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/forms.css" type="text/css">
<link rel="stylesheet" href="../../css/gerals.css" type="text/css">
<body bgcolor="#FFFFFF" text="#000000">
<center>
<div align="left"><br>
  <?php
    
   print('
  <table width="471" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Consulta de Faltas por Sele��o de Disciplina</strong></font></div></td>
  </tr>
  <tr>
    <td>
      <div align="center"></div></td>
  </tr>
</table>

  ');
  
   print("<form name=\"change_periodo\" method=\"get\" action=\"consultapordisciplina1.php\">
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

   if($_GET["getperiodo"]  != "")  
   {
      $periodo = $_GET["getperiodo"];
        // $curso = $_GET["getcurso"];
      print("<form name=\"envia\" enctype=\"multipart/form-data\" action=\"consultapordisciplina.php\" method=\"get\"> 	    <input type=\"hidden\" name=\"id\" value=\"$id\"> 	<input type=\"hidden\" name=\"getperiodo\" value=\"$periodo\">  <p>Selecione a disciplina:</p> 	<p><select name=\"getdisciplina\" class=\"select\" onchange=\"document.envia.submit();\"> 
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
?>
</form>
</body>
</head>
</html>