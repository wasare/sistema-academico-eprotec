<?php
$metodo = @$_REQUEST['metodo'];
$codigo = @$_REQUEST['codigo'];

require_once("../modelo/clsOperador.class.php");
$op = new clsOperador();
?>

<html>
<head>
	<title>Gestão de Operadores do PRATO</title>
    <link href="estilo/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/prototype.js" type="text/javascript"></script>

	<!--COMPONENTES AJAX DA PAGINA -->
    <script src="js/ajax/frmQuestao_ajax.js" type="text/javascript"></script>
<br/>
</head>
<body>
<form id="frmAltera" name="frmAltera" method="post" action="frmOperador_exec.php">
<input type="hidden" name="txtMetodo" id="txtMetodo" value=""/>
<input type="hidden" name="txtCodigo" id="txtCodigo" value=""/>

<table width="416" height="208" border="0" cellpadding="0" cellspacing="0" align="left" >
  <tr>
    <td width="17" valign="top">&nbsp;</td>
    <td width="385" valign="middle" align="center" >
    
    <table width="82%" border="0" cellspacing="0" cellpadding="0" class="tabelaDeTipos">
      <tr>
        <td>&nbsp;</td>
        <td>
		<?php
        	switch (trim($metodo))
        	{
        		case 'novo':
					echo '<b> SALVANDO QUESTÃO</b>';
				break;
        		case 'altera':
        			echo '<b> ALTERANDO QUESTÃO</b>';
        			$op->SelecionaPorCodigo($codigo);
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
            <input type="text" name="txtCodigo" id="txtCodigo" class="caixaGrande" disabled="disabled" value="<?php echo $op->GetCodigo(); ?>"/>
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
            <input type="text" name="txtNome" id="txtNome" class="caixaGrande" value="<?php echo $op->GetNome(); ?>"/>
            </label>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Login:</td>
        <td><input type="text" name="txtLogin" id="txtLogin" class="caixaGrande" value="<?php echo $op->GetLogin(); ?>"/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Senha</td>
        <td><input type="password" name="txtSenha" id="txtSenha" class="caixaGrande" value=""/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Perfil:</td>
        <td>
        <select name="dpdPerfil" class="caixaGrande" id="dpdPerfil">
          <?php    
		  
          if (trim($op->GetPerfil()) == "A")
			{
				echo '<option value="A">Administrador</option>';
			}
			else if (trim($op->GetPerfil()) == "O")
			{
				echo '<option value="O">Operador</option>';
			}          		
          ?>
          <option value="O">Operador</option>
          <option value="A">Administrador</option>
        </select>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      
    
      <tr>
        <td>&nbsp;</td>
        <td>
        <input id="cmdGravar" type="button" value="Gravar" class="botao"
        name="cmdGravar" onClick="submitForm('frmAltera','<?php echo $metodo; ?>','<?php echo $codigo; ?>');"/>       </td>
      </tr>
    </table></td>
    <td width="14" valign="top">&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
