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
    <script src="../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    <script language="javascript" src="../../lib/prototype.js"></script>
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
    <script src="../../lib/SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
    <link href="../../lib/SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
    <link href="../../lib/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<form method="post" action="lista_alunos.php" name="form1" target="_blank">
<h2>Relat&oacute;rio de  Alunos Matriculados</h2>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="68" align="center">
    <label class="bar_menu_texto">
        <input name="input" type="image" src="../../images/icons/print.jpg" alt="Exibir" onclick="submit_opt('lista_alunos.php');"/>
        <br />
    Exibir</label>
</td>
<td width="73" align="center">
    <label class="bar_menu_texto">
        <input type="image" name="imageField" id="imageField" src="../../images/icons/pdf_icon.jpg" onclick="submit_opt('pdf_alunos.php');" />
        <br />
    Gerar PDF</label>
</td>
<td width="63" align="center">
    <label class="bar_menu_texto"> <a href="#" class="bar_menu_texto" onclick="history.back(-1)"> <img src="../../images/icons/back.png" alt="Voltar" width="20" height="20" /><br />
    Voltar</a> </label>
</td>
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
                <input name="periodo1" type="text" id="periodo1" size="10" onchange="ChangeCode('periodo1','periodo'); setPeriodo();" />
                <?php  print $Result1->GetMenu('periodo',null,true,false,0,'onchange="ChangeOp()"; setPeriodo();'); ?>
        <span class="textfieldRequiredMsg">Obrigat&oacute;rio.</span></span> </td>
    </tr>
    <tr>
        <td>C&oacute;digo do Curso:</td>
        <td><span id="sprytextfield2">
                <label>
                    <input name="codigo_curso" type="text" id="codigo_curso" size="10" />
                    <input name="descricao_curso" disabled="disabled" id="descricao_curso" value="" size="40" />
                <a href="javascript:abre_consulta_rapida('../consultas_rapidas/cursos/index.php')"><img src="../../images/icons/lupa.png" alt="Pesquisar usu&aacute;rio" width="20" height="20" /></a> </label>
        <span class="textfieldRequiredMsg">Obrigat&oacute;rio.</span></span></td>
    </tr>
    <tr>
        <td>C&oacute;digo do Aluno:</td>
        <td><input name="aluno" type="text" id="aluno" size="10">
        <span class="style1">Caso n&atilde;o preenchido exibir&aacute; todos.</span></td>
    </tr>
    <tr>
        <td>Turma:</td>
        <td><input name="turma" type="text" id="turma" size="10" />
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="background-color:#CCCCCC"><strong>Exibir colunas:</strong><br>
            <span id="sprycheckbox1">
                <label>
                    <input type="checkbox" name="nome" id="nome" value="nome">
                    Nome
                    <input type="checkbox" name="pai" id="pai" value="pai">
                    Nome do Pai
                    <input type="checkbox" name="mae" id="mae" value="mae">
                    Nome da Mãe
                    <input type="checkbox" name="endereco" id="endereco" value="endereco">
                    Endereço
                    <input type="checkbox" name="bairro" id="bairro" value="bairro">
                    Bairro
                    <input type="checkbox" name="cidade" id="cidade" value="cidade">
                    Cidade
                    <input type="checkbox" name="cep" id="cep" value="cep">
                    CEP<br>
                    <input type="checkbox" name="telefone" id="telefone" value="telefone" />
                    Telefone(s)
                    <input type="checkbox" name="rg" id="rg" value="rg">
                    RG
                    <input type="checkbox" name="cpf" id="cpf" value="cpf">
                    CPF
                    <input type="checkbox" name="sexo" id="sexo" value="sexo">
                    Sexo
                    <input type="checkbox" name="data_nascimento" id="data_nascimento" value="data_nascimento">
                    Data de Nascimento
                    <label>
                        <input type="checkbox" name="turma2" id="turma2" value="turma2" />
                    </label>
                    Turma <br />
                    &nbsp;
                </label>
        <span class="checkboxMinSelectionsMsg">Selecione no mínimo uma coluna.</span></span> </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" style="background-color:#CCCCCC"><strong>Assinatura (opcional):</strong></td>
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
</form>
<script type="text/javascript">
    <!--
    var sprycheckbox1 = new Spry.Widget.ValidationCheckbox("sprycheckbox1", {isRequired:false, minSelections:1});
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
    var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
    //-->
</script>
</body>
</html>
