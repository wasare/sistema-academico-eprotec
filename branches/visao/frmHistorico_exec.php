<?php
require_once("../config.class.php");
require_once("../modelo/clsRelatorio.class.php");
require_once("../modelo/clsMinhasConsultas.class.php");
session_start();

//FUNÇÃO PARA PEGAR OS DADOS DA ORDENAÇÃO (não esquenta com isso siga para o restante do documento)

function PegaCampoOrdem($valor)
{
	switch ($valor)
	{	
		case 'codigo':
			return ' tb_historico."FKid_usuario", ';
		break;
		
		case 'nome':
			return ' pessoas.nome, ';
		break;
		
		case 'vendedor':
			return ' tb_historico."FKcod_operador", ';
		break;
		
		case 'refeicao':
			return ' tb_historico.refeicao_historico, ';
		break;
		
		case 'quantidade':
			return ' tb_historico.quant_historico, ';
		break;
		
		case 'custo':
			return ' tb_historico.custo_historico, ';
		break;
		
		case 'desconto':
			return ' tb_historico.desconto_historico, ';
		break;
		
		case 'preco':
			return ' tb_historico.preco_historico, ';
		break;
		
		case 'data':
			return ' tb_historico.data_historico, ';
		break;
	}		
}


//PEGA O MÉTODO QUE VAI EXECUTAR (gerar ou salvar)
$metodo = $_POST['txtMetodo'];

//INICIA A CONTRUÇÃO DA STRING SQL DE CONSULTA-----------------------------------------
//MONTA CAMPOS-------------
$CAMPOS = "";

if (trim($_POST['chkCodigo']) != '')
	$CAMPOS .= 'tb_historico."FKid_usuario" as código, ';
	
if (trim($_POST['chkNome']) != '')
	$CAMPOS .= 'pessoas.nome as nome, ';
	
if (trim($_POST['chkVendedor']) != '')
	$CAMPOS .= 'tb_historico."FKcod_operador" as vendedor, ';
	
if (trim($_POST['chkRefeicao']) != '')
	$CAMPOS .= 'tb_historico.refeicao_historico as refeição, ';
	
if (trim($_POST['chkQuantidade']) != '')
	$CAMPOS .= 'tb_historico.quant_historico as quantidade, ';
	
if (trim($_POST['chkCusto']) != '')
	$CAMPOS .= '\'R$ \' || to_char(tb_historico.custo_historico, \'0D00\') as custo, ';
	
if (trim($_POST['chkDesconto']) != '')
	$CAMPOS .= 'tb_historico.desconto_historico || \' %\' as desconto, ';
	
if (trim($_POST['chkPreco']) != '')
	$CAMPOS .= '\'R$ \' || to_char(tb_historico.preco_historico, \'0D00\') as preço,';
	
if (trim($_POST['chkData']) != '')
	$CAMPOS .= 'to_char(tb_historico.data_historico, \'dd/mm/yyyy\') as data, ';
	
if (trim($_POST['chkHora']) != '')
	$CAMPOS .= 'tb_historico.hora_historico as hora, ';

//tira a vírgula do final da string
$CAMPOS = substr($CAMPOS, 0, strlen($CAMPOS) - 2);

//MONTA TABELAS---------------
$TABELAS = "";
if (trim($_POST['chkNome']) != '')
	$TABELAS .= 'public.pessoas, prato.tb_historico ';
else
	$TABELAS .= 'prato.tb_historico ';
	
//MONTA FILTROS--------------
$FILTROS = 'tb_historico."FKid_usuario" = pessoas.id AND ';

if (trim($_POST['txtDataInicial']) != '')
	$FILTROS .= ' tb_historico.data_historico>=\''.$_POST['txtDataInicial'].'\' AND ';
	
if (trim($_POST['txtDataFinal']) != '')
	$FILTROS .= ' tb_historico.data_historico<=\''.$_POST['txtDataFinal'].'\' AND ';
	
if (trim($_POST['txtFCodigo']) != '')
	$FILTROS .= ' tb_historico."FKid_usuario" like \''.$_POST['txtFCodigo'].'%\' AND ';
	
if (trim($_POST['txtFNome']) != '')
	$FILTROS .= ' pessoas.nome like \''.$_POST['txtFNome'].'%\' AND ';

if (trim($_POST['txtFVendedor']) != '')
	$FILTROS .= ' tb_historico."FKcod_operador" like \''.$_POST['txtFVendedor'].'%\' AND ';
	
if (trim($_POST['txtFRefeicao']) != '')
	$FILTROS .= ' tb_historico.refeicao_historico like \''.$_POST['txtFRefeicao'].'%\' AND ';
	
if (trim($_POST['txtFDesconto']) != '')
	$FILTROS .= ' tb_historico.desconto_historico='.$_POST['txtFDesconto'].' AND ';
	
//tira o AND do final da string
$FILTROS = substr($FILTROS, 0, strlen($FILTROS) - 5);

//MONTA ORDENADOR--------------
$ORDEM = "";
if (trim($_POST['dpdOrd1']) != 'nenhuma')
	$ORDEM .= PegaCampoOrdem($_POST['dpdOrd1']);
	
if (trim($_POST['dpdOrd2']) != 'nenhuma')
	$ORDEM .= PegaCampoOrdem($_POST['dpdOrd2']);
	
if (trim($_POST['dpdOrd3']) != 'nenhuma')
	$ORDEM .= PegaCampoOrdem($_POST['dpdOrd3']);

//tira a vírgula do final da string
$ORDEM = substr($ORDEM, 0, strlen($ORDEM) - 2);

//JUNÇÃO DOS TEXTOS----------
$SQL =  'SELECT ' . $CAMPOS . ' FROM ' . $TABELAS . ' WHERE ' . $FILTROS;
		
if ($ORDEM != '')
	$SQL .= ' ORDER BY ' . $ORDEM;
			
//STRING SQL MONTADA-----------------------------------------


switch ($metodo)
{
	//SELEÇÕES DE GRID----------------------------------------------
	
	case 'salvar':
		$rel = new clsMinhasConsultas();
		$nome = $_POST['txtNomeConsulta'];
		if (trim($nome) == '')
			$nome = 'Consulta sem nome';
		$rel->SalvarConsulta($nome, $SQL);
		
		$config = new clsConfig();
		$config->ConfirmaOperacao("frmHistorico.php",'Consulta salva com sucesso! Acesse as suas consultas salvas clicando em <a href="frmMinhasConsultas.php">Minhas Consultas</a><br> ');
	break;
	
	case 'gerar':
		$rel = new clsRelatorio();
		$rel->RelHistorico($SQL);
	break;
}
	
	
	
?>