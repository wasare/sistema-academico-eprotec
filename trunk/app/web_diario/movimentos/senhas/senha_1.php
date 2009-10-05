<?php

require_once('../../../setup.php');

list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

$conn = new connection_factory($param_conn);


$sql1 = "SELECT DISTINCT
      b.nome
      FROM
      diario_usuarios a, pessoas b
      WHERE
      a.login = '". $uid ."'
      AND
      a.id_nome = b.id;";

$nome_completo = $conn->adodb->getOne($sql1);

if($nome_completo === FALSE || !is_string($nome_completo))
{
    die('Falha ao efetuar a consulta: '. $conn->adodb->ErrorMsg());
}
	 
?>
<html>
<head>
  <title><?=$IEnome?> - troca senha</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" href="<?=$BASE_URL .'public/styles/web_diario.css'?>" type="text/css">

</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" class="papeleta">
  <tr> 
    <td colspan="2"><div align="center"><font color="#CC0000" size="4" face="Arial, Helvetica, sans-serif"><strong>Troca 
        de Senha</strong></font></div></td>
  </tr>
  <tr> 
    <td colspan="2"><div align="left"> 
        <?php print("<p>Ol&aacute; <strong>$nome_completo</strong>, bem vindo ao processo de troca da senha. Para efetuar
          a troca digite sua senha atual em seguida a nova senha.</p>");
          ?>
        <p>&nbsp;</p>
      </div></td>
  </tr>
  <tr> 
    <td>Digite sua senha atual: </td>
    <td><form name="form1" method="post" action="passwd_troca.php">
        <?php print("<input type=\"hidden\" name=\"user\" value=\"$uid\">"); ?>
        <input type="password" name="passwdoriginal" id="passwdoriginal" />
      </td>
  </tr>
  <tr> 
    <td>Digite a nova senha:</td>
    <td>
        <input type="password" name="passwnew1" id="passwnew1" />
    </td>
  </tr>
  <tr> 
    <td> Redigite sua nova senha:</td>
    <td>
        <input type="password" name="passwnew2" id="passwnew1" />
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
