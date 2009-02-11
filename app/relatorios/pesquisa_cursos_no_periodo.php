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

$Result3 = $Conexao->Execute("SELECT descricao, id FROM tipos_curso ORDER BY 1 DESC;");

if (!$Result3){
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
<title>Lista Cursos em Andamento</title>
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
  ChangeOption(document.form1.periodo,document.form1.periodo1);
}

function ChangeCode(fld_name,op_name){
 
  var field = eval('document.form1.' + fld_name);
  var combo = eval('document.form1.' + op_name);
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

function submit_opt(arq){

	document.form1.action = arq; 

}

-->
</script>
<script src="../../lib/functions.js" type="text/javascript"></script>
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="lista_cursos_no_periodo.php" name="form1" target="_blank">
  <h2>Cursos em andamento por per&iacute;odo</h2>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="68" align="center">
          <label class="bar_menu_texto">
          <input name="input" type="image" src="../../images/icons/print.jpg" alt="Exibir" onclick="submit_opt('lista_cursos_no_periodo.php');" />
          <br />
          Exibir</label></td>
      <td width="73" align="center">
          <label class="bar_menu_texto">
          <input type="image" name="imageField" id="imageField" src="../../images/icons/pdf_icon.jpg" onclick="submit_opt('pdf_cursos_no_periodo.php');" />
          <br />
          Gerar PDF</label>
        </div></td>
      <td width="63" align="center">
          <label class="bar_menu_texto"> <a href="#" onclick="history.back(-1)" class="bar_menu_texto"> <img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
          Voltar</a> </label>      </td>
    </tr>
  </table>
  <table width="637" cellpadding="0" cellspacing="0" bgcolor="#E6E6E6" class="pesquisa">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="116">Per&iacute;odo:</td>
      <td width="519"><span id="sprytextfield1">
        <input name="periodo1" type="text" id="periodo1" size="10" onchange="ChangeCode('periodo1','periodo')" />
        <?php  print $Result1->GetMenu('periodo',null,true,false,0,'onchange="ChangeOp()"'); ?>
      </span> </td>
    </tr>
    <tr>
        <td>Tipo de curso:</td>
        <td>
        <?php print $Result3->GetMenu('tipo',null,true,false,0); ?>
        <span class="style1">Caso n&atilde;o selecionado exibir&aacute; todos.</span></td>
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
</body>
</html>
