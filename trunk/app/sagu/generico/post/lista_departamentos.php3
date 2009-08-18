<?php

require("../../common.php");

$id = $_POST['id'];
$desc = $_POST['desc'];

?>

<html>
<head>
<title><?echo($title);?></title>
<script language="JavaScript">
function _select(id,desc)
{
  window.opener.setResult(id,desc);
  window.close();
}
</script>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="lista_departamentos.php3">
<table width="500" border="0" cellspacing="2" cellpadding="0"
	align="center">
	<tr bgcolor="#0066CC">
		<td colspan="4" height="28" align="center"><font size="2"
			face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Consulta
		Departamentos</b></font></td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<td width="20">&nbsp;</td>
		<td width="50"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;C&oacute;digo</font></td>
		<td width="303"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;Descri&ccedil;&atilde;o</font></td>
		<td width="50">&nbsp;</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td>
		<td width="50"><input type="text" name="id" size="8"
			value="<?echo($id);?>"></td>
		<td width="303"><input type="text" name="desc"
			value="<?echo($desc);?>" size="40"></td>
		<td width="50" align="right"><input type="submit" name="Submit"
			value="Localizar"></td>
	</tr>
	<tr>
		<td colspan="4">
		<hr size="1" width="500">
		</td>
	</tr>
	<tr bgcolor="#CCCCCC">
		<td width="20" align="left">&nbsp;</td>
		<td width="50"><font face="Arial, Helvetica, sans-serif" size="2"
			color="#000000"> &nbsp;C&oacute;digo </font></td>
		<td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2"
			color="#000000"> &nbsp;</font><font size="2"
			face="Arial, Helvetica, sans-serif">Descri&ccedil;&atilde;o</font><font
			face="Arial, Helvetica, sans-serif" size="2" color="#000000"> </font></td>
	</tr>
	<?php

	$conn = new Connection;

	$conn->Open();

	// note the parantheses in the where clause !!!
	$sql = "select id, descricao from departamentos";

	$where = '';

	if ( $id != '' )
	$where .= " and id = '$id'";

	if ( $desc != '' )
	$where .= " and upper(descricao) like upper('$desc%')";

	if ( substr($where,0,5) == " and " )
	$where = " where " . substr($where,5);

	$sql .= $where . " order by descricao";

	$query = $conn->CreateQuery($sql);

	for ( $i=0; $i<25 && $query->MoveNext(); $i++ )
	{
		list ( $id, $nome ) = $query->GetRowValues();

		$href = "<a href=\"javascript:_select($id,'$nome')\"><img src=\"../../images/select.gif\" alt='Selecionar' border=0></a>";

		if ( $i % 2 == 0)
		{
			?>
	<tr bgcolor="#EEEEFF" valign="top">
		<td width="20"><font face="Arial, Helvetica, sans-serif" size="2"> <script
			language="PHP">
echo($href);
</script> </font></td>
		<td width="50"><font face="Arial, Helvetica, sans-serif" size="2"> <script
			language="PHP">
echo($id);
</script> </font></td>
		<td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2"> <script
			language="PHP">
echo($nome);
</script> </font><font face="Arial, Helvetica, sans-serif" size="2"> </font></td>
	</tr>
	<script language="PHP">
    } // if 

    else 
    {
</script>
	<tr bgcolor="#FFFFEE" valign="top">
		<td width="20"><font face="Arial, Helvetica, sans-serif" size="2"> <script
			language="PHP">
echo($href);
</script> </font></td>
		<td width="50"><font face="Arial, Helvetica, sans-serif" size="2"> <script
			language="PHP">
echo($id);
</script> </font></td>
		<td colspan="2"><font face="Arial, Helvetica, sans-serif" size="2"> <script
			language="PHP">
echo($nome);
</script> </font><font face="Arial, Helvetica, sans-serif" size="2"> </font></td>
	</tr>
	<script language="PHP">
    } // else
  } // for

  $hasmore = $query->MoveNext();

  $query->Close();
  $conn->Close();

</script>
	<tr>
		<td colspan="4" align="center"><font
			face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font
			color="#FF0000"> <?PHP
			if ( $hasmore ) echo("<br>Se o Departamento n�o estiver listado, seja mais espec�fico.<br>");
			?> </font></b></font></font>
		<hr size="1" width="500">
		<input type="button" name="Button" value="Cancelar"
			onClick="javascript:window.close()"></td>
	</tr>
</table>
</form>
</body>
</html>
