<?php
session_start();

require_once("../config.class.php");
require_once("../modelo/clsOperador.class.php");
$config = new clsConfig();
	
if ((isset($_SESSION['codigo'])))
{
	$admin = new clsOperador();
	$admin->SelecionaPorCodigo(trim($_SESSION['codigo']));
}
else
{
	$config->Logout(false);
	$config->ConfirmaOperacao($config->GetPaginaPrincipal(),"Você não tem permissão para acessar essa página!");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Hist&oacute;rico de Alimenta&ccedil;&otilde;es</title>

<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-size: x-large;
}
.style2 {font-size: large}
.style3 {color: #FFFFFF}
-->
</style>
<link href="estilo/estilo.css" rel="stylesheet" type="text/css" />

 <!-- ESTILO JANELA -->
    <link href="css/themes/default.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/spread.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/alert.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/alert_lite.css" rel="stylesheet" type="text/css" />	

  	<link href="css/themes/alphacube.css" rel="stylesheet" type="text/css" />	
  	<link href="css/themes/debug.css" rel="stylesheet" type="text/css"/>
    
	<script src="js/prototype.js" type="text/javascript"></script>
    <script src="js/ajax/frmVenda_ajax.js" type="text/javascript"></script>
        
        <!-- JANELA -->
    <script type="text/javascript" src="js/janela/effects.js"> </script>
    <script type="text/javascript" src="js/janela/window.js"> </script>
    <script type="text/javascript" src="js/janela/window_effects.js"> </script>
    <script type="text/javascript" src="js/janela/debug.js"> </script>      
    
	<script type="text/javascript" src="js/mascara.js"> </script>
    <style type="text/css">
<!--
.style22 {color: #0066FF}
-->
    </style>
</head>

<body>
<form id="frmHistorico" name="frmHistorico" method="post" action="frmHistorico_exec.php">
<input type="hidden" name="txtMetodo" id="txtMetodo" value=""/>
<table width="100%" height="100%" border="0">
  <tr>
    <th bgcolor="#0099FF" scope="col"><span class="style1">RELAT&Oacute;RIO DE HIST&Oacute;RICO DE ALIMENTA&Ccedil;&Otilde;ES </span></th>
  </tr>
  
  <tr>
    <th bgcolor="#CCFFFF" scope="row"><a href="frmAdmin.php">Voltar para Principal</a> </th>
  </tr>
  <tr>
    <th bgcolor="#CCFFFF" scope="row"><div align="left" class="style2">Desejo visualizar:</div></th>
  </tr>
  <tr>
    <th scope="row"><table width="100%" border="0" align="left">
      <tr>
        <td><label>
          <div align="left">
            <input name="chkCodigo" type="checkbox" id="chkCodigo" value="chkCodigo" checked="checked" />
            C&oacute;digo</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkNome" type="checkbox" id="chkNome" value="chkNome" checked="checked" />
            Nome</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkVendedor" type="checkbox" id="chkVendedor" value="chkVendedor" checked="checked" />
            Vendedor</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkRefeicao" type="checkbox" id="chkRefeicao" value="chkRefeicao" checked="checked" />
            Refei&ccedil;&atilde;o</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkQuantidade" type="checkbox" id="chkQuantidade" value="chkQuantidade" checked="checked" />
            Quantidade          </div>
          </label></td>
      </tr>
      <tr>
        <td><label>
          <div align="left">
            <input name="chkCusto" type="checkbox" id="chkCusto" value="chkCusto" checked="checked" />
            Custo</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkDesconto" type="checkbox" id="chkDesconto" value="chkDesconto" checked="checked" />
            Desconto</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkPreco" type="checkbox" id="chkPreco" value="chkPreco" checked="checked" />
            Pre&ccedil;o</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkData" type="checkbox" id="chkData" value="chkData" checked="checked" />
            Data</div>
        </label></td>
        <td><label>
          <div align="left">
            <input name="chkHora" type="checkbox" id="chkHora" value="chkHora" checked="checked" />
            Hora</div>
        </label></td>
      </tr>
    </table></th>
  </tr>
  
  <tr>
    <th bgcolor="#CCFFFF" scope="row"><div align="left" class="style2">No per&iacute;odo:</div></th>
  </tr>
  <tr>
    <th scope="row"><table width="100%" border="0">
      <tr>
        <td width="28%">
          <div align="left">
            Data Inicial
          
          :
          <input name="txtDataInicial" type="text" id="txtDataInicial" value="01-01-2000" />
          </div>          </td>
        <td width="72%"><div align="left"> Data Final
          
          :
            <input name="txtDataFinal" type="text" id="txtDataFinal" value="31-12-2010" />
        </div>          </td>
        </tr>
    </table></th>
  </tr>
    <tr>
    <th bgcolor="#CCFFFF" scope="row"><div align="left" class="style2">Filtrado por: </div></th>
  </tr>
  
  <tr>
    <th scope="row"><table width="100%" border="0">
      <tr>
        <td width="16%"><div align="left">C&oacute;digo:</div></td>
        <td width="84%"><div align="left">
          <input name="txtFCodigo" type="text" id="txtFCodigo" onkeypress="mascara(this,soNumeros)"/> 
          <span class="rodape">*s&oacute; n&uacute;meros </span></div></td>
      </tr>
      <tr>
        <td><div align="left">Nome:</div></td>
        <td><div align="left">
          <input name="txtFNome" type="text" id="txtFNome" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Vendedor:</div></td>
        <td><div align="left">
          <input name="txtFVendedor" type="text" id="txtFVendedor" />
          <span class="rodape">*digite c&oacute;digo e nome juntos (exemplo: <span class="style22">2josias</span>) - <a href="frmOperador.php">Ver C&oacute;digos</a> </span></div></td>
      </tr>
      <tr>
        <td><div align="left">Refei&ccedil;&atilde;o:</div></td>
        <td><div align="left">
          <input name="txtFRefeicao" type="text" id="txtFRefeicao" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Desconto:</div></td>
        <td><div align="left">
              <input name="txtFDesconto" type="text" id="txtFDesconto" onkeypress="mascara(this,soNumeros)"/>
          %</div></td>
        </tr>
    </table></th>
  </tr>
  
  <tr>
    <th bgcolor="#CCFFFF" scope="row"><div align="left" class="style2">Ordenado: </div></th>
  </tr>
  <tr>
    <th scope="row"><table width="100%" border="0">
      <tr>
        <td width="14%"><div align="left">Primeiramente por </div></td>
        <td width="86%"><label>
          <div align="left">
            <select name="dpdOrd1" id="dpdOrd1">
			<option value="nenhuma">-- Selecione --</option>
              <option value="codigo">C&oacute;digo</option>
              <option value="nome">Nome</option>
              <option value="vendedor">Vendedor</option>
              <option value="refeicao">Refei&ccedil;&atilde;o</option>
              <option value="quantidade">Quantidade</option>
              <option value="custo">Custo</option>
              <option value="desconto">Desconto</option>
              <option value="preco">Pre&ccedil;o</option>
              <option value="data">Data</option>
            </select>
            </div>
        </label></td>
      </tr>
      <tr>
        <td><div align="left">em seguida por </div></td>
        <td><div align="left">
          <select name="dpdOrd2" id="dpdOrd2">
		  <option value="nenhuma">-- Selecione --</option>
            <option value="codigo">C&oacute;digo</option>
            <option value="nome">Nome</option>
            <option value="vendedor">Vendedor</option>
            <option value="refeicao">Refei&ccedil;&atilde;o</option>
            <option value="quantidade">Quantidade</option>
            <option value="custo">Custo</option>
            <option value="desconto">Desconto</option>
            <option value="preco">Pre&ccedil;o</option>
            <option value="data">Data</option>
          </select>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">em seguida por </div></td>
        <td><div align="left">
          <select name="dpdOrd3" id="dpdOrd3">
		  <option value="nenhuma">-- Selecione --</option>
            <option value="codigo">C&oacute;digo</option>
            <option value="nome">Nome</option>
            <option value="vendedor">Vendedor</option>
            <option value="refeicao">Refei&ccedil;&atilde;o</option>
            <option value="quantidade">Quantidade</option>
            <option value="custo">Custo</option>
            <option value="desconto">Desconto</option>
            <option value="preco">Pre&ccedil;o</option>
            <option value="data">Data</option>
          </select>
        </div></td>
        </tr>
    </table></th>
  </tr>
  
  <tr>
    <th scope="row">
    <table width="100%" border="1">
      <tr>
        <td><input id="cmdGerarRelatorio" type="button" value="Gerar Relatório" class="botaoHist" name="cmdGerarRelatorio"
        	onclick=
				"
				$('txtMetodo').value= 'gerar'; 
				$('frmHistorico').submit();
				"
			/>
          <input id="cmdMinhasConsultas" type="button" value="Minhas Consultas" class="botaoHist" name="cmdMinhasConsultas"
        onclick="window.location.href ='frmMinhasConsultas.php';" /></td>
        <td><label>
          <div align="right"><span class="rodape">Nome da Consulta:</span>
            <input name="txtNomeConsulta" type="text" class="caixaMedia" id="txtNomeConsulta" />
            </div>
        </label>
          <div align="right">
            <input id="cmdSalvarConsulta" type="button" value="Salvar Consulta" class="botaoHist" name="cmdSalvarConsulta"
				onclick=
				"
				$('txtMetodo').value= 'salvar'; 
				$('frmHistorico').submit();
				"		
			/>
          </div></td>
      </tr>
    </table></th>
  </tr>
  
  <tr>
    <th bgcolor="#0099FF" scope="row"><span class="style3">PRATO - Ponto Automatizado de Refeit&oacute;rio</span> </th>
  </tr>
</table>
</form>
</body>

</html>
