<?php
require("../../../lib/common.php");
require("../../../configuracao.php");
require("../../../lib/adodb/adodb.inc.php");
require("../../../lib/carimbo.php");
require("../../../lib/search.php");

$Conexao = NewADOConnection("postgres");
$Conexao->PConnect("host=$host dbname=$database user=$user password=$password");

$Result1 = $Conexao->Execute("SELECT descricao, id FROM periodos ORDER BY 1 DESC;");

if (!$Result1){
    print $Conexao->ErrorMsg();
    die();
}

//Carimbo
$carimbo = new carimbo($host,$user,$password,$database);

// Pesquisa estantanea
$busca = new search('search','codigo_curso','searchlist', 'form1', 'curso_lista.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <script src="../../../lib/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    <link href="../../../lib/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <script language="javascript" src="../../../lib/prototype.js"></script>
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
        alert(code + ' nao e um codigo valido!');
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
    <link href="../../../Styles/formularios.css" rel="stylesheet" type="text/css" />
    <title>SA</title>
</head>

<body onload="Oculta('confirmar');">
<form method="post" name="form1" target="_blank">
<h1>Egressos</h1>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="68" align="center">
    <label class="bar_menu_texto">
        <input name="input" type="image" src="../../../images/icons/print.jpg" alt="Exibir" onclick="submit_opt('lista_egressos.php');"/>
        <br />Exibir
    </label>
</td>
<td width="63" align="center">
    <label class="bar_menu_texto">
    	<a href="#" class="bar_menu_texto" onclick="history.back(-1)"> 
    	<img src="../../../images/icons/back.png" alt="Voltar" width="20" height="20" />
    	<br />Voltar</a> 
    </label>
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
        	<span class="textfieldRequiredMsg">Obrigat&oacute;rio.</span></span> 
        </td>
    </tr>
    <tr>
        <td>Consulta curso:</td>
        <td>
	    <span id="sprytextfield2">
        	<label>	<?php echo $busca->input_text_retorno("5"); ?> </label>
	    <span class="textfieldRequiredMsg">Obrigat&oacute;rio.</span></span>
	    <?php
		echo $busca->input_text_consulta("40");
		echo $busca->area_lista();
	    ?>
	</td>
    </tr>
    <tr>
        <td style="background-color:#CCCCCC">
	    <strong>Assinatura (opcional):</strong>
	</td>
        <td style="background-color:#CCCCCC">
	    <?php echo $carimbo->listar();?>
	</td>
    </tr>
    <tr>
        <td style="background-color:#CCCCCC"></td>
        <td style="background-color:#CCCCCC"></td>
    </tr>
</table>
</form>
<script type="text/javascript">
<!--
    var sprytextPeriodo = new Spry.Widget.ValidationTextField("sprytextfield1");
    var sprytextPessoa = new Spry.Widget.ValidationTextField("sprytextfield2");
//-->
</script>
</body>
</html>
