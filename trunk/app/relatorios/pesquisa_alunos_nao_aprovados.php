<?php

header("Cache-Control: no-cache");

//INCLUSAO DE BIBLIOTECAS
require("../../lib/common.php");
require("../../lib/config.php");
require("../../configuracao.php");
require("../../lib/adodb/adodb.inc.php");


//Criando a classe de conex�o
$Conexao = NewADOConnection("postgres");
	
//Setando como conex�o persistente
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
<title>Relat&oacute;rio de Alunos Aprovados/Reprovados</title>
<link href="../../Styles/formularios.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../../lib/prototype.js"></script>
<style type="text/css">
<!--
.style2 {
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

  alert(code + ' n�o � um c�digo v�lido!');

  field.focus();

  return true;
}


function setPeriodo() {
	periodo = $F('periodo1');
        var url = 'set_periodo.php';
        var parametros = 'p=' + periodo;
	var myAjax = new Ajax.Request( url, { method: 'post', parameters: parametros, onSuccess: function(transport) { return true; } });
}


function submit_opt(arq){

	document.form1.action = arq; 

}

-->
</script>
<script src="../../lib/functions.js" type="text/javascript"></script>
<script src="../../lib/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../lib/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<h2>Relat�rio de Alunos Aprovados/Reprovados</h2>
<form action="lista_alunos_nao_aprovados.php" method="post" name="form1" target="_blank">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="68"><div align="center">
          <label class="bar_menu_texto">
          <input name="" type="image" src="../../images/icons/print.jpg" alt="Exibir" onclick="submit_opt('lista_alunos_nao_aprovados.php');"/>
          <br />
          Exibir</label>
        </div></td>
      <td width="73"><div align="center">
          <label class="bar_menu_texto"> </label>
          <label>
          <input type="image" name="imageField" id="imageField" src="../../images/icons/pdf_icon.jpg" onclick="submit_opt('pdf_alunos_nao_aprovados.php');" />
          </label>
          <label class="bar_menu_texto"><a href="javascript:abrir_rel_submit('lista_alunos_nao_aprovados.php?opt=pdf');" class="bar_menu_texto"><br />
          Gerar PDF</a> </label>
        </div></td>
      <td width="63"><div align="center">
          <label class="bar_menu_texto"> <a href="#" onclick="history.back(-1)" class="bar_menu_texto"> <img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
          Voltar</a> </label>
        </div></td>
    </tr>
  </table>
  <div class="borda_janela">
    <table width="637" cellpadding="0" cellspacing="0" bgcolor="#E6E6E6" class="pesquisa">
      <tr>
        <td  width="118">&nbsp;</td>
        <td  width="517">&nbsp;</td>
      </tr>
      <tr>
        <td>Per&iacute;odo:</td>
        <td><span id="sprytextfield2">
          <input name="periodo1" type="text" id="periodo1" size="10" onchange="ChangeCode('periodo1','periodo'); setPeriodo();" />
          <?php  print $Result1->GetMenu('periodo',null,true,false,0,'onchange="ChangeOp();setPeriodo();"'); ?>
          <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio</span></span> </td>
      </tr>
      <tr>
        <td>C&oacute;digo do Curso:</td>
        <td><span id="sprytextfield1">
          <input name="codigo_curso" type="text" id="codigo_curso" size="10" />
          <input name="descricao_curso" disabled="disabled" id="descricao_curso" value="" size="40" />
          <a href="javascript:abre_consulta_rapida('../consultas_rapidas/cursos/index.php')" ><img src="../../images/icons/lupa.png" alt="Pesquisar usu&aacute;rio" width="20" height="20" /></a> <span class="textfieldRequiredMsg">Valor obrigat&oacute;rio.</span> </span></td>
      </tr>
      <tr>
        <td>C&oacute;digo do Aluno:</td>
        <td><input name="aluno" type="text" id="aluno" size="10">
          <span class="style2">Caso n&atilde;o preenchido exibir&aacute; todos os alunos.</span> </td>
      </tr>
      <tr>
        <td>Turma:</td>
        <td><input name="turma" type="text" id="turma" size="10" />
          <span class="style2">Caso n&atilde;o preenchido exibir&aacute; todas as turmas.</span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:#CCCCCC"><strong>Situa&ccedil;&atilde;o:</strong><br />
          <input type="radio" name="aprovacao" id="aprovacao" value="1" />
          Aprovado
          <input type="radio" name="aprovacao" id="aprovacao" value="2" checked="checked" />
          Reprovado
          <input type="radio" name="aprovacao" id="aprovacao" value="3" />
          Aprovado e Reprovado </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" style="background-color:#CCCCCC"><strong>Assinatura (opcional):</strong> </td>
      </tr>
      <tr>
        <td style="background-color:#CCCCCC">Resp. Nome:</td>
        <td style="background-color:#CCCCCC"><input name="resp_nome" type="text" id="resp_nome" size="60" />
        </td>
      </tr>
      <tr>
        <td style="background-color:#CCCCCC">Resp. Cargo:</td>
        <td style="background-color:#CCCCCC"><input name="resp_cargo" type="text" id="resp_cargo" size="60" />
        </td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
//-->
</script>
</body>
</html>
