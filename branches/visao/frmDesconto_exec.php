<?php

require_once("../modelo/clsRefeicao.class.php");
require_once("../modelo/clsDesconto.class.php");
require_once("../config.class.php");

$codgrupo = $_POST['txtCodigo'];

$desc = new clsDesconto();
$config = new clsConfig();
$ref = new clsRefeicao();

$tbl = $ref->TodasRefeicoes();

foreach($tbl as $chave => $linha)
{
	$codrefeicao = $linha['cod_refeicao'];
	
	$descantigo = trim($desc->PegaDesconto($codgrupo, $codrefeicao));
	$descnovo = trim(@$_POST['txtDesconto' . $codrefeicao]);
	
	//não alterou nada 
	if ($descantigo != $descnovo)
	{
		//retirou o desconto da refeicao
		if ($descnovo == 0)
		{
			$desc->Excluir($codgrupo, $codrefeicao);
			$config->ConfirmaOperacao("frmDesconto.php?codgrupo=".$codgrupo,"Registro alterado com sucesso!");
		}
		else
		{
			//inseriu um desconto na refeicao
			if ($descantigo == 0)
			{
				$desc->Salvar($codgrupo, $codrefeicao, $descnovo);
				$config->ConfirmaOperacao("frmDesconto.php?codgrupo=".$codgrupo,"Registro alterado com sucesso!");
			}
			//modificou o valor do desconto
			else
			{
				$desc->Alterar($codgrupo, $codrefeicao, $descnovo);
				$config->ConfirmaOperacao("frmDesconto.php?codgrupo=".$codgrupo,"Registro alterado com sucesso!");
			}
		}
		
	}

}

?>