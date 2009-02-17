<?php 

require("/var/www/sagu/html/lib/common.php3"); 
require("/var/www/sagu/html/lib/config.php3");
require("/var/www/sagu/html/lib/properties.php3");

$periodo = $properties->Get("periodo","0");
$pessoa = $properties->Get("pessoa","0"); 


function SQL_Combo($nome,$sql,$default,$onchange)
{
  $conn = new Connection;
  $conn->Open();
  $query = $conn->CreateQuery($sql);

  if ( $onchange != "" )
    echo("<select name=\"$nome\" onchange=\"$onchange\">");
  else
    echo("<select name=\"$nome\">");

  for ( $i=1; $query->MoveNext(); $i++ )
  {
    list ( $text, $value ) = $query->GetRowValues();

    if ( $value == $default )
      echo("  <option value=\"$value\" selected>$text</option>\n");
    else
      echo("  <option value=\"$value\">$text</option>\n");
  }
  echo("</select>");
  $query->Close();
  $conn->Close();
}

?>
<html>
<head>
<title>SA</title>
<script language="JavaScript">
<!--
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
  ChangeOption(document.myform.op,document.myform.periodo);
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

var cmp;

function buscaPlaca()
{
  cmp = '1';

  var url = '../generico/post/busca_placa.php3' +
            '?placa=' + escape(document.myform.placa.value);

  var wnd = window.open(url,'busca','toolbar=no,width=550,height=350,scrollbars=yes');
}

function buscaPessoa()
{
  cmp = '1';

  var url = '../../sagu/generico/post/lista_pessoas.php3' +
            '?pnome=' + escape(document.myform.pnome.value);

  var wnd = window.open(url,'busca','toolbar=no,width=550,height=350,scrollbars=yes');
}

function setResult(arg1,arg2)
{
  if (cmp=='1')
  {
    document.myform.pessoa.value = arg1;
    document.myform.pnome.value = arg2;
  }
}
-->
</script>
<link href="../../../Styles/formularios.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form method="post" action="consultas_diversas.php" name="myform">
  <h2>Consultas diversas</h2>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="68" align="center"><label class="bar_menu_texto">
        <input name="input" type="image" src="../../../images/icons/print.jpg" alt="Exibir" onclick="submit_opt('lista_alunos.php');"/>
        <br />
        Exibir</label>
      </td>
      <td width="63" align="center"><label class="bar_menu_texto"> <a href="#" class="bar_menu_texto" onclick="history.back(-1)"> <img src="../../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
        Voltar</a> </label>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="600" cellpadding="0" cellspacing="0" bgcolor="#E6E6E6" class="tabela_geral">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Per&iacute;odo</td>
      <td><input type="text" name="periodo" size="8" onChange="ChangeCode('periodo','op')" value="<?echo($periodo);?>">
        <?php
                  SQL_Combo("op",$sql_periodos_academico,$periodo,"ChangeOp()");
              ?>      </td>
    </tr>
    <tr>
      <td> Aluno</td>
      <td><input type="text" name="pessoa" size="8" maxlength="8" value="<?echo($pessoa);?>">
        <input type="text" name="pnome" size="25" maxlength="30">
        <input type="button" value="..." onClick="buscaPessoa()" name="button">      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
