<?php
require_once("../modelo/clsRelatorio.class.php");
require_once("../controle/gtiString.php");
$codigo = $_REQUEST['codrel'];

switch ($codigo)
{
	//rankingos dos professores cadastrados com base na média -----------------------------------------------------
	case 'saldos':
		$rel = new clsRelatorio();
		
		$nome = 'LISTAGEM DE SALDOS';//nome do relatorio
		$campos = array('Código','Saldo', 'Grupo', 'Nome');//nome dos cabecalhos
		$tamanho = array('30','30','30', '100'); //tamanho dos cabecalhos: 120 
		$tam_fonte = 8; //tamanho da fonte
		$tam_fonte_cab = 8; //tamanho da fonte do cabecalho
		$dados = $rel->RelSaldos();//fabrica de relatorios
		
		$rel->ExibeRelatorio($nome, $campos, $tamanho, $dados, $tam_fonte, $tam_fonte_cab);
	break;
	
	case 'bolsistas':
		$rel = new clsRelatorio();
		
		$nome = 'LISTAGEM DE BOLSISTAS';//nome do relatorio
		$campos = array('Código','Grupo','Nome');//nome dos cabecalhos
		$tamanho = array('30','30','100'); //tamanho dos cabecalhos: 120 
		$tam_fonte = 8; //tamanho da fonte
		$tam_fonte_cab = 8; //tamanho da fonte do cabecalho
		$dados = $rel->RelBolsistas();//fabrica de relatorios
		
		$rel->ExibeRelatorio($nome, $campos, $tamanho, $dados, $tam_fonte, $tam_fonte_cab);
	break;
	
		

}

?>