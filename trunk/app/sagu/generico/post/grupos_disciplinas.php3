<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
  CheckFormParameters(array(
                            "descricao"));

$conn = new Connection;

$conn->Open();

$sql = "select nextval('seq_grupos_disciplinas')";
  
  $query = $conn->CreateQuery($sql);
  
  $success = false;
  
  if ( $query->MoveNext() )
  {
    $id = $query->GetValue(1);
    
    $success = true;
  }
  
  $query->Close();

SaguAssert($success,"Nao foi possivel obter um numero para o Grupo!");

$sql = "insert into grupos_disciplinas (" .
       "                               id," .
       "                               descricao)" . 
       "       values (" .
       "                               '$id'," .
       "                               '$descricao')";

// $query = $conn->CreateQuery();

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

SaguAssert($ok,"Nao foi possivel inserir o registro!");

// $query->Close();

$conn->Close();

</script>
</head>
<body bgcolor="#FFFFFF">
<table border="0" width="500" align="center" cellspacing="2" cellpadding="0">
  <tr bgcolor="#FFFFFF">
    <td width="27%">
      <div align="center"><img src="images/logo_ies.gif" width="104" height="94"></div>
    </td>
    <td width="73%">
      <div align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><b><font color="#000099">Grupos 
        de Disciplinas</font></b></font></div>
    </td>
  </tr>
</table>
<br>
<form method="post" action="" name="myform">
  <table cols=2 width="500" align="center">
    <tr> 
      <td bgcolor="#FFFFFF" colspan="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF0000"><b><font size="2">Registro 
        Incluido com &ecirc;xito<br>
        </font></b></font>
        <hr>
      </td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCFF" width="34%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#00009C">Id</font></td>
      <td bgcolor="#FFFFFF" width="66%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#00009C"><b> 
        <font color="#FF0000">
        <script language="PHP">echo($id);</script>
        </font> </b></font></td>
    </tr>
    <tr> 
      <td bgcolor="#CCCCFF" width="34%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#00009C">Descri&ccedil;&atilde;o</font></td>
      <td bgcolor="#FFFFFF" width="66%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#00009C"><b> 
        <script language="PHP">echo($descricao);</script>
        </b></font></td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr size="1">
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b><a href="../../academico/inserir_grupo_disciplinas.phtml">Incluir 
          Outro</a> - <a href="javascript:history.go(-2)">Voltar</a></b></font></div>
      </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p></body>
</html>
