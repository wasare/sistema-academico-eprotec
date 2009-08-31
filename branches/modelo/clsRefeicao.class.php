<?php
require_once("../controle/gtiConexao.class.php");

class clsRefeicao
{
	public function clsRefeicao()
	{
	}
	
	//CAMPOS PRIVADOS------------------
	private $codigo;
	private $descricao;
	private $custo;
	private $unidade;
	
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
	
	//CUSTO---------------------------------
	public function GetCusto()
	{
		return $this->custo;
	}
	public function SetCusto($value)
	{
		$this->custo = $value;
	}
	
	//UNIDADE---------------------------------
	public function GetUnidade()
	{
		return $this->unidade;
	}
	public function SetUnidade($value)
	{
		$this->unidade = $value;
	}
	
	//METODOS
	public function PegaRefeicaoPorCodigo($codigo)
	{
		$SQL = 'SELECT * FROM "prato"."tb_refeicao" WHERE "cod_refeicao"='.$codigo.';';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
    	foreach($tbl as $chave => $linha)
		{
			$this->descricao = $linha['des_refeicao'];
			$this->codigo = $linha['cod_refeicao'];
			$this->custo = $linha['custo_refeicao'];
			$this->unidade = $linha['unid_refeicao'];
		}
	}
	
	public function ListaRefeicao()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_refeicao" order by "des_refeicao";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$drop = "";
		
    	foreach($tbl as $chave => $linha)
		{
			 $id = $linha['cod_refeicao'];
			$nome = $linha['des_refeicao'];
			$drop .= '<option value="'.$id.'">'.htmlentities($nome).'</option>';
		}
		
		return $drop;
    }
    
    public function ListaRefeicaoArray()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_refeicao" order by "des_refeicao";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
    	foreach($tbl as $chave => $linha)
		{
			$lin[0] = $linha['cod_refeicao'];			
			$lin[1] = '<![CDATA[<span>'.htmlentities($linha['des_refeicao']).'</span> ]]>';
			$lin[2] = '<![CDATA[<span>'.htmlentities($linha['custo_refeicao']).'</span> ]]>';
			
			$lin[3] = '<![CDATA[<span>Unidade</span> ]]>';
			if (trim($linha['unid_refeicao']) == 'PG')
			{
				$lin[3] = '<![CDATA[<span>Peso (gramas)</span> ]]>';
			}
			
			$arr[$cont++] = $lin;
		}
		
		return $arr;
    }
    
    public function Excluir($p_codigo)
    {
    	$SQL = 'DELETE FROM "prato"."tb_refeicao" WHERE "cod_refeicao"='.$p_codigo.';';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
    
    public function Alterar()
    {
    	$SQL = 'UPDATE "prato"."tb_refeicao" SET "des_refeicao"=\''.$this->descricao.'\',"custo_refeicao"='.$this->custo.',"unid_refeicao"=\''.$this->unidade.'\'  WHERE "cod_refeicao"='.$this->codigo.';';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
    
    public function Salvar()
    {
    $SQL = 'INSERT INTO "prato"."tb_refeicao" ("des_refeicao","custo_refeicao","unid_refeicao") VALUES (\''.$this->descricao.'\','.$this->custo.',\''.$this->unidade.'\');';
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
	public function TodasRefeicoes()
	{
		$SQL = 'SELECT "cod_refeicao" FROM "prato"."tb_refeicao";';
		
		$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		return $tbl;		
	}
	
	public function PegaDescontoPorGrupo($codgrupo)
	{
		$SQL = 'SELECT "desconto_gr" FROM "prato"."tbrel_grupo_refeicao" WHERE "FKcod_refeicao"='.$this->codigo.' AND "FKcod_grupo"='.$codgrupo.';';

    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$desconto = '0';
		
    	foreach($tbl as $chave => $linha)
		{
			$desconto = $linha['desconto_gr'];
		}
		
		return $desconto;
	}
	
	public function ListaComboRefeicao()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_refeicao" order by "des_refeicao";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$drop = "";
		
    	foreach($tbl as $chave => $linha)
		{
			 $id = $linha['cod_refeicao'];
			$nome = $linha['des_refeicao'];
			$drop .= '<option value="'.htmlentities($nome).'">'.htmlentities($nome).'</option>';
		}
		
		return $drop;
    }
	
	
}
?>