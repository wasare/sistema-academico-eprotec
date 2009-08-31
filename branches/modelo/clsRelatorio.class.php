<?php
require_once("../controle/gtiConexao.class.php");
require_once("../controle/gtiRelatorio.class.php");


class clsRelatorio
{
	public function clsRelatorio()
	{
	}
	
	public function ExibeRelatorio($nome,$campos,$tamanho, $dados, $tam_fonte, $tam_fonte_cab)
	{
		$pdf= new gtiRelatorio();
		$pdf->SetNome($nome);
		$pdf->SetCabecalho($campos);
		$pdf->SetTamanho($tamanho);
		$pdf->SetTamFonteCabecalho($tam_fonte_cab);		
		
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',$tam_fonte);
		
		$numLin = count($dados);
		$numCol = count($dados[0]);
		
		for($i=0;$i<=($numLin-1);$i++)
		{
			$linha = '';
			for ($j=0;$j<=($numCol-1);$j++)
			{
				
				$tamCampo = strlen($dados[$i][$j]);
	    	
		    	if ($tamCampo < $tamanho[$j])
		    	{
		    		$comp = str_pad($dados[$i][$j], ($tamanho[$j]-$tamCampo), " ", STR_PAD_RIGHT); 
		    	}
		    	
		    	$linha .= $comp;
	    	
			}
			
			$pdf->Cell(0,10,$linha,0,0,'L');
			$pdf->Ln(5);
		}
		
		
		$pdf->Output();
	}
	
	
	public function RelSaldos()
	{	
			$con = new gtiConexao();
			$con->gtiConecta();
			$SQL = 'select tb_info_usuario."FKid_usuario" as codigo, pessoas.nome as nome, tb_grupo.des_grupo as grupo, 				
			
					tb_saldo.saldo_usuario as saldo FROM
					public.pessoas, prato.tb_grupo, prato.tb_info_usuario, financeiro.tb_saldo
					WHERE
					tb_info_usuario."FKcod_grupo" = tb_grupo.cod_grupo AND
					tb_info_usuario."FKid_usuario" = pessoas.id AND
					tb_info_usuario."FKid_usuario" = tb_saldo."FKid_usuario"
					ORDER BY
					tb_grupo.des_grupo,
					pessoas.nome,
					tb_saldo.saldo_usuario';	  
					  	
			$tbl = $con->gtiPreencheTabela($SQL);			
			$con->gtiDesconecta();
			
			$cont = 0;
			$rel = array();
			foreach($tbl as $chave => $linha)
			{				
				$rel[$cont][0] = trim($linha['codigo']);
				$rel[$cont][3] = trim($linha['nome']);
				$rel[$cont][2] = trim($linha['grupo']);
				$rel[$cont][1] = number_format(trim($linha['saldo']), 2, ',', '');
				
				$cont++;
			}
			
			return $rel;
	}
	
	public function RelHistorico($SQL)
	{
		$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		
		//Informacoes de cabecalho
	  $info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
	  $info .= "<strong>Hora: </strong>" . date("H:m:s") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
	  $info .= "<strong>Total de Registros: </strong>" . $tbl->RecordCount() . "&nbsp;&nbsp;&nbsp;&nbsp;<br><br>";
	  
	  //Dados de rodape com assinatura
	  $rodape = '<span style="font-size: 12px;">teste</span><br>';
	  $rodape .= '<span style="font-size: 9px;"><strong>teste</strong></span><br>';
	
	
	 //html2pdf
	  //ob_start();
	
	echo '
	<style type="text/css">

	.pequeno {font-size: small; text-align:center;}
	
	</style>
	<page backtop="10mm" backbottom="10mm" >
	<page_header></page_header>
	<page_footer>
	<table style="width: 700px;">
	  <tr>
		<td style="text-align: left; width: 50%">&nbsp;</td>
	  </tr>
	</table>
	</page_footer>
	<center>
	<span style="text-align:center; font-size:12px;">
		<img src="../visao/imagens/logoprato.jpg" width=200px><br />
		PRATO - Ponto Automatizado de Refeit&oacute;rio<br />
		CENTRO FEDERAL DE EDUCAÇÃO TECNOLÓGICA DE BAMBUÍ-MG<br />
		<br /><br /><br />
	</span></center>
	<h2 style="font-size:16px;">HIST&Oacute;RICO DE ALIMENTA&Ccedil;&Otilde;ES <br>
	<a href="javascript:window.print();" >imprimir listagem</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="frmHistorico.php" >voltar</a> </h2>';
	

	  echo $info;  
	  

		  require("../biblioteca/adodb5/tohtml.inc.php");
		  rs2html($tbl, 'width="100%" class="pequeno" cellspacing="0" border="0" cellpadding="0"'); 
	  
	  
	echo '</table>
	<br>
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center"><a href="javascript:window.print();" >imprimir listagem</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="frmHistorico.php" >voltar</a></td>
	  </tr>
	  <tr>
		<td align="center">____________________________________________________________________________________</td>
	  </tr>
	  <tr>
		<td align="center">PRATO - Ponto Automatizado de Refeit&oacute;rio</td>
	  </tr>
	</table>
	</page>';
	
		/*$content = ob_get_clean();
		require_once('../biblioteca/html2pdf/html2pdf.class.php');
		$pdf = new HTML2PDF('P','A4','en');
		$pdf->WriteHTML($content, isset($_GET['vuehtml']));
		$pdf->Output(); */
		
	}
	
	public function RelSomatorio($datainicial, $datafinal, $refeicao)
	{
		if (trim($refeicao)=="Todas")
		{
			$refeicao = '';
		}
	
		$filtro = ' data_historico >= \''.$datainicial.'\' AND data_historico <= \''.$datafinal.'\' AND refeicao_historico like \''.$refeicao.'%\'';
		$con = new gtiConexao();
		$con->gtiConecta();
		
		//Quantidade Total (vendas por peso)
		$quant_peso = '';
		$SQL = 'select sum(quant_historico) || \' Kilogramas\' as total from prato.tb_historico where quant_historico<3 AND unid_historico=\'PG\' AND ' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$quant_peso = $linha['total'];
		}
		
		//Quantidade Total (vendas por unidade): 
		$quant_unid = '';
		$SQL = 'select sum(quant_historico) || \' Unidades\' as total from prato.tb_historico where quant_historico<50 AND unid_historico=\'UN\' AND ' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$quant_unid = $linha['total'];
		}
		
		//Arrecadação Total (R$):
		$arrec_total = '';
		$SQL = 'select \'R$\' || to_char(sum(preco_historico), \'99999D00\') as total from prato.tb_historico where' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$arrec_total = $linha['total'];
		}
				
		//Total de Vendas Realizadas:
		$total_vendas = '';
		$SQL = 'select count(cod_historico) as total from prato.tb_historico where' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$total_vendas = $linha['total'];
		}
		
		//Total de Vendas Normais:
		$total_normais = '';
		$SQL = 'select count(cod_historico) as total from prato.tb_historico where ("FKid_usuario" <> \'cortesia\') AND ("FKid_usuario" <> \'vale\') AND ' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$total_normais = $linha['total'];
		}
		
		//Total de Vendas por Vale:
		$total_vales = '';
		$SQL = 'select count(cod_historico) as total from prato.tb_historico where "FKid_usuario"= \'vale\' AND ' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$total_vales = $linha['total'];
		}
		
		//Total de Vendas por Cortesia: 
		$total_cortesias = '';
		$SQL = 'select count(cod_historico) as total from prato.tb_historico where "FKid_usuario"= \'cortesia\' AND ' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$total_cortesias = $linha['total'];
		}
		
		//Total de Vendas por Marmitex: 
		$total_marmitex = '';
		$SQL = 'select count(cod_historico) as total from prato.tb_historico where "FKid_usuario"= \'marmitex\' AND ' .  $filtro;
		$tbl = $con->gtiPreencheTabela($SQL);
		foreach($tbl as $chave => $linha)
		{
			$total_marmitex = $linha['total'];
		}
		$con->gtiDesconecta();
		
		
		//Informacoes de cabecalho
	  $info .= "<strong>Data: </strong>" . date("d/m/Y") . "&nbsp;&nbsp;-&nbsp;&nbsp;";
	  $info .= "<strong>Hora: </strong>" . date("H:m:s") . "&nbsp;&nbsp;&nbsp;&nbsp;";
	  
	  //Dados de rodape com assinatura
	  $rodape = '<span style="font-size: 12px;">teste</span><br>';
	  $rodape .= '<span style="font-size: 9px;"><strong>teste</strong></span><br>';
	
	
	 //html2pdf
	  //ob_start();
	
	echo '
	<style type="text/css">

	.pequeno {font-size: small; text-align:center;}
	
	</style>
	<page backtop="10mm" backbottom="10mm" >
	<page_header></page_header>
	<page_footer>
	<table style="width: 700px;">
	  <tr>
		<td style="text-align: left; width: 50%">&nbsp;</td>
	  </tr>
	</table>
	</page_footer>
	<center>
	<span style="text-align:center; font-size:12px;">
		<img src="../visao/imagens/logoprato.jpg" width=200px><br />
		PRATO - Ponto Automatizado de Refeit&oacute;rio<br />
		INSTITUTO FEDERAL DE MINAS GERAIS - CAMPUS BAMBUÍ<br />
		<br /><br /><br />
	</span></center>
	<center>
	<h2 style="font-size:16px;">SOMAT&Oacute;RIO DE DADOS DO HIST&Oacute;RICO<br>
	<a href="javascript:window.print();" >imprimir listagem</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="frmRelatorios.php" >voltar</a> </h2>';
	

	  echo $info;  
	  
	if (trim($refeicao == ''))
		$refeicao = 'Todas';
		  
	  
	  echo '</center>
	   <center><p><strong> Data Inicial: </strong>'.$datainicial.'<strong>&nbsp;&nbsp;/&nbsp;&nbsp;Data Final: </strong>'.$datafinal.'<strong>&nbsp;&nbsp;/&nbsp;&nbsp;Refei&ccedil;&atilde;o: </strong>'.$refeicao.'</p>
	     <p>____________________________________________________________________________________</p>
		 <p></p></center>
		 
		 <table width="50%" border="0" align="center">
		  <tr>
			<td><strong>CATEGORIAS</strong> </td>
			<td><strong>TOTAIS</strong></td>
		  </tr>
		  <tr>
			<td><strong>Quantidade Total (vendas por peso):</strong> </td>
			<td>'.ceil($quant_peso).' Kilogramas</td>
		  </tr>
		  <tr>
			<td><strong>Quantidade Total (vendas por unidade):</strong> </td>
			<td> '.ceil($quant_unid).' Unidades</td>
		  </tr>
		  <tr>
			<td><strong>Arrecada&ccedil;&atilde;o Total (R$):</strong> </td>
			<td>'.$arrec_total.'</td>
		  </tr>
		  <tr>
			<td><strong>Total de Vendas Realizadas:</strong> </td>
			<td>'.$total_vendas.'</td>
		  </tr>
		  <tr>
			<td><strong>Total de Vendas Normais:</strong> </td>
			<td>'.$total_normais.'</td>
		  </tr>
		  <tr>
			<td><strong>Total de Vendas por Vale:</strong> </td>
			<td>'.$total_vales.'</td>
		  </tr>
		  <tr>
			<td><strong>Total de Vendas por Cortesia:</strong> </td>
			<td >'.$total_cortesias.'</td>
		  </tr>
		  <tr>
			<td><strong>Total de Vendas por Marmitex:</strong> </td>
			<td >'.$total_marmitex.'</td>
		  </tr>
		</table>
		 
		 <p></p>

	<br>
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center"><a href="javascript:window.print();" >imprimir listagem</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="frmRelatorios.php" >voltar</a></td>
	  </tr>
	  <tr>
		<td align="center">____________________________________________________________________________________</td>
	  </tr>
	  <tr>
		<td align="center">PRATO - Ponto Automatizado de Refeit&oacute;rio</td>
	  </tr>
	</table>
	</page>';
	
		/*$content = ob_get_clean();
		require_once('../biblioteca/html2pdf/html2pdf.class.php');
		$pdf = new HTML2PDF('P','A4','en');
		$pdf->WriteHTML($content, isset($_GET['vuehtml']));
		$pdf->Output(); */
		
	}
	
	public function RelBolsistas()
	{	
			$con = new gtiConexao();
			$con->gtiConecta();
			$SQL = 'SELECT pessoas.id as codigo, pessoas.nome as nome, tb_grupo.des_grupo as grupo FROM public.pessoas, prato.tb_grupo, prato.tb_info_usuario WHERE pessoas.id = tb_info_usuario."FKid_usuario" AND tb_info_usuario."FKcod_grupo" = tb_grupo.cod_grupo AND tb_grupo.des_grupo like \'B%\' ORDER BY tb_grupo.des_grupo, pessoas.nome;';	  
					  	
			$tbl = $con->gtiPreencheTabela($SQL);			
			$con->gtiDesconecta();
			
			$cont = 0;
			$rel = array();
			foreach($tbl as $chave => $linha)
			{				
				$rel[$cont][0] = trim($linha['codigo']);
				$rel[$cont][2] = trim($linha['nome']);
				$rel[$cont][1] = trim($linha['grupo']);
				
				$cont++;
			}
			
			return $rel;
	}
		
}
?>