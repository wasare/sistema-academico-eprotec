<?php

/**
* Monta formulário  para entrada de informações da dispensa
* @author Wanderson Santiago dos Reis
* @version 1
* @since 19-02-2009
**/

$opcao = $_POST['op'];

$opcoes = array(2,3,4);


if( !in_array($opcao,$opcoes) AND !in_array($_POST['dispensa_tipo'],$opcoes) )
	die;

$erro_valida = 'Verifique os erros dos campos abaixo:'."\n\n";

$flag_erro = FALSE;

if($_POST['second'] == 1)
{

  // APROVEITAMENTO DE ESTUDOS
  if($_POST['dispensa_tipo'] == 2)
  {

	if (!is_numeric($_POST['ref_instituicao']) )
	{
		$erro_valida .= 'Instituição de origem é inválida'."\n";
        $flag_erro = TRUE;

	}

	if (empty($_POST['obs_aproveitamento']) OR strlen($_POST['obs_aproveitamento']) < 5 )
    {
        $erro_valida .= 'Nome da disciplina na Instituição de origem é inválida'."\n";
        $flag_erro = TRUE;

    }

	if (!is_numeric($_POST['nota_final']) OR  $_POST['nota_final'] < 50 OR $_POST['nota_final'] > 100 )
    {
        $erro_valida .= 'Nota da disciplina é inválida'."\n";
        $flag_erro = TRUE;

    }
  }

  // CERTIFICACAO DE EXPERIENCIA
  if($_POST['dispensa_tipo'] == 3)
  {

	if (!is_numeric($_POST['nota_final']) OR  $_POST['nota_final'] < 50 OR $_POST['nota_final'] > 100 )
    {
        $erro_valida .= 'Nota obtida na disciplina é inválida'."\n";
        $flag_erro = TRUE;

    }
	
  }

/*
	// EDUCAO FISICA
  if($_POST['dispensa_tipo'] == 4)
  {
  }
*/

	if( $flag_erro )
		echo $erro_valida;
	else
		echo "0";
/*	{
		require_once('processa.php');
	}
	else
		echo $erro_valida;
  */
    exit();

}




?>
   <div class="box_geral">
    <strong>Detalhes da dispensa:</strong><br /><br />

<?php
// EDUCACAO FISICA
if ($opcao == 4) 
{
?>


Texto Legal de dispensa de Educa&ccedil;&atilde;o F&iacute;sica:<br />

<textarea name="obs_final" id="obs_final" cols="80" rows="2" disabled="disabled" >Dispensa da Educa&ccedil;&atilde;o F&iacute;sica nos termos do Decreto-Lei N&ordm; 1.044 de 21/10/1969.</textarea> 

<br />

<input type="hidden" name="ref_liberacao_ed_fisica" id="ref_liberacao_ed_fisica" size="2"  value="1" />


<?php
}
// APROVEITAMENTO DE ESTUDOS
if ($opcao == 2)
{
?>

Institui&ccedil;&atilde;o:
  <input type="text" name="ref_instituicao" id="ref_instituicao" size="6" />
  <input type="text" name="instituicao_nome" id="instituicao_nome" size="35" >

    <a href="javascript:abre_consulta_rapida('../consultas_rapidas/instituicao/index.php')">
          <img src="../../images/icons/lupa.png" alt="Pesquisar usu&aacute;rio" width="20" height="20" />
   </a>

<br /> <br />
Nome da disciplina na Institui&ccedil;&atilde;o de origem:
<input type="text" name="obs_aproveitamento" id="obs_aproveitamento" size="30"  value="" />
<br /><br />


Nota da disciplina na Institui&ccedil;&atilde;o de origem:
<input type="text" name="nota_final" id="nota_final" size="3"  value="" />

<br />
<?php

}
// CERTIFICACAO DE EXPERIENCIAS
if ($opcao == 3)
{
?>


Nota obtida na avalia&ccedil;&atilde;o de compet&ecirc;ncia / experi&ecirc;ncia:
<input type="text" name="nota_final" id="nota_final" size="3"  value="" />

<br />
<br />

<?php
}


?>

<br />
N&ordm do processo e/ou observa&ccedil;&otilde;es adicionais:<br />

<textarea name="processo" id="processo" cols="80" rows="2"></textarea>

<input type="hidden" name="second" value="1">

</div>



