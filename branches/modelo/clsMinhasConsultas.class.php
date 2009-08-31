<?php
require_once("../controle/gtiConexao.class.php");

class clsMinhasConsultas
{
	public function clsMinhasConsultas()
	{
	}
	
	//CAMPOS PRIVADOS------------------
	private $codigo;
	private $descricao;
	private $sql;
	
	//PROPRIEDADES
	//CODIGO---------------------------------
	public function GetCodigo()
	{
		return $this->codigo;
	}
	public function SetCodigo($value)
	{
		$this->codigo = $value;
	}
	
	//DESCRICAO---------------------------------
	public function GetDescricao()
	{
		return $this->descricao;
	}
	public function SetDescricao($value)
	{
		$this->descricao = $value;
	}
	
	//LIMITE---------------------------------
	public function GetSQL()
	{
		return $this->sql;
	}
	public function SetSQL($value)
	{
		$this->sql = $value;
	}
	
	//METODOS
	public function PegaConsultaPorCodigo($codigo)
	{
		$SQL = 'SELECT * FROM "prato"."tb_consulta" WHERE "cod_consulta"='.$codigo.';';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
    	foreach($tbl as $chave => $linha)
		{
			$this->descricao = $linha['nome_consulta'];
			$this->codigo = $linha['cod_consulta'];
			$this->sql = $linha['sql_consulta'];
		}
	}
	
    public function ListaConsultaArray()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_consulta" order by "nome_consulta";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
    	foreach($tbl as $chave => $linha)
		{
			$lin[0] = $linha['cod_consulta'];			
			$lin[1] = '<![CDATA[<span>'.htmlentities($linha['nome_consulta']).'</span> ]]>';
			$lin[2] = '<![CDATA[<span><a href="frmMinhasConsultas_exec.php?metodo=gerar&codigo='.htmlentities($linha['cod_consulta']).'">ver</a></span> ]]>';
			
			$arr[$cont++] = $lin;
		}
		
		return $arr;
    }
    
    public function Excluir($p_codigo)
    {
    	$SQL = 'DELETE FROM "prato"."tb_consulta" WHERE "cod_consulta"='.$p_codigo.';';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
		public function SalvarConsulta($nome, $stringSQL)
    {
    	$SQL = 'INSERT INTO prato.tb_consulta (nome_consulta,sql_consulta) VALUES (\''.$nome.'\',\''.str_replace("'", "\\'", $stringSQL).'\');';
		
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
}
?>