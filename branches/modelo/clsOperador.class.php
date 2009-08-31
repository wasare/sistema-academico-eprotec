<?php

require_once("../controle/gtiConexao.class.php");
require_once("../controle/gtiValida.class.php");

class clsOperador
{
	
	// CAMPOS PRIVADOS-----------------------------------------
	private $codigo;
	private $login;
	private $senha;
	private $nome;
	private $perfil;
	
	//PROPRIEDADES----------------------------------------------
	
	//propriedade CODIGO
	public function SetCodigo($value)
	{
		$this->codigo= $value;
	}
	public function GetCodigo()
	{
		return $this->codigo;
	}
	
	//propriedade LOGIN
	public function SetLogin($value)
	{
		$this->login = $value;
	}
	public function GetLogin()
	{
		return $this->login;
	}
	
	//propriedade SENHA
	public function SetSenha($value)
	{
		$this->senha = $value;
	}
	public function GetSenha()
	{
		return $this->senha;
	}
	
	//propriedade NOME
	public function SetNome($value)
	{
		$this->nome = $value;
	}
	public function GetNome()
	{
		return $this->nome;
	}
	
	//propriedade PERFIL
	public function SetPerfil($value)
	{
		$this->perfil = $value;
	}
	public function GetPerfil()
	{
		return $this->perfil;
	}
	
    //MÉTODOS------------------------------------------------------
	
	public function clsOperador()
	{
		$this->codigo = "";
		$this->login = "";
		$this->senha = "";
		$this->nome = "";
		$this->perfil = "";
	}

	function Autentica($login, $senha)
    {
        $SQL = 'SELECT * FROM "prato"."tb_operador" WHERE "login_operador"=\''.trim($login).'\' AND 
        "senha_operador"=md5(\''.trim($senha).'\');';
        
        $con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();	
		
		$existe = false;

		if($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$this->codigo = $linha['cod_operador'];
				$this->login = $linha['login_operador'];
				$this->senha = $linha['senha_operador'];
				$this->nome = $linha['nome_operador'];
				$this->perfil = trim($linha['perfil_operador']);
			}
			$existe = true;
		}
		
		return $existe;
    }
    
    function SelecionaPorCodigo($codigo)
    {
        $SQL = 'SELECT "cod_operador","login_operador","senha_operador", "nome_operador", "perfil_operador" FROM "prato"."tb_operador" WHERE "cod_operador"=\''.trim($codigo).'\';';
        
        $con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();	

		if($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$this->codigo = $linha['cod_operador'];
				$this->login = $linha['login_operador'];
				$this->senha = $linha['senha_operador'];
				$this->nome = $linha['nome_operador'];
				$this->perfil = $linha['perfil_operador'];
			}
		}
		else
		{
			$this->clsUsuario();
		}
    }
    
	public function Excluir($codigo)
    {
    	$SQL = 'DELETE FROM "prato"."tb_operador" WHERE "cod_operador"='.$codigo.';';
		
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
    function Alterar()
    {
        $SQL = 'UPDATE "prato"."tb_operador" SET 
        "login_operador"=\''.$this->login.'\', 
        "senha_operador"=md5(\''.$this->senha.'\'),
        "nome_operador"=\''.$this->nome.'\',
		"perfil_operador"=\''.$this->perfil.'\'  
        WHERE 
        "cod_operador"='.$this->codigo.';';
        
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
		
    }
	
	public function Salvar()
    {
    	$SQL = 'INSERT INTO "prato"."tb_operador" ("login_operador","senha_operador","nome_operador","perfil_operador") VALUES (\''.$this->login.'\',md5(\''.$this->senha.'\'),\''.$this->nome.'\',\''.$this->perfil.'\');';
		
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
    }
	
	 public function ListaOperadorArray()
    {
    	$SQL = 'SELECT * FROM "prato"."tb_operador" order by "nome_operador", "perfil_operador";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
    	foreach($tbl as $chave => $linha)
		{
			$lin[0] = $linha['cod_operador'];			
			$lin[1] = '<![CDATA[<span>'.htmlentities($linha['nome_operador']).'</span> ]]>';
			
			$lin[2] = '<![CDATA[<span>Administrador</span> ]]>';
			if (trim($linha['perfil_operador']) == 'O')
			{
				$lin[2] = '<![CDATA[<span>Operador</span> ]]>';
			}
			
			$lin[3] = '0';

			if (trim($linha['habilitado_operador']) == 't')
			{
				$lin[3] = '1';
			}

			$arr[$cont++] = $lin;
		}
		
		return $arr;
    }
	
	function SetaAcesso($estado, $codigo)
    {
    	$status = 'f';
        if ($estado=='true')
        {
        	$status = 't';
        }
    	
    	$SQL = 'UPDATE prato.tb_operador SET habilitado_operador=\'' . $status . '\' WHERE "cod_operador"='.$codigo;
    	
        $con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();	
    }
    
    function PegaAcesso()
    {
    	$SQL = 'SELECT habilitado_operador FROM prato.tb_operador WHERE cod_operador='.$this->codigo;
    	
        $con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();	
		
		if($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				if (trim($linha['habilitado_operador']) == 't')
				{
					return true;
				}
				else if (trim($linha['habilitado_operador']) == 'f')
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
    }
}


?>