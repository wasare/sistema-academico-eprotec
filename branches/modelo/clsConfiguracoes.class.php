<?php

require_once("../controle/gtiConexao.class.php");

class clsConfiguracoes
{
	
	// CAMPOS PRIVADOS-----------------------------------------
	private $registroaut;
	private $permitevale;
	private $numvale;
	private $precovale;
	private $permitecortesia;
	private $numcortesia;
	private $refeicaopadrao;
	private $repetebolsista;
	
	//PROPRIEDADES----------------------------------------------
	
	//propriedade REGISTRO AUTOMATICO
	public function SetRegistroAut($value)
	{
		$this->registroaut= $value;
	}
	public function GetRegistroAut()
	{
		return $this->registroaut;
	}
	
	//propriedade PERMITE VALE
	public function SetPermiteVale($value)
	{
		$this->permitevale = $value;
	}
	public function GetPermiteVale()
	{
		return $this->permitevale;
	}
	
	//propriedade NUMERO DE VALES
	public function SetNumVale($value)
	{
		$this->numvale = $value;
	}
	public function GetNumVale()
	{
		return $this->numvale;
	}
	
	//propriedade PREÇO DO VALE
	public function SetPrecoVale($value)
	{
		$this->precovale = $value;
	}
	public function GetPrecoVale()
	{
		return $this->precovale;
	}
	
	//propriedade PERMITE CORTESIAS
	public function SetPermiteCortesia($value)
	{
		$this->permitecortesia = $value;
	}
	public function GetPermiteCortesia()
	{
		return $this->permitecortesia;
	}
	
	//propriedade NUMERO DE CORTESIAS
	public function SetNumCortesia($value)
	{
		$this->numcortesia = $value;
	}
	public function GetNumCortesia()
	{
		return $this->numcortesia;
	}
	
	//propriedade NUMERO DE CORTESIAS
	public function SetRefeicaoPadrao($value)
	{
		$this->refeicaopadrao = $value;
	}
	public function GetRefeicaoPadrao()
	{
		return $this->refeicaopadrao;
	}
	
	//propriedade REPETICAO DE BOLSISTA
	public function SetRepeteBolsista($value)
	{
		$this->repetebolsista = $value;
	}
	public function GetRepeteBolsista()
	{
		return $this->repetebolsista;
	}
	
    //MÉTODOS------------------------------------------------------
	
	public function clsConfiguracoes()
	{
		$this->registroaut = "";
		$this->permitevale = "";
		$this->numvale = "";
		$this->precovale = "";
		$this->permitecortesia = "";
		$this->numcortesia = "";
		$this->refeicaopadrao = "";
		$this->repetebolsista = "";
	}
    
    function Seleciona()
    {
        $SQL = 'SELECT * FROM prato.tb_parametro;';
        
        $con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();	

		if($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$this->registroaut = $linha['registro_automatico'];
				$this->permitevale = $linha['permite_vale'];
				$this->numvale = $linha['vales_disponiveis'];
				$this->precovale = $linha['preco_vale'];
				$this->permitecortesia = $linha['permite_cortesia'];
				$this->numcortesia = $linha['cortesias_disponiveis'];
				$this->refeicaopadrao = $linha['FKrefeicao_padrao'];
				$this->repetebolsista = $linha['repete_bolsista'];
				break;
			}
		}
		else
		{
			$this->clsConfiguracoes();
		}
    }
	
	function Altera($registroaut,$permitevale,$numvale, $permitecortesia,$numcortesia, $precovale, $repetebolsista)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		$SQL = 'UPDATE prato.tb_parametro SET registro_automatico=\''.$registroaut.'\', permite_vale=\''.$permitevale.'\', vales_disponiveis='.$numvale.', permite_cortesia=\''.$permitecortesia.'\', cortesias_disponiveis='.$numcortesia.', preco_vale='.$precovale.', repete_bolsista=\''.$repetebolsista.'\';';
		
		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
	
	function AlteraRefeicaoPadrao($codrefeicao)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		$SQL = 'UPDATE prato.tb_parametro SET "FKrefeicao_padrao"='.$codrefeicao.';';
		
		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
	
	public function DecrementaVale($quant)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		
		$novo = $this->numvale - $quant;
		
		$SQL = 'UPDATE prato.tb_parametro SET vales_disponiveis='.$novo.';';
		
		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
	
	public function DecrementaCortesia($quant)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		
		$novo = $this->numcortesia - $quant;
		
		$SQL = 'UPDATE prato.tb_parametro SET cortesias_disponiveis='.$novo.';';
		
		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
  
}


?>