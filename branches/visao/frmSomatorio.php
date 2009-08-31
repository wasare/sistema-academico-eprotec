<html>
<head>
	<title>PRATO</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/prototype.js" type="text/javascript"></script>
<br/>
</head>
<body>
<form id="frmSomatorio" name="frmSomatorio" method="post" action="frmSomatorio_exec.php">

<table width="416" height="208" border="0" cellpadding="0" cellspacing="0" align="left" >
  <tr>
    <td width="17" valign="top">&nbsp;</td>
    <td width="385" valign="middle" align="center">
    
    <table width="91%" border="0" cellspacing="0" cellpadding="0" class="tabelaDeTipos">
      <tr>
        <td>&nbsp;</td>
        <td>
<b> SOMATÓRIO DE DADOS DO HISTÓRICO </b>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="76">Data Inicial :</td>
        <td width="310">
            <label>
            <input type="text" name="txtDataInicial" id="txtDataInicial" class="caixaGrande"  value="01-01-2000"/>
            </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Data Final :</td>
        <td>
            <label>
            <input type="text" name="txtDataFinal" id="txtDataFinal" class="caixaGrande" value="31-12-2010"/>
            </label>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td>Refei&ccedil;&atilde;o:</td>
        <td><select name="dpdRefeicao" id="dpdRefeicao">
        <option selected>Todas</option>
        <?php 
			require_once("../modelo/clsRefeicao.class.php");
					
			$ref = new clsRefeicao();
			echo $ref->ListaComboRefeicao();
		?>
        </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
        <input id="cmdGerar" type="submit" value="Gerar" class="botao"
        name="cmdGerar"/>       </td>
      </tr>
    </table></td>
    <td width="14" valign="top">&nbsp;</td>
  </tr>
</table>

</form>
</body>
</html>
