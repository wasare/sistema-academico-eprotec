<?php
include ('../../conf/webdiario.conf.php');

$sql1 = "SELECT DISTINCT
      a.login,
      b.nome
      FROM
      diario_usuarios a, pessoas b
      WHERE
      a.login = '$us'
      AND
      a.id_nome = b.id";
	  
$query1 = pg_exec($dbconnect, $sql1);
while($linha1 = pg_fetch_array($query1)) 	{
        		$nomecompleto = $linha1["nome"];
                }
                
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/geral.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="95%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2"><div align="center"><font color="#CC0000" size="4" face="Arial, Helvetica, sans-serif"><strong>TROCA 
        DE SENHA</strong></font></div></td>
  </tr>
  <tr> 
    <td colspan="2"><div align="left"> 
        <?PHP print("<p>Ol&aacute; <strong>$nomecompleto</strong>, bem vindo ao processo de troca da senha. Para efetuar
          a troca digite sua senha atual em seguida a nova senha.</p>");
          ?>
        <p>&nbsp;</p>
      </div></td>
  </tr>
  <tr> 
    <td width="22%">Digite sua senha atual: </td>
    <td width="78%"><form name="form1" method="post" action="passwd_troca.php">
        <?PHP print("<input type=\"hidden\" name=\"user\" value=\"$us\">"); ?>
        <input type="password" name="passwdoriginal">
      </td>
  </tr>
  <tr> 
    <td>Digite a nova senha:</td>
    <td>
        <input type="password" name="passwnew1">
    </td>
  </tr>
  <tr> 
    <td> Redigite sua nova senha:</td>
    <td>
        <input type="password" name="passwnew2">
      </td>
  </tr>
  <tr> 
    <td colspan="2">
        <div align="center">
          <input name="Submit" type="submit" id="Submit" value="Gravar">
        </div>
      </form></td>
  </tr>
</table>
</body>
</html>
