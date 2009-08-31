<?php
require_once("../controle/gtiConexao.class.php");
require_once("clsRefeicao.class.php");
require_once("clsConfiguracoes.class.php");
require_once("clsGrupo.class.php");

class clsUsuario
{	
	//CAMPOS PRIVADOS------------------
	private $codigo;
	private $nome;
	private $datanasc;
	private $habilitado;
	private $motivo;
	private $nomegrupo;
	private $codgrupo;
	private $saldo;
	private $limite;
	private $datacad;
	
	public function clsUsuario()
	{
		$this->codigo = "";
		$this->nome = "";
		$this->datanasc = "";
		$this->habilitado = "";
		$this->motivo = "";
		$this->nomegrupo = "";
		$this->codgrupo = "";
		$this->saldo = "";
		$this->limite = "";
		$this->datacad = "";
	}
	
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
	
	//NOME---------------------------------
	public function GetNome()
	{
		return $this->nome;
	}
	public function SetNome($value)
	{
		$this->nome = $value;
	}
	
	//DATA NASCIMENTO---------------------------------
	public function GetDataNasc()
	{
		return $this->datanasc;
	}
	public function SetDataNasc($value)
	{
		$this->datanasc = $value;
	}
	
	//HABILITADO---------------------------------
	public function GetHabilitado()
	{
		return $this->habilitado;
	}
	public function SetHabilitado($value)
	{
		$this->habilitado = $value;
	}
	//MOTIVO---------------------------------
	public function GetMotivo()
	{
		return $this->motivo;
	}
	public function SetMotivo($value)
	{
		$this->motivo = $value;
	}
	
	//NOME DO GRUPO---------------------------------
	public function GetNomeGrupo()
	{
		return $this->nomegrupo;
	}
	public function SetNomeGrupo($value)
	{
		$this->nomegrupo = $value;
	}
	
	//COD DO GRUPO---------------------------------
	public function GetCodGrupo()
	{
		return $this->codgrupo;
	}
	public function SetCodGrupo($value)
	{
		$this->codgrupo = $value;
	}
	
	//SALDO---------------------------------
	public function GetSaldo()
	{
		return $this->saldo;
	}
	public function SetSaldo($value)
	{
		$this->saldo = $value;
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
	
	//DATA DE CADASTRO---------------------------------
	public function GetDataCad()
	{
		return $this->datacad;
	}
	public function SetDataCad($value)
	{
		$this->datacad = $value;
	}
	
	//METODOS
	public function PegaUsuarioSagu($codigo)
	{	
		$SQL = 'SELECT id, nome FROM public.pessoas WHERE id='.$codigo.';'; 
		
		$con = new gtiConexao();
		$con->gtiConecta();	
		$tbl = $con->gtiPreencheTabela($SQL);		
		$con->gtiDesconecta();

		if ($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$this->codigo = $linha['id'];
				$this->nome = $linha['nome'];
			}
		}
	}
	
	public function PegaUsuarioPorCodigo($codigo)
	{
		$con = new gtiConexao();
		$con->gtiConecta();
		
		$SQL = 'SELECT * FROM prato.vw_usuario WHERE codigo='.$codigo.';'; 
		
		$tbl = $con->gtiPreencheTabela($SQL);	
		
		$con->gtiDesconecta();

		if ($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$this->codigo = $linha['codigo'];
				$this->nome = $linha['nome'];
				$this->datanasc = $linha['datanasc'];
				$this->habilitado = $linha['habilitado'];
				$this->motivo = $linha['motivo'];
				$this->codgrupo = $linha['codgrupo'];
				$this->nomegrupo = $linha['nomegrupo'];
				$this->limite = $linha['limite'];
				$this->saldo = $linha['saldo'];
				$this->datacad = $linha['datacad'];
			}
		}
		else
		{
			 $this->clsUsuario();
		}
		
		
	}
	
	public function ListaUsuario()
    {
    	$SQL = 'SELECT * FROM "prato"."vw_usuario" order by "nome";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$drop = "";
		
    	foreach($tbl as $chave => $linha)
		{
			$id = $linha['codigo'];
			$nome = $linha['nome'];
			$drop .= '<option value="'.$id.'">'.htmlentities($nome).'</option>';
		}
		
		return $drop;
    }
    
    public function ListaUsuarioArray()
    {
    	$SQL = 'SELECT * FROM "prato"."vw_usuario" order by "nome";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
    	foreach($tbl as $chave => $linha)
		{
			$lin[0] = $linha['codigo'];			
			$lin[1] = '<![CDATA[<span>'.htmlentities($linha['nome']).'</span> ]]>';
			
			$arr[$cont++] = $lin;
		}
		
		return $arr;
    }
	
	public function ListaUsuarioSaguArray()
    {
    	$SQLx = 'SELECT id, nome FROM "public"."pessoas" ORDER BY nome;';
    	
    	$conx = new gtiConexao();
		$conx->gtiConecta();
		$tblx = $conx->gtiPreencheTabela($SQLx);
		$conx->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
    	foreach($tblx as $chavex => $linhax)
		{
			//codigo e nome
			$lin[0] = $linhax['id'];			
			$lin[1] = '<![CDATA[<span>'.htmlentities($linhax['nome']).'</span> ]]>';
			
			$this->PegaUsuarioPorCodigo($linhax['id']);
			
			if (trim($this->codigo) != "")
			{
				//esta registrado
				//grupo e saldo
				$lin[2] = '<![CDATA[<span>'.htmlentities($this->GetNomeGrupo()).'</span> ]]>';
				$lin[3] = '<![CDATA[<span>'.htmlentities($this->GetSaldo()).'</span> ]]>';
				//links
				if ($this->habilitado == 't')
				{
					$lin[4] = '<![CDATA[<span><a href="frmAcesso.php?codigo='.$linhax['id'].'>Habilitado</a></span> ]]>';
				}
				else
				{
					$lin[4] = '<![CDATA[<span><a style="color: #0000FF;" href="frmAcesso.php?codigo='.$linhax['id'].'>Bloqueado</a></span> ]]>';
				}
				
				$lin[5] = '<![CDATA[<span><a href="frmRegistro.php?codigo='.$linhax['id'].'>Registrado</a></span> ]]>';
				
				
			}
			else
			{
				//nao esta registrado
				//grupo e saldo
				$lin[2] = '<![CDATA[<span>Inativo</span> ]]>';
				$lin[3] = '<![CDATA[<span>Inativo</span> ]]>';
				//links
				$lin[4] = '<![CDATA[<span>Inativo</span> ]]>';
				$lin[5] = '<![CDATA[<span><a style="color: #0000FF;" href="frmRegistro.php?codigo='.$linhax['id'].'>Registrar</a></span>]]>';
				
			}
			
			$arr[$cont++] = $lin;
		}
		
		return $arr;
    }
    
	public function AlteraRegistro($codigo, $datacad, $grupo)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		
		$SQL = 'UPDATE prato.tb_info_usuario SET datacad_usuario=\''.$datacad.'\',"FKcod_grupo"='.$grupo.' WHERE "FKid_usuario"='.$codigo.';';
		
		$con->gtiExecutaSQL($SQL);		
		$con->gtiDesconecta();	
	}
	
	public function Registra($codigo,$datacad,$grupo,$credito)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		
		//insere no prato
		$SQL = 'INSERT INTO prato.tb_info_usuario ("FKid_usuario","FKcod_grupo",datacad_usuario) VALUES ('.$codigo.','.$grupo.',\''.$datacad.'\');';
		$con->gtiExecutaSQL($SQL);		
		
		//insere no financeiro
		$SQL = '
		set search_path = financeiro; INSERT INTO tb_transacao ("FKid_usuario","FKcod_operacao",valor_transacao, "FKcod_operador") VALUES ('.$codigo.',1,'.$credito.',2);';

		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
	
	public function AlteraAcesso($codigo,$acesso,$motivo)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		$SQL = 'UPDATE prato.tb_info_usuario SET habilitado_usuario=\''.$acesso.'\', motivo_usuario=\''.$motivo.'\' WHERE "FKid_usuario"='.$codigo.';';
		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
	
	public function CalculaPreco($quantidade)
	{
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		$ref = new clsRefeicao();
		$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
		
		//calculo do peso bruto
		$precobruto = ($quantidade) * $ref->GetCusto();
		
		//calculo do preco com desconto
		$desc = $ref->PegaDescontoPorGrupo($this->codgrupo);
		$valordesc = ($precobruto * $desc)/100;
		
		//calcula o preco real
		$precoreal = $precobruto - $valordesc;
		
		return $precoreal;
	}
	
	public function UsandoSiape($codigo)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
	
		$SQL = 'SELECT ref_pessoa FROM public.funcionario WHERE siape=\''.$codigo.'\';';
	
		$tbl = $con->gtiPreencheTabela($SQL);	
		
		$codigo = "";
		if ($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$codigo = $linha['ref_pessoa'];
			}
		}
		
		$con->gtiDesconecta();		
		return $codigo;
	}
	
	public function RepeteRefeicao()
	{
		
		$valor = 'f';

		$data = date('Y-m-d');
		$hora = date('H:i:s');
		
		$con = new gtiConexao();
		$con->gtiConecta();		
	
		$SQL = 'SELECT refeicao_historico, data_historico, hora_historico FROM prato.tb_historico 
WHERE cod_historico=(SELECT MAX(cod_historico) FROM prato.tb_historico WHERE "FKid_usuario"=\''.$this->codigo.'\');';


		$tbl = $con->gtiPreencheTabela($SQL);	
		
		$refeicaoh = "";
		$datah = "";
		$horah = "";
		
		if ($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$refeicaoh = $linha['refeicao_historico'];
				$datah = $linha['data_historico'];
				$horah = $linha['hora_historico'];
			}
		}
		
		$tempo =  strtotime($hora) - strtotime($horah);
		
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		$ref = new clsRefeicao();
		$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
		
		if (($data == $datah) && ($ref->GetDescricao() == $refeicaoh) && ($tempo<5))
		{
			$valor = 't';
		}
		
		$con->gtiDesconecta();

		
		return $valor;
	}
	
	public function RepeteBolsista()
	{
		
		$valor = 'f';

		if ($this->codgrupo == '4' || $this->codgrupo == '5' || $this->codgrupo == '6')
		{
			$data = date('Y-m-d');
			
			$con = new gtiConexao();
			$con->gtiConecta();		
		
			$SQL = 'SELECT refeicao_historico, data_historico FROM prato.tb_historico 
WHERE cod_historico=(SELECT MAX(cod_historico) FROM prato.tb_historico WHERE "FKid_usuario"='.$this->codigo.');';
		
			$tbl = $con->gtiPreencheTabela($SQL);	
			
			$refeicaoh = "";
			$datah = "";
			if ($tbl->RecordCount()>0)
			{
				foreach($tbl as $chave => $linha)
				{
					$refeicaoh = $linha['refeicao_historico'];
					$datah = $linha['data_historico'];
				}
			}
			
			$pam = new clsConfiguracoes();
			$pam->Seleciona();
			
			$ref = new clsRefeicao();
			$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
			if (($data == $datah) && ($ref->GetDescricao() == $refeicaoh))
			{
				$valor = 't';
			}
			
			$con->gtiDesconecta();
		}
		
		return $valor;
	}
	
	public function RegistroAutomatico($codigo, $siape, $peso, $operador)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		
		$grupo = '';
		if (trim($siape) == 't')
		{
			//ele é funcionário
			$grupo = '2';
		}
		else
		{
			//ele é aluno
			$grupo = '1';
		}
		
		$this->codgrupo = $grupo;
		$valor = $this->CalculaPreco($peso);
		
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		$ref = new clsRefeicao();
		$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
		$desconto = $ref->PegaDescontoPorGrupo($this->codgrupo);
		
		//insere registro do usuario no prato
		$SQL = 'INSERT INTO prato.tb_info_usuario ("FKid_usuario","FKcod_grupo",datacad_usuario) VALUES ('.$codigo.','.$grupo.',now());';
		$con->gtiExecutaSQL($SQL);	
		
		//insere no tabela historico o registro da alimentacao
		$SQL = 
		'INSERT INTO prato.tb_historico 
		(refeicao_historico,
		quant_historico,
		unid_historico,
		custo_historico,
		desconto_historico,
		preco_historico,
		data_historico,
		hora_historico,
		"FKcod_operador",
		"FKid_usuario")
		VALUES
		(\''.$ref->GetDescricao().'\',
		'.$peso.',
		\''.$ref->GetUnidade().'\',
		'.$ref->GetCusto().',
		'.$desconto.',
		'.$valor.',
		\''.date("d/m/Y").'\',
		\''.date("H:i:s").'\',
		\''.$operador.'\', 
		\''.$codigo.'\'
		);';

		$con->gtiExecutaSQL($SQL);		
		
		//insere no financeiro(2 é o código do sistema financeiro na tabela operadores/ 2 e a operacao para debito alimentacao)
		$SQL = 'set search_path = financeiro; INSERT INTO tb_transacao ("FKid_usuario","FKcod_operacao",valor_transacao, "FKcod_operador") VALUES ('.$codigo.',2,'.$valor.',2);';
		$con->gtiExecutaSQL($SQL);				
		
		$con->gtiDesconecta();
	}
	
	public function RegistraVenda($peso, $operador)
	{
		$con = new gtiConexao();
		$con->gtiConecta();	
		
		$valor = $this->CalculaPreco($peso);
		
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		$ref = new clsRefeicao();
		$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
		$desconto = $ref->PegaDescontoPorGrupo($this->codgrupo);
		
		$SQL = 
		'INSERT INTO prato.tb_historico 
		(refeicao_historico,
		quant_historico,
		unid_historico,
		custo_historico,
		desconto_historico,
		preco_historico,
		data_historico,
		hora_historico,
		"FKcod_operador",
		"FKid_usuario")
		VALUES
		(\''.$ref->GetDescricao().'\',
		'.$peso.',
		\''.$ref->GetUnidade().'\',
		'.$ref->GetCusto().',
		'.$desconto.',
		'.$valor.',
		\''.date("d/m/Y").'\',
		\''.date("H:i:s").'\',
		\''.$operador.'\', 
		\''.$this->codigo.'\'
		);';

		$con->gtiExecutaSQL($SQL);		
		
		//insere no financeiro (2 é o código do sistema financeiro na tabela operadores/ 2 e a operacao para debito alimentacao)
		$SQL = 'set search_path = financeiro; INSERT INTO tb_transacao ("FKid_usuario","FKcod_operacao",valor_transacao, "FKcod_operador") VALUES ('.$this->codigo.',2,'.$valor.',2);';
		$con->gtiExecutaSQL($SQL);				
		
		$con->gtiDesconecta();
		
	}
	
	public function RegistraVendaVale($peso, $operador)
	{
		$con = new gtiConexao();
		$con->gtiConecta();	
		
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		
		if ($pam->GetPermiteVale() == 't' && $pam->GetNumVale() >0)
		{		
			$ref = new clsRefeicao();
			$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
			
			$SQL = 
			'INSERT INTO prato.tb_historico 
			(refeicao_historico,
			quant_historico,
			unid_historico,
			custo_historico,
			desconto_historico,
			preco_historico,
			data_historico,
			hora_historico,
			"FKcod_operador",
			"FKid_usuario")
			VALUES
			(\''.$ref->GetDescricao().'\',
			'.$peso.',
			\''.$ref->GetUnidade().'\',
			'.$pam->GetPrecoVale().',
			0,
			'.$pam->GetPrecoVale().',
			\''.date("d/m/Y").'\',
			\''.date("H:i:s").'\',
			\''.$operador.'\', 
			\'vale\'
			);';
	
			$con->gtiExecutaSQL($SQL);		
			
			//como a venda é por vale ela não é cadastrada no financeiro pois não remete a uma conta de uma pessoa física			
			
			//cadastra a venda de um vale (recupera um vale)
			$pam->DecrementaVale(1);
			
			$con->gtiDesconecta();
			
			return 't';
		}
		else
		{
			return 'f';
		}
	}
	
	public function RegistraVendaCortesia($peso, $operador)
	{
		$con = new gtiConexao();
		$con->gtiConecta();	
		
		$pam = new clsConfiguracoes();
		$pam->Seleciona();
		
		
		if ($pam->GetPermiteCortesia() == 't' && $pam->GetNumCortesia() >0)
		{		
			$ref = new clsRefeicao();
			$ref->PegaRefeicaoPorCodigo($pam->GetRefeicaoPadrao());
			
			$SQL = 
			'INSERT INTO prato.tb_historico 
			(refeicao_historico,
			quant_historico,
			unid_historico,
			custo_historico,
			desconto_historico,
			preco_historico,
			data_historico,
			hora_historico,
			"FKcod_operador",
			"FKid_usuario")
			VALUES
			(\''.$ref->GetDescricao().'\',
			'.$peso.',
			\''.$ref->GetUnidade().'\',
			0,
			0,
			0,
			\''.date("d/m/Y").'\',
			\''.date("H:i:s").'\',
			\''.$operador.'\', 
			\'cortesia\'
			);';
	
			$con->gtiExecutaSQL($SQL);		
			
			//como a venda é por cortesia ela não é cadastrada no financeiro pois não remete a uma conta de uma pessoa física			
			
			//cadastra a venda de um vale (recupera um vale)
			$pam->DecrementaCortesia(1);
			
			$con->gtiDesconecta();
			
			return 't';
		}
		else
		{
			return 'f';
		}
	}
	
	public function RegistraMarmitex($codigo, $quantidade, $custo, $preco)
	{
		$con = new gtiConexao();
		$con->gtiConecta();		
		$SQL = 'INSERT INTO prato.tb_historico (refeicao_historico, quant_historico, unid_historico, custo_historico, desconto_historico, preco_historico, data_historico, hora_historico, "FKcod_operador", "FKid_usuario") VALUES (\'Marmitex\','.$quantidade.',\'UN\','.$custo.',0,'.$preco.',now(),now(),\''.$codigo.'\',\'marmitex\');';

		$con->gtiExecutaSQL($SQL);	
		
		$con->gtiDesconecta();
	}
	
	
	/* METODO PARA FILTRAR O GRID
	 * @UTHOR: SILAS ANTÔNIO CEREDA DA SILVA
	 * DATA: 14/07/2009
	*/ 
	public function FiltraUsuarioSaguArray($valor)
    {
    	$SQLx = 'SELECT id, nome FROM "public"."pessoas" WHERE
		lower(nome) LIKE \'%'.$valor.'%\' ORDER BY nome;';
    	
    	$conx = new gtiConexao();
		$conx->gtiConecta();
		$tblx = $conx->gtiPreencheTabela($SQLx);
		$conx->gtiDesconecta();
		
		$arr = "";
		$cont = -1;
		
		if ($tblx->RecordCount()!=0)
		{
			foreach($tblx as $chavex => $linhax)
			{
				//codigo e nome
				$lin[0] = $linhax['id'];			
				$lin[1] = '<![CDATA[<span>'.htmlentities($linhax['nome']).'</span> ]]>';
				
				$this->PegaUsuarioPorCodigo($linhax['id']);
				
				if (trim($this->codigo) != "")
				{
					//esta registrado
					//grupo e saldo
					$lin[2] = '<![CDATA[<span>'.htmlentities($this->GetNomeGrupo()).'</span> ]]>';
					$lin[3] = '<![CDATA[<span>'.htmlentities($this->GetSaldo()).'</span> ]]>';
					//links
					if ($this->habilitado == 't')
					{
						$lin[4] = '<![CDATA[<span><a href="frmAcesso.php?codigo='.$linhax['id'].'>Habilitado</a></span> ]]>';
					}
					else
					{
						$lin[4] = '<![CDATA[<span><a style="color: #0000FF;" href="frmAcesso.php?codigo='.$linhax['id'].'>Bloqueado</a></span> ]]>';
					}
					
					$lin[5] = '<![CDATA[<span><a href="frmRegistro.php?codigo='.$linhax['id'].'>Registrado</a></span> ]]>';
					
					
				}
				else
				{
					//nao esta registrado
					//grupo e saldo
					$lin[2] = '<![CDATA[<span>Inativo</span> ]]>';
					$lin[3] = '<![CDATA[<span>Inativo</span> ]]>';
					//links
					$lin[4] = '<![CDATA[<span>Inativo</span> ]]>';
					$lin[5] = '<![CDATA[<span><a style="color: #0000FF;" href="frmRegistro.php?codigo='.$linhax['id'].'>Registrar</a></span>]]>';
					
				}
				
				$arr[$cont++] = $lin;
			}
		}
		else
		{
			$lin[0] = '';			
			$lin[1] = '';
			$lin[2] = '';
			$lin[3] = '';	
			$lin[4] = '';
			$lin[5] = '';
			
			$arr[$cont++] = $lin;
		}
		return $arr;
    }
	
}
?>