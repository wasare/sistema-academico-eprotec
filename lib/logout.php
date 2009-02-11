<?php

require("config.php");
SetCookie("SessionAuth","",0,"/","$sagu_cookie",0); 

?>
<html>
<head>
<?php

list($login_id,$pwd) = split(":",$SessionAuth,2);

if (empty($login_id) || $login_id=="")
   $login_id="Não definido";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema Acadêmico</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
  <div id="banner">&nbsp;</div>
  <div id="menu">&nbsp;</div>
  <div id="info">
    <div align="center">
     <p>&nbsp;</p>
          <form method="post" action="../appcode/post/appcode/post/login.php" name="myform">
            <table width="400" border="0" cellpadding="0" cellspacing="0" class="pesquisa">
              
              <tr align="center">
                <th>Sistema Acad&ecirc;mico</th>
              </tr>
              <tr align="center">
                <td width="258" height="120">
                O usu&aacute;rio <strong><?php echo("\"$login_id\""); ?></strong><br />
				saiu do sistema com sucesso!             
                <p align="center"><a href="../appcode/index.php">Voltar para a tela de login</a></p>
                </td>
              </tr>
            </table>
            <br>
          </form>
       </div>
    </div>
    <div id="rodape">&nbsp;</div>
</div>
</body>
</html>