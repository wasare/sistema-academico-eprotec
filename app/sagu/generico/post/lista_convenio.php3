<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title>Localizar Convenio</title>
<script language="JavaScript">
function _init()
{
  document.selecao.cnome.focus();
}

function addconvenio()
{
  
  window.open("../convenios_inclui.phtml","popWindowCity","toolbar=no,width=600,height=368,top=5,left=5,directories=no,menubar=no,scrollbars=yes");
}
</script>

<script language="PHP">
$hasmore = false;

function ListaConvenio()
{
  global $cnome, $hasmore;
  global $like;

  $cnome = strtoupper($cnome);

  $like = "";

  if ( $cnome != "" )
    $like = "$cnome";

  $like = "$like%";

  if ( $like != "" )
  {
    $hasmore = true;
    
    // cores fundo
    $bg0 = "#000000";
    $bg1 = "#EEEEFF";
    $bg2 = "#FFFFEE";

    // cores fonte
    $fg0 = "#FFFFFF";
    $fg1 = "#000099";
    $fg2 = "#000099";

    $conn = new Connection;
  
    $conn->Open();

    $sql = "select id, nome from convenios_medicos ". 
           "  where nome like '$like'";
    
    $query = $conn->CreateQuery($sql);

    echo("<table width=\"490\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
    
    echo("  <tr bgcolor=\"$bg0\">\n");
    echo("    <td width=\"5%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"$fg0\">&nbsp;</font></b></td>\n");
    echo("    <td width=\"20%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"$fg0\">Cód.</font></b></td>\n");
    echo("    <td width=\"75%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"$fg0\">Descrição</font></b></td>\n");
    echo("  </tr>\n");

    for ( $i=1; $i <= 25; $i++ )
    {
      if ( !$query->MoveNext() )
      {
        $hasmore = false;
	    break;
      }
      
      list ( $id, $nome ) = $query->GetRowValues();
      
      if ( $i % 2 )
      {
        $bg = $bg1;
        $fg = $fg1;
      }
      
      else
      {
        $bg = $bg2;
        $fg = $fg2;
      }

      if ( empty($campo) )
        $campo = '';

      $href = "<a href=\"javascript:_select($id,'$nome')\"><img src=\"../images/select.gif\" title='Selecionar' border=0></a>";

      echo("  <tr bgcolor=\"$bg\">\n");

      echo("    <td width=\"5%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"$fg\">$href</font></b></td>\n");
      echo("    <td width=\"20%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"$fg\">$id</font></b></td>\n");
      echo("    <td width=\"75%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\" color=\"$fg\">$nome</font></b></td>\n");
      echo("  </tr>\n");
    }

    echo("</table>");

    $query->Close();

    $conn->Close();
  }

  else
    echo("<br><center><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=red><b>Escolha um campo pelo menos!</b></font></center><br>");
}

</script>
<script language="JavaScript">
function _select(id,nome)
{

 window.opener.setResult(id,nome);
 window.close();

}
</script>
</head>
<body bgcolor="#FFFFFF" onload="_init()">
<form method="post" action="lista_convenio.php3" name="selecao">
  <div align="center"> 
    <table width="490" border="0" cellspacing="0" cellpadding="2">
      <tr bgcolor="#0066CC"> 
        <td colspan="2"> 
          <div align="center"><font size="2" color="#FFFFFF"><b><font face="Verdana, Arial, Helvetica, sans-serif">Localiza&ccedil;&atilde;o de Convênios</font></b></font></div>
        </td>
      </tr>
      <tr> 
        <td width="260"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Nome 
          do Convênio:</font> </td>
        <td width="222">&nbsp; </td>
      </tr>
      <tr> 
        <td width="260"> 
          <input type="text" name="cnome" size="40" maxlength="45" value="<?echo($cnome)?>">
        </td>
        <td width="222">
          <input type="submit" name="botao" value="Localizar">
        </td>
      </tr>
      <tr> 
        <td colspan="2"> 
          <hr>
        </td>
      </tr>
      <tr> 
        <td colspan="2"> 
          <script language="PHP">
              ListaConvenio();
          </script>
          <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
          <script language="PHP">
          if ( $hasmore )
             echo("<center>Se o Convênio Médico não estiver listado, seja mais específico.</center>");
          </script>
          </font></b></font></font> </td>
      </tr>
      <tr> 
        <td colspan="2"> 
          <hr>
        </td>
      </tr>
      <tr> 
        <td colspan="2"> 
          <div align="center"> 
            <input type="button" value=" Voltar  " onClick="javascript:window.close()" name="button">
            <input type="button" value=" Incluir " onClick="addconvenio()" name="button2">
          </div>
        </td>
      </tr>
    </table>
    
  </div>
</form>
</body>
</html>
