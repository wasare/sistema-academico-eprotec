<? require("../../../../lib/common.php"); ?>
<? require("../../../../lib/config.php"); ?>
<html>
<head>
<title>Localização de Pessoas por Nome</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>a { text-decoration:none }</style>
<script language="PHP">
$hasmore = false;

function ListaPessoas()
{
  global $pnome, $snome, $hasmore, $g_pessoas_list;
  global $like;

  $pnome = strtoupper($pnome);
  $snome = strtoupper($snome);

  $like = "";

  if ( $pnome != "" )
    $like = "$pnome";

  if ( $snome != "" )
    $like = "$like% $snome%";

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

    $pessoa = strtoupper($pessoa);
  
    $conn = new Connection;
  
    $conn->Open();

    $sql = "select id, nome from pessoas ". 
           "  where nome like '$like' order by nome";
    
    $query = $conn->CreateQuery($sql);

    echo("<table width=\"490\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">");
    
    echo("  <tr bgcolor=\"$bg0\">\n");
    echo("    <td width=\"25%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg0\">Código</font></b></td>\n");
    echo("    <td width=\"75%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg0\">Nome</font></b></td>\n");
    echo("  </tr>\n");

    for ( $i=1; $i <= $g_pessoas_list; $i++ )
    {
      if ( !$query->MoveNext() )
      {
	$hasmore = false;
	
	break;
      }
      
      list ( $id,$nome ) = $query->GetRowValues();
      
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

      $href = "<a href=\"javascript:Select('$id','$nome')\"><font color=\"$fg\">$id</font></a>";
	
      echo("  <tr bgcolor=\"$bg\">\n");
      echo("    <td width=\"25%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg\">$href</font></b></td>\n");
      echo("    <td width=\"75%\"><b><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"$fg\">$nome</font></b></td>\n");
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
function Select(id,nome)
{
 if ( window.opener.setPessoa != null )
   window.opener.setPessoa(id,nome);

 else if (window.opener.document.formVar.op_pessoa.value=="1")
 {
      window.opener.document.myform.pessoa.value = id;
      window.opener.document.myform.pnome.value = nome;
 }

 else if (window.opener.document.formVar.op_pessoa.value=="2")
 {
      window.opener.document.formPessoa.pessoa.value = id;
      window.opener.document.formPessoa.como.selectedIndex = 1;
 }

 else if (window.opener.document.formVar.op_pessoa.value=="3")
 {
      window.opener.document.myform.ref_segurado.value = id;
      window.opener.document.myform.segurado.value = nome;
 }

 else if (window.opener.document.formVar.op_pessoa.value=="4")
 {
      window.opener.document.myform.ref_pessoa.value = id;
      window.opener.document.myform.pessoa.value = nome;
 }

 else if (window.opener.document.formVar.op_pessoa.value=="5")
 {
      window.opener.document.myform.cobr_ref_folha_titular.value = id;
      window.opener.document.myform.folha_titular.value = nome;
 }

 else if (window.opener.document.formVar.op_pessoa.value=="6")
 {
      window.opener.document.myform.ref_professor.value = id;
      window.opener.document.myform.professor.value = nome;
 }

 else if (window.opener.document.formVar.op_pessoa.value=="7")
 {
      window.opener.document.myform.id.value = id;
      window.opener.document.myform.nome.value = nome;
 }

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
<p align="center"> 
  
  <br>
</p>
<table width="490" border="0" cellspacing="0" cellpadding="0" align="center" vspace="0" hspace="0">
  <tr>
    <td width="27%">
      <div align="center"><font size="4"><b><font color="#0066CC" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/logo_ies.gif" width="104" height="94"></font></b></font></div>
    </td>
    <td width="73%">
      <div align="center"><font size="4"><b><font color="#0066CC" face="Verdana, Arial, Helvetica, sans-serif">Localiza&ccedil;&atilde;o 
        de Pessoas por Nome</font></b></font></div>
    </td>
  </tr>
</table>
<form method="post" action="lista.php3" name="selecao">
  <div align="center"> 
    <table width="490" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Nome:</font> 
        </td>
        <td>
          <input type="text" name="pnome" size="50" maxlength="45" value="<?echo($pnome)?>">
        </td>
      </tr>
      <tr>
        <td><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Sobrenome:</font> 
        </td>
        <td>
          <input type="text" name="snome" size="50" maxlength="45" value="<?echo($snome)?>">
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
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
  ListaPessoas();
</script>
  <font face="Verdana, Arial, Helvetica, sans-serif"> <font size="2"> <b> <font color="#FF0000"> 
  <br>
  <br>
  <script language="PHP">
  if ( $hasmore )
    echo("A Pesquisa excedeu " . $g_pessoas_list . " registros.");
</script>
  <br>
  <br>
  <a href="javascript:window.close()"><font color="#000000">Voltar</font></a> 
  </font></b></font></font> </div>
</body>
</html>
