<?php
require_once("../controle/gtiConexao.class.php");

class clsUsuario
{	
	//CAMPOS PRIVADOS------------------
	private $codigo;
	private $nome;
	private $datanasc;
	private $saldo;
	private $imagem;
	
	public function clsUsuario()
	{
		$this->codigo = "";
		$this->nome = "";
		$this->datanasc = "";
		$this->saldo = "";
		$this->imagem = "";

		$config = new clsConfig();
		$this->imagem = @file_get_contents('imagens/' . $config->GetImagemSemFoto());
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
	
	//SALDO---------------------------------
	public function GetSaldo()
	{
		return $this->saldo;
	}
	public function SetSaldo($value)
	{
		$this->saldo = $value;
	}
	
	//IMAGEM---------------------------------
	public function GetImagem()
	{
		return $this->imagem;
	}
	public function SetImagem($value)
	{
		$this->imagem = $value;
	}
	
	//METODOS
	public function PegaUsuario($codigo)
	{	
		if (trim($codigo) == '')
		{
			$codigo = 0;
		}
		
		$SQL = 'SELECT id, nome FROM public.pessoas WHERE id='.$codigo.';'; 
		
		$con = new gtiConexao();
        $con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);		

		if ($tbl->RecordCount()>0)
		{
			foreach($tbl as $chave => $linha)
			{
				$this->codigo = $linha['id'];
				$this->nome = $linha['nome'];
				$this->datanasc = $linha['dt_nascimento'];
				
				//pega saldo no financeiro
				$SQLx = 'SELECT saldo_usuario FROM financeiro.tb_saldo WHERE "FKid_usuario"='.$linha['id'].';'; 

				$tblx = $con->gtiPreencheTabela($SQLx);
				
				if ($tblx->RecordCount()>0)
				{
					foreach($tblx as $chavex => $linhax)
					{
						$this->saldo = $linhax['saldo_usuario'];
					}
				}
				else
				{
					$this->saldo = 0;
				}
				
				//pega foto no sagu
				$SQLi = 'SELECT foto FROM public.pessoas_fotos WHERE ref_pessoa='.$linha['id'].';'; 
				$tbli = $con->gtiPreencheTabela($SQLi);
				
				if ($tbli->RecordCount()>0)
				{
					foreach($tbli as $chavei => $linhai)
					{
						if ((isset($linhai['foto'])) && $linhai['foto'] <> "null" && $linhai['foto'] <> "")
						{
							$this->imagem = $linhai['foto'];
						}
					}
				}
			}
		}
		
		$con->gtiDesconecta();
	}
	
	public function ListaUsuario()
    {
    	$SQL = 'SELECT id, nome FROM "public"."pessoas" order by "nome";';
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$drop = "";
		
    	foreach($tbl as $chave => $linha)
		{
			$id = $linha['id'];
			$nome = $linha['nome'];
			$drop .= '<option value="'.$id.'">'.htmlentities($nome).'</option>';
		}
		
		return $drop;
    }
	
	public function ListaUsuarioCodNome($codigo, $nome)
    {
		if ((trim($codigo) != '') && (trim($nome) != ''))
		{
			$SQL = 'SELECT id, nome FROM "public"."pessoas" WHERE id ilike \''.$codigo.'%\' AND nome ilike \''.$nome.'%\' order by "nome";';
		}
		else if ((trim($codigo) != '') && (trim($nome) == ''))
		{
			$SQL = 'SELECT id, nome FROM "public"."pessoas" WHERE id ilike \''.$codigo.'%\' order by "nome";';
		}
		else if ((trim($codigo) == '') && (trim($nome) != ''))
		{
			$SQL = 'SELECT id, nome FROM "public"."pessoas" WHERE nome ilike \''.$nome.'%\' order by "nome";';
		}
		else
		{
			$SQL = 'SELECT id, nome FROM "public"."pessoas" order by "nome";';
		}
		
    	
    	
    	$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$drop = "";
		
    	foreach($tbl as $chave => $linha)
		{
			$id = $linha['id'];
			$nome = $linha['nome'];
			$drop .= '<option value="'.$id.'">'.htmlentities($nome).'</option>';
		}
		
		return $drop;
    }
	
	public function ExecutarOperacao($codigo,$operacao,$valor,$codoperador)
	{
		$valor = str_replace(",", ".", $valor);
		$SQL = 'set search_path=financeiro; INSERT INTO tb_transacao ("FKid_usuario","FKcod_operacao",valor_transacao,"FKcod_operador") VALUES ('.$codigo.','.$operacao.','.$valor.','.$codoperador.');';
		
    	$con = new gtiConexao();
		$con->gtiConecta();
		$con->gtiExecutaSQL($SQL);
		$con->gtiDesconecta();
	}
	
	public function GeraExtrato($codigo, $datainicial, $datafinal)
	{
	
		$SQL = 'SELECT T.datahora_transacao, O.des_operacao, O.tipo_operacao, T.valor_transacao FROM financeiro.tb_transacao as T, financeiro.tb_operacao as O WHERE T."FKid_usuario"='.$codigo.' AND T.datahora_transacao<=\''.$datafinal.'\' AND T.datahora_transacao>=\''.$datainicial.'\' AND T."FKcod_operacao" = O.cod_operacao;';
		$con = new gtiConexao();
		$con->gtiConecta();
		$tbl = $con->gtiPreencheTabela($SQL);
		$con->gtiDesconecta();
		
		$tabela = '';
		
		foreach($tbl as $chave => $linha)
		{
			$lin = '<tr>';
			$lin .= '<td><div class="style2" align="left">'.substr($linha['datahora_transacao'],0,19).'</div></td>';
			$lin .= '<td><div class="style2" align="left">'.$linha['des_operacao'].'</div></td>';
			$lin .= '<td><div class="style2" align="left">'.$linha['tipo_operacao'].'</div></td>';
			$lin .='<td><div class="style2" align="left">'.number_format($linha['valor_transacao'], 2, ',', '').'</div></td>';
			$lin .= '</tr>';
			
			$tabela = $tabela . $lin;
		}
		
		return $tabela;
	}
	
}
?>
