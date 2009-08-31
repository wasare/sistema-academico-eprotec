<?php
require_once("../controle/gtiConexao.class.php");

class clsGrupo
{
	public function clsGrupo()
	{
	}
	
	//CAMPOS PRIVADOS------------------
	private $codigo;
	private $descricao;
	private $limite;
	
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
	public function GetLimite()
	{
		return $this->limite;
	}
	public function SetLimite($value)
	{
		$this->limite = $value;
	}
	
	//METODOS
	public function PegaGrupoPorCodigo($codigo)
	{
		$SQL = 'SELECT * FROM "prato"."tb_grupo" WHERE "cod_grupo"='.$codigo.';';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
    	foreach($tbl as $chave => $linha)
		{
			$this->descricao = $linha['des_grupo'];
			$this->codigo = $linha['cod_grupo'];
			$this->limite = $linha['limite_grupo'];
		}
	}
	
	public function ListaGrupo()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_grupo" order by "des_grupo";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$drop = "";
		
    	foreach($tbl as $chave => $linha)
		{
			 $id = $linha['cod_grupo'];
			$nome = $linha['des_grupo'];
			$drop .= '<option value="'.$id.'">'.htmlentities($nome).'</option>';
		}
		
		return $drop;
    }
    
    public function ListaGrupoArray()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_grupo" order by "des_grupo";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
    	foreach($tbl as $chave => $linha)
		{
			$lin[0] = $linha['cod_grupo'];			
			$lin[1] = '<![CDATA[<span>'.htmlentities($linha['des_grupo']).'</span> ]]>';
			$lin[2] = '<![CDATA[<span>'.htmlentities($linha['limite_grupo']).'</span> ]]>';
			$lin[3] = '<![CDATA[<span><a href="frmDesconto.php?codgrupo='.htmlentities($linha['cod_grupo']).'">descontos</a></span> ]]>';
			
			$arr[$cont++] = $lin;
		}
		
		return $arr;
    }
    
    public function Excluir($p_codigo)
    {
    	$SQL = 'DELETE FROM "prato"."tb_grupo" WHERE "cod_grupo"='.$p_codigo.';';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
    
    public function Alterar()
    {
    	$SQL = 'UPDATE "prato"."tb_grupo" SET "des_grupo"=\''.$this->descricao.'\',"limite_grupo"='.$this->limite.'  WHERE "cod_grupo"='.$this->codigo.';';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
    
    public function Salvar()
    {
    	$SQL = 'INSERT INTO "prato"."tb_grupo" ("des_grupo","limite_grupo") VALUES (\''.$this->descricao.'\',\''.$this->limite.'\');';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
}
?>