<?php
require_once("../controle/gtiConexao.class.php");

class clsFila
{
	public function clsFila()
	{
	}
	
	public function PegaIntervalos($data,$refeicao)
	{
		$con = new gtiConexao();
		$con->gtiConecta();
		
		$SQL = 'SELECT hora_historico FROM prato.tb_historico WHERE data_historico=\''.$data.'\' AND refeicao_historico like \''.$refeicao.'%\';'; 

		$tbl = $con->gtiPreencheTabela($SQL);	
		
		$con->gtiDesconecta();

		$con = new gtiConexao();
			
		$arr = "";
		$string = "";
		$i = 0;
		
		foreach($tbl as $chave => $linha)
		{
			$tempo[$i] = strtotime($linha['hora_historico']);
			$string[$i] = $linha['hora_historico'];
			$i++;
		}
		
		$inter = "";
		//print_r($arr);
		for ($j=0;$j<=count($tempo)-1	;$j++)
		{
			$inter[$j][0] = $string[$j];
			$inter[$j][1] = $tempo[$j + 1] - $tempo[$j];
		}
		
		
		//sort($inter);
		
		for ($k=0;$k<=count($inter)-1;$k++)
		{
			echo 'índice: '.$k. ' | hora: ' .$inter[$k][0]. ' | intervalo: ' .$inter[$k][1] . '<br/>';
		}
		
	}
}
