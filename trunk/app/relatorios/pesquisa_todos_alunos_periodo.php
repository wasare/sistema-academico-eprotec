<?php

header("Cache-Control: no-cache");

//INCLUSAO DE BIBLIOTECAS
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php"); 


//Criando a classe de conexão
$Conexao = NewADOConnection("postgres");
	
//Setando como conexão persistente
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

//EXECUTANDO SQL COM ADODB
$Result1 = $Conexao->Execute("SELECT descricao, id FROM periodos ORDER BY 1 DESC;");

//Se Result1 falhar	
if (!$Result1){
	print $Conexao->ErrorMsg();			
    die();
}	

//EXECUTANDO SQL COM ADODB
$RsCidades = $Conexao->Execute("SELECT cidade_campus, id FROM campus WHERE ref_empresa = 1 ORDER BY 1;");

// Se RsCidades falhar
if (!$RsCidades){
        print $Conexao->ErrorMsg();
    die();
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Lista alunos matriculados</title>
<link href="../../Styles/formularios.css" rel="stylesheet" type="text/css" />
<style type="text/css">
        <!--
        .style1 {
            color: #0099FF;
            font-style: italic;
        }
        -->
    </style>
<script src="../../lib/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script language="javascript">
<!--

function ChangeOption(opt,fld){
  var i = opt.selectedIndex;
  if ( i != -1 )
    fld.value = opt.options[i].value;
  else
    fld.value = '';
}

function ChangeOp() {
  ChangeOption(document.myform.periodo,document.myform.periodo1);
}

function ChangeCode(fld_name,op_name){
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

-->
</script>
<link href="../../lib/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="lista_todos_alunos_periodo.php" name="myform">
  <h2>Relat&oacute;rio de  Todos os Alunos Matriculados no Per&iacute;odo</h2>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="68" align="center"><label class="bar_menu_texto">
        <input name="input" type="image" src="../../images/icons/print.jpg" alt="Exibir" onclick="submit_opt('lista_alunos.php');"/>
        <br />
        Exibir</label>      </td>
      <td width="63" align="center"><label class="bar_menu_texto"> <a href="#" onclick="history.back(-1)" class="bar_menu_texto"> <img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
        Voltar</a> </label>      </td>
    </tr>
  </table>
  <table width="637" cellpadding="0" cellspacing="0" bgcolor="#E6E6E6" class="pesquisa">
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">Este relat&oacute;rio pode levar algum tempo para exibir as informa&ccedil;&otilde;es e n&atilde;o esta apto a impress&atilde;o devido ao alto processamento e quantidade de dados retornados; 
        para dados mais espec&iacute;ficos acesse o relat&oacute;rio de <a href="../../relatorios/pesquisa_alunos.php">alunos matriculados</a>.</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="116">Per&iacute;odo:</td>
      <td width="519"><span id="sprytextfield1">
        <input name="periodo1" type="text" id="periodo2" size="10" onchange="ChangeCode('periodo1','periodo')" />
        <?php  print $Result1->GetMenu('periodo',null,false,false,0,'onchange="ChangeOp()"'); ?>
        <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio</span></span> </td>
    </tr>
    <tr>
      <td width="116">Cidade:</td>
      <td width="519"><span id="sprytextfield1">
	<?php  print $RsCidades->GetMenu('cidade',null,true,false,0); ?>
         <span class="style1">Caso n&atilde;o preenchido exibir&aacute; todas.</span></td>
    </tr>

    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
</form>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
//-->
</script>
</body>
</html>
