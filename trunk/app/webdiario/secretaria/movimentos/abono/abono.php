<?php
setcookie ("abono", "0", time( )-9999);
?>
<html>
<head>
<title>Abono de Faltas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../../css/geral.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" onLoad="document.form1.user.focus()">
<form name="form1" method="post" action="confereuser.php">
  <table width="62%" height="242" border="0" align="center" bgcolor="#FFFFFF">
    <tr>
      <td>
        <table width="552" border="0" align="center" height="300">
          <tr>
            <td width="546" height="81">
              <table width="253" border="0" align="center" height="93">
                <tr bgcolor="#FFFFFF"> 
                  <td colspan="2" height="20" class="login"> <div align="center"><font color="#990000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Abono 
                      de Faltas</strong></font></div></td>
                </tr>
                <tr> 
                  <td class="login" height="16"><font size="3">Usu&aacute;rio:</font></td>
                  <td height="16"> <font size="3"> 
                    <input type="text" name="user">
                    </font></td>
                </tr>
                <tr> 
                  <td class="login" height="6"><font size="3">Senha:</font></td>
                  <td class="login" height="6"> <font size="3"> 
                    <input type="password" name="senha">
                    </font></td>
                </tr>
                <tr> 
                  <td colspan="2" height="41" class="login"> <table width="197" border="0" align="center" height="27" class="login">
                      <tr> 
                        <td width="191"> <div align="right"> 
                            <input type="submit" name="submit" target="_parent" value="Prosseguir ...">
                            <input type="reset" name="reset" value="Limpar">
                          </div></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <p align="center"><strong><font color="#000099">Acesso permitido 
                somente &agrave; SECRETARIA !</font></strong></p></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
