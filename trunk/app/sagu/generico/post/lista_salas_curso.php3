<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<?
  CheckFormParameters(array('ref_curso'));
?>
<html>
<head>
<title>Disciplinas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function _init()
{
  document.myform.id.focus();
}

function _select(id,nome)
{
  if ( window.callSetResult )
    window.callSetResult(id,nome);
  else
    window.opener.setResult(id,nome);

  window.close();
}
</script>
</head>
<body bgcolor="#FFFFFF" onload="_init()">
<font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000">
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
</font></b></font></font> 
<table border="0" cellspacing="2" cellpadding="0" align="center">
  <tr bgcolor="#0066CC"> 
    <td colspan="3" height="28" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><b>Salas 
      por Curso</b></font></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td width="20"><font face="Arial, Helvetica, sans-serif" size="2"> &nbsp; 
      </font></td>
    <td width="50"> <font face="Arial, Helvetica, sans-serif" size="2"> C&oacute;digo 
      </font></td>
    <td widht="430"> <font face="Arial, Helvetica, sans-serif" size="2"> Descri&ccedil;&atilde;o 
      </font><font face="Arial, Helvetica, sans-serif" size="2"> </font><font face="Arial, Helvetica, sans-serif" size="2"> 
      </font></td>
  </tr>
  <script language="PHP">
  $conn = new Connection;
  
  $conn->Open();

  // note the parantheses in the where clause !!!
  $sql = "select B.id, B.descricao_disciplina as d" .
         "  from cursos_disciplinas A, disciplinas B" .
         "  where A.ref_curso = '$ref_curso' and " .
         "        B.id = A.ref_disciplina" .
         "  order by d";

  echo("\n\n<!--\n\n$sql\n\n-->\n\n");

  $query = $conn->CreateQuery($sql);

  for ( $i=0; /* $i<25 && */ $query->MoveNext(); $i++ )
  {
    list ( $id, $nome, $prof, $idia ) = $query->GetRowValues();

    $href = "<a href=\"javascript:_select($id,'$nome')\"><img src=\"../images/select.gif\" alt='Selecionar' border=0></a>";

    if ( $i % 2 == 0)
    {
</script>
  <tr bgcolor="#EEEEFF"> 
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
    <td width="430"> <font face="Arial, Helvetica, sans-serif" size="2"> 
      <script language="PHP">
echo($nome);
</script>
      </font><font face="Arial, Helvetica, sans-serif" size="2"> </font><font face="Arial, Helvetica, sans-serif" size="2"> 
      </font></td>
  </tr>
  <script language="PHP">
    } // if 

    else 
    {
</script>
  <tr bgcolor="#FFFFEE"> 
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
    <td width="430"> <font face="Arial, Helvetica, sans-serif" size="2"> 
      <script language="PHP">
echo($nome);
</script>
      </font><font face="Arial, Helvetica, sans-serif" size="2"> </font><font face="Arial, Helvetica, sans-serif" size="2"> 
      </font></td>
  </tr>
  <script language="PHP">
    } // else
  } // for

  $hasmore = $query->MoveNext();

  $query->Close();
  $conn->Close();
</script>
  <tr> 
    <td colspan=3 align="center"> 
      <hr size="1" width="500">
      <script language="PHP">
if ( $hasmore )
  echo("<br>Se o Curso não estiver listado, seja mais específico.<br>");
</script>
      <input type="button" name="Button" value="Cancelar" onClick="javascript:window.close()">
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>