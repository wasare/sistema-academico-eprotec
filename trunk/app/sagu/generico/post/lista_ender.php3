<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<? require("../../lib/SQLCombo.php3");?>
<?
$op_opcoes = SQLArray("select nome_modulo, id from sagu_modulos order by nome_modulo");
?>
<HTML>
<HEAD>

<title>Localização de Endereços</title>
<script language="PHP">
function ListaEnderecos()
{
  global $ref_modulo;

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

    $sql = " select id, " .
           "        endereco, " .
	   "        nome, " .
	   "        descricao, " .
	   "        modulo" .
           " from sagu_paginas" .
           " where modulo = '$ref_modulo' order by nome";

    $query = $conn->CreateQuery($sql);

  echo("<table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" align=\"center\">");

  echo ("<tr bgcolor=\"000099\">\n");
  echo ("<td width=\"30%\" valign=\"top\"><Font face=\"Verdana\" size=\"2\" color=\"ccccff\"><b>&nbsp;Página</b><br>");
  echo ("<td align=left width=\"80%\" valign=\"top\"><Font face=\"Verdana\" size=\"2\" color=\"ccccff\"><b>&nbsp;Descrição</b></td>");
  echo ("  </tr>");

  while( $query->MoveNext() )
  {
    $modulo = 0;
    $modulo_grp = "";

    while ( 1 )
    {
      list ( $id,
             $endereco,
	     $nome,
	     $descricao,
	     $modulo ) = $query->GetRowValues();

          $href = "<a href=\"javascript:Select_Ender('$id','$nome')\"><font color=\"$fg\">$nome</font></a>";
          echo ("<tr bgcolor=\"$bg2\">\n");
          echo ("<td width=\"30%\" valign=\"top\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$href<br>");
          echo ("<td align=left width=\"80%\" valign=\"top\"><Font face=\"Verdana\" size=\"2\" color=\"$fg2\">$descricao</td>");
          echo ("  </tr>");

      if ( $z > 10 )
        break;

      if ( !$query->MoveNext() )
        break;

      if ( $query->GetValue(5) != $modulo_grp )
      {
        $query->MovePrev();
        break;
      }
    }
  }

  echo("</table>");
  $query->Close();
  $conn->Close();
}

</script>

<script language="JavaScript">
function ChangeOption(opt,fld)
{
  var i = opt.selectedIndex;

  if ( i != -1 )
    fld.value = opt.options[i].value;
  else
    fld.value = '';
}

function ChangeOp()
{
   ChangeOption(document.myform.op,document.myform.ref_modulo);
}
		
function ChangeCode(fld_name,op_name)
{
  var field = eval('document.myform.' + fld_name);
  var combo = eval('document.myform.' + op_name);
  var code  = field.value;
  var n     = combo.options.length;
   
  for ( var i=0; i<n; i++ )
  {
     if ( combo.options[i].value == code )
     {
         combo.selectedIndex = i;
         return;
     }
  }

  alert(code + ' não é um código válido!');

  field.focus();

  return true;
  
}

function Select_Ender(id,nome)
{
 window.opener.document.myform.ref_pagina.value = id;
 window.opener.document.myform.pagina.value = nome;
 window.close();
}
</script>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="lista_ender.php3" name="myform">
  <div align="center"> 
    <table width="490" border="0" cellspacing="0" cellpadding="2">
      <tr> 
        <td bgcolor="#000099" colspan="3" height="28"> <font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#CCCCFF"><b>&nbsp;Busca de P&aacute;ginas por m&oacute;dulo</b></font></td>
      </tr>
    <tr>
        <td bgcolor="#CCCCFF" width="109"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="00009C">&nbsp;Módulo</font></td>
        <td width="379"> <font color="#000000">
           <input name="ref_modulo" type=text size="5" onChange="ChangeCode('ref_modulo','op')">
           <?PHP ComboArray("op",$op_opcoes,"0","ChangeOp()"); ?> </font>
        </td>
        <td> 
          <input type="submit" name="botao" value="Localizar">
        </td>
      </tr>
    </table>
    
  </div>
</form>
<hr size="1" width="490">
<div align="center">
  <script language="PHP">
  ListaEnderecos();
</script>
  <font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><b><font color="#FF0000"> 
  </font></b></font></font> </div>
</body>
</html>
