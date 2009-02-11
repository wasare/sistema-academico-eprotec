<? require("../../../lib/config.php"); ?>
<? SetCookie("SessionAuth","",0,"/","$sagu_cookie",0); ?>
<html>
<head>
<script language="PHP">

list($login_id,$pwd) = split(":",$SessionAuth,2);

if (empty($login_id) || $login_id=="")
   $login_id="Não definido";

</script>
</head>
<body bgcolor="#FFFFFF">
<table width="85%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="26%" height="91"> 
      <div align="center"><img src="images/logo_ies.gif" width="104" height="94"></div>
    </td>
    <td width="46%" height="91"> 
      <div align="center">
        <p><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b><font size="4" color="#0066CC">Logout</font><br>
          <font size="2">Sistema Administrativo</font></b></font></p>
        <p><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b><font size="2">Usu&aacute;rio: 
          <font color="#FF6666"> </font><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b><font size="2"><font color="#FF6666"> 
          <script language="PHP">
echo("\"$login_id\"");
</script>
          </font></font></b></font></font></b></font></p>
      </div>
    </td>
    <td width="28%" height="91"> 
      <div align="right"><img src="../images/princ.jpg" width="154" height="111"></div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif"><b><font color="#FF0000">Volte 
  Sempre!</font></b></font></p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b><a href="../index.php3" target="_top"><font size="2">Login</font></a><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b><font size="2"><font color="#FF6666"> 
  </font></font></b></font></font></b></font></p>
</body>
</html>
