<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title><?echo($title);?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function _select(proficiencia_id,proficiencia)
{
  if ( window.callSetResult )
    window.callSetResult(proficiencia_id, proficiencia);
  else
    window.opener.setResult(proficiencia_id, proficiencia);
  window.close();
}
</script>
</head>
<body bgcolor="#FFFFFF">
<font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000">
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> 
<form method="post" action="lista_proficiencias.php3">
  <table width="500" border="0" cellspacing="2" cellpadding="0" align="center">
    <tr bgcolor="#0066CC"> 
      <td colspan="4" height="28" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Consulta Proficiências</b></font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td>&nbsp;</td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">C&oacute;digo:</font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">Descricao:</font></td>
      <td width="50">&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td> 
        <input type="text" name="id" size="8" value="<?echo($id);?>">
      </td>
      <td> 
        <input type="text" name="proficiencia" value="<?echo($proficiencia);?>" size="40">
      </td>
      <td width="50"> 
        <input type="submit" name="Submit" value="Localizar">
      </td>
    </tr>
    <tr> 
      <td colspan="4"> 
        <hr size="1" width="500">
      </td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td width="20" align="left">&nbsp;</td>
      <td width="50"> <font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> 
        C&oacute;digo </font></td>
      <td colspan="2"> <font face="Arial, Helvetica, sans-serif" size="2" color="#000000">Proficiência</font></td>
    </tr>

<script language="PHP">
if ( $id != '' || $proficiencia != '' )
{
  $conn = new Connection;
  $conn->Open();

  $sql = "select id, descricao from proficiencias";
  $where = '';

  if ( $id != '' )
    $where .= " id = $id";

  if ( $proficiencia != '' )
    if ( $where != '' )
       $where .= " and upper(descricao) like upper('$proficiencia%')";
    else
       $where .= " upper(descricao) like upper('$proficiencia%')";

  $sql .= " where" . $where . " order by id";

  $query = $conn->CreateQuery($sql);

  for ( $i=0; $i<25 && $query->MoveNext(); $i++ )
  {
    list ( $id, 
           $proficiencia ) = $query->GetRowValues();
	   
    $href = "<a href=\"javascript:_select($id, '$proficiencia')\"><img src=\"../images/select.gif\" alt='Selecionar' border=0></a>";

    if ( $i % 2 == 0)
    {
</script>
    <tr bgcolor="#EEEEFF" valign="top"> 
      <td width="20"><font face="Arial, Helvetica, sans-serif" size="2"> 
<script language="PHP">
echo($href);
</script>
        </font></td>
      <td width="50"> <font face="Arial, Helvetica, sans-serif" size="2"> 
<script language="PHP">
echo($id);
</script>
        </font></td>
      <td colspan="2"> <font face="Arial, Helvetica, sans-serif" size="2"> 
<script language="PHP">
echo($proficiencia);
</script></font></td>
    </tr>
<script language="PHP">
    } // if 

    else 
    {
</script>
    <tr bgcolor="#FFFFEE" valign="top"> 
      <td width="20"><font face="Arial, Helvetica, sans-serif" size="2"> 
<script language="PHP">
echo($href);
</script>
        </font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2"> 
<script language="PHP">
echo($id);
</script>
        </font></td>
      <td colspan="2"> <font face="Arial, Helvetica, sans-serif" size="2"> 
<script language="PHP">
echo($proficiencia);
</script>
        </font></td>
    </tr>
<script language="PHP">
    } // else
  } // for

  $hasmore = $query->MoveNext();

  $query->Close();
  $conn->Close();
} // if
</script>
    <tr> 
      <td colspan="4" align="center"> 
        <script language="PHP">
        if ( $hasmore )
  		echo("<br>Resultado maior do que 25 linhas<br>");
        </script>
        <hr size="1" width="500">
        <input type="button" name="Button" value="Cancelar" onClick="javascript:window.close()">
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
