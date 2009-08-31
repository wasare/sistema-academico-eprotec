<?php
$metodo = @$_REQUEST['metodo'];
$codigo = @$_REQUEST['codigo'];
$nome = "";
?>

<html>
<head>
	<title>Gerenciando de Línguas - copAdmin</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/prototype.js" type="text/javascript"></script>

	<!--COMPONENTES AJAX DA PAGINA -->
    <script src="js/ajax/frmRefeicao_ajax.js" type="text/javascript"></script>
<br/>
</head>
<body>
<form id="frmAltera" name="frmAltera" method="post" action="frmRefeicao_exec.php">
<input type="hidden" name="txtMetodo" id="txtMetodo" value=""/>
<input type="hidden" name="txtCodigo" id="txtCodigo" value=""/>

<table width="416" height="208" border="0" cellpadding="0" cellspacing="0" align="left" >
  <tr>
    <td width="17" valign="top">&nbsp;</td>
    <td width="385" valign="middle" align="center">
    
    <table width="82%" border="0" cellspacing="0" cellpadding="0" class="tabelaDeTipos">
      <tr>
        <td>&nbsp;</td>
        <td>
		<?php
        	switch (trim($metodo))
        	{
        		case 'novo':
					echo '<b> SALVANDO REFEI&Ccedil;&Atilde;O </b>';
				break;
        		case 'altera':
        			echo '<b> ALTERANDO REFEI&Ccedil;&Atilde;O</b>';
        			
        			require_once("../modelo/clsRefeicao.class.php");
        			$ref = new clsRefeicao();
        			$ref->PegaRefeicaoPorCodigo($codigo);
        			$nome = $ref->GetDescricao();
					$custo = $ref->GetCusto();
					$unidade = $ref->GetUnidade();
					
        		break;
        	}
		?>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="76">C&oacute;digo:</td>
        <td width="310">
            <label>
            <input type="text" name="txtCod" id="txtCod" class="caixaGrande" disabled="disabled" value="<?php echo $codigo; ?>"/>
            </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Nome:</td>
        <td>
            <label>
            <input type="text" name="txtNome" id="txtNome" class="caixaGrande" value="<?php echo $nome; ?>"/>
            </label>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Custo:</td>
        <td><input type="text" name="txtCusto" id="txtCusto" class="caixaGrande" value="<?php echo $custo; ?>"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Unidade:</td>
        <td><select name="dpdUnidade" class="caixaGrande" id="dpdUnidade">
          <?php    
		  
          if (trim($unidade) == "PG")
			{
				echo '<option value="PG">Peso (gramas)</option>';
			}
			else if (trim($unidade) == "UN")
			{
				echo '<option value="UN">Unidade</option>';
			}          		
          ?>
          <option value="PG">Peso (gramas)</option>
          <option value="UN">Unidade</option>
        </select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
        <input id="cmdSalvar" type="button" value="Salvar" class="botao"
        name="cmdSalvar" onClick="submitForm('frmAltera','<?php echo $metodo; ?>','<?php echo $codigo; ?>');"/>       </td>
      </tr>
    </table></td>
    <td width="14" valign="top">&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
