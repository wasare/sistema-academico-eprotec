<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title>Localização de pessoas por Placa do Carro</title>
<script language="PHP">
$hasmore = false;

function ListaPessoa()
{
  global $placa, $hasmore, $limite_list;
  global $like;

  $placa = strtoupper($placa);

  $count = 0;

  $like = "";

  if ( $placa != "" )
    $like = "$placa";

  else if ( $like != "" )
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

    $placa = strtoupper($placa);
  
    $conn = new Connection;
  
    $conn->Open();

    $sql = "select id, nome, placa_carro from pessoas ". 
           "  where upper(placa_carro) like '%$like%' order by nome";
    
    $query = $conn->CreateQuery($sql);

    echo("<table width=\"490\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
    
    echo("  <tr bgcolor=\"$bg0\">\n");
    echo("    <td width=\"5%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg0\">&nbsp;</font></b></td>\n");
    echo("    <td width=\"20%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg0\">Código</font></b></td>\n");
    echo("    <td width=\"55%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg0\">Nome</font></b></td>\n");
    echo("    <td width=\"20%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg0\">Placa</font></b></td>\n");
    echo("  </tr>\n");
    
    for ( $i=1; $i <= $limite_list; $i++ )
    {
      if ( !$query->MoveNext() )
      {
  $hasmore = false;
  
  break;
      }
      
      list ( $id,$nome,$placa_carro ) = $query->GetRowValues();
      
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
     
      $href = "<a href=\"javascript:_select($id)\"><img src=\"../images/select.gif\" title='Selecionar' border=0></a>";

      echo("  <tr bgcolor=\"$bg\">\n");
      echo("    <td width=\"5%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg\">$href</font></b></td>\n");
      echo("    <td width=\"20%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg\">$id</font></b></td>\n");
      echo("    <td width=\"55%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg\">$nome</font></b></td>\n");
      echo("    <td width=\"20%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg\">$placa_carro</font></b></td>\n");
      echo("  </tr>\n");
    }

    echo("</table>");

    $query->Close();

    $conn->Close();

    $count = $i;
  }

  else
    echo("<br><center><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=red><b>Informe um campo para fazer a pesquisa!</b></font></center><br>");

  return $count;
}

</script>

<script language="JavaScript">
function _init()
{
  document.selecao.placa.focus();
}

function _select(id)
{
  window.opener.document.location='../../academico/pessoaf_edita.phtml' +
        '?id=' + id;

  window.close();
}
</script>


</head>
<body bgcolor="#FFFFFF" onload="_init()">
<form method="post" action="busca_placa.php3" name="selecao">
  <div align="center"> 
    <table width="69%" border="0" cellspacing="0" cellpadding="2">
      <tr bgcolor="#0066CC"> 
        <td colspan="4" height="28"> 
          <div align="center"><font size="2" color="#FFFFFF"><b><font face="Verdana, Arial, Helvetica, sans-serif">Localiza&ccedil;&atilde;o de Pessoas por Placa do Carro</font></b></font></div>
        </td>
      </tr>
      <tr> 
        <td width="1">&nbsp;</td>
        <td width="201"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Placa do carro:</font> 
        </td>
        <td width="75">&nbsp; </td>
      </tr>
      <tr> 
        <td width="1">&nbsp;</td>
        <td width="201"> 
          <input type="text" name="placa" size="40" maxlength="40" value="<?echo($placa)?>">
        </td>
        <td width="75"> 
          <input type="submit" name="botao" value="Localizar">
        </td>
      </tr>
      <tr> 
        <td colspan="4" align="center"> 
          <hr size="1" width="490">
        </td>
      </tr>
      <tr> 
        <td colspan="4"> 
          <script language="PHP">
  ListaPessoa();
</script>
          <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
          <script language="PHP">
  if ( $hasmore )
    echo("<BR>Se a Pessoa não estiver listada, seja mais específico.");
</script>
          </font></b></font></font> </td>
      </tr>
      <tr align="center"> 
        <td colspan="4"> 
          <hr size="1" width="490">
        </td>
      </tr>
      <tr align="center"> 
        <td colspan="4"> 
          <input type="button" value="Cancelar" onclick="javascript:window.close()">
        </td>
      </tr>
    </table>
    <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
    </font></b></font></font> </div>
</form>
</body>
</html>
