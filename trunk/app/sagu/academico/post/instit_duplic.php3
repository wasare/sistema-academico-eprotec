<? require("../../../../lib/common.php"); ?>

<html>
<head>

<script language="PHP">
  CheckFormParameters(array(
                            "id_old",
                            "id_new"));

  $conn = new Connection;

  $conn->Open();
    
  $sql = "insert into cadastro_duplo (" .
       "                               id_old," .
       "                               id_new," . 
       "                               tipo)" . 
       "       values (" .
       "                               '$id_old'," .
       "                               '$id_new'," .
       "                               'instituicoes')";


  $ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

</script>

</head>
<body bgcolor="#FFFFFF" marginwidth="20" marginheight="20" >
<table width="500" border="0" cellspacing="0" cellpadding="0" height="40" align="center">
  <tr bgcolor="#000099"> 
    <td height="35"> 
      <div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><b><font color="#CCCCFF"> 
        Opera&ccedil;&atilde;o Conclu&iacute;da</font></b></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><b></b></font></div>
    </td>
  </tr>
</table>
<br>
<form method="post" action="" name="myform">
  <table cols=2 width="472" align="center">
    <tr> 
      <td bgcolor="#FFFFFF" colspan="2"> 
        <div align="center"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="3" color="#FF0000">Registro 
          inclu&iacute;do com &ecirc;xito.</font></b><br>
        </div>
        <hr width="500">
      </td>
    </tr>
  
    <tr> 
      <td colspan="2"> 
        <div align="center">
          <input type="button" name="Button" value="Voltar" onClick="location='../instit_duplic.phtml'">
          <input type="button" name="Button2" value="Sair" onClick="javascript:history.go(-1)">
        </div>
      </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p></body>
</html>
