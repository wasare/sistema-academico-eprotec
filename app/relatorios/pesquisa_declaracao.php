<?php

header("Cache-Control: no-cache");

//INCLUSAO DE BIBLIOTECAS
require("../../lib/common.php");
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Lista alunos matriculados</title>
<link href="../../Styles/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	background-image:url(../../images/bg1.gif);
	background-repeat:repeat;
}
.style1 {
	color: #0099FF;
	font-style: italic;
}
-->
</style>

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

</head>
<body bgcolor="#FFFFFF">
<div align="center">
  <p>&nbsp;</p>
  <div align="left" style="width:638px;">
    <form method="post" action="lista_alunos.php" name="myform">
      <table width="637" cellpadding="0" cellspacing="0" class="pesquisa">
        <tr>
          <th colspan="2" align="center">Relat&oacute;rio de Declara&ccedil;&atilde;o </th>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="116">C&oacute;digo(s) Aluno(s):</td>
          <td width="519"><input name="curso" type="text" id="curso" size="40"> <label>
            <input type="submit" name="button" id="button" value="..." />
            <span class="style1"> <br />
            Para gerar em massa inserira  os c&oacute;digos de alunos separando por ;</span></label></td>
        </tr>
        <tr>
          <td>Data:</td>
          <td><label>
            <input type="text" name="data" id="data" />
          </label></td>
        </tr>
        <tr>
          <td>Declarar:</td>
          <td><textarea name="aluno" cols="60" rows="3" id="aluno"></textarea></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" style="background-color:#CCCCCC"><strong>Assinatura:</strong></td>
        </tr>
        <tr>
          <td style="background-color:#CCCCCC">Carimbo 1:</td>
          <td style="background-color:#CCCCCC">&nbsp;</td>
        </tr>
        <tr>
          <td style="background-color:#CCCCCC">Carimbo 2:</td>
          <td style="background-color:#CCCCCC">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="Submit"   value=" Gerar PDF">
            <input type="button" name="Submit22" value=" Voltar " onClick="location='menu.php'">          </td>
        </tr>
      </table>
    </form>
  </div>
</div>

</body>
</html>
