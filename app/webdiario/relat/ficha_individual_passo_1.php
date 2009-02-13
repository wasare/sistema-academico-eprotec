<?php
//////include ('../conf/webdiario.conf.php');
// CONECTA BD
/*
$dbuser='root';
$dbpassword='';
$dbname='sagu';

$dbconnect = pg_Pconnect("user=$dbuser password=$dbpassword dbname=$dbname") or die ("Não foi possivel conectar à fonte de dados");
*/

require_once('../conf/conn_diario.php');


/* Seleciona o Período */
$sql1 = "SELECT
	  id,
	  descricao
	  FROM
	  periodos order by 1";
$query1 = pg_exec($dbconnect, $sql1);

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
    <td width="471"><div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Ficha
        Individual</strong></font></div></td>
  </tr>
  <tr>
    <td> <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
        <br>
        Entre com os dados abaixo:</strong></font></p>
      <div align="center"></div></td>
  </tr>
</table>

  ');
       	print("<form name=\"envia\" enctype=\"multipart/form-data\" action=\"ficha_individual_passo_2.php\" method=\"get\">
      	<p>Selecione a classe:</p>
		<p><select name=\"getclasse\" class=\"select\" onchange=\"document.envia.submit();\">
		<option>Escolha a Classe</option>");
	        while($row1 = pg_fetch_array($query1))
	        	{
        		$desc = $row1["descricao"];
        		$id = $row1["id"];
               	print ("<option value=$id>$id --> $desc</option>");
		        }
		print("</select></form>");
?>
</form>
</body>
</head>
</html>
