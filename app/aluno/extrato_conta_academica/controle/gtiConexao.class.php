<?php
//importa��o da ADODB
require_once('../../../../lib/adodb/adodb.inc.php');
require_once('../config.class.php');

/**
 *
 * Classe que efetua processos controlados em bancos de dados.
 *
 * ------------------------------------------------------------
 *
 *     CLASSE PARA CONEX�O COM BANCOS DE DADOS DIVERSOS
 *               Fa�ade sobre a classe ADODB5
 *        Propriedade da GTI - Cria��o:18/12/2007
 *            �ltima Modifica��o: 18/12/2007
*/

class gtiConexao
{
	//vari�veis privadas
    private $m_host;
	private $m_usuario;
	private $m_senha;
	private $m_esquema;
	private $m_driver;

	//objeto de conex�o
	private $m_conexao;

	//construtor
	function __construct()
    {
    	$this->gtiDefineConexao();
    	$m_conexao = false;
    }

 

	/**
	* -------------------------------------------------------------------------------
	* M�todos para definir a string de conex�o com base em um arquivo XML externo.
	*---------------------------------------------------------------------------------
	*PAR�METROS ($driver)
	* 1: $driver: String que espera o driver utilizado para conectar a um determinado
	* banco de dados. Sendo os principais: 'firebird', 'mysql', 'mssql', 'postgres'*/

	public function gtiDefineConexaoXML($arquivoXML,$driver)
	{
		$xml = simplexml_load_file($arquivoXML);

		$this->m_host = $xml->sessionfactory->host;
		$this->m_usuario = $xml->sessionfactory->usuario;
		$this->m_senha = $xml->sessionfactory->senha;
		$this->m_esquema = $xml->sessionfactory->banco;

		$this->m_driver = $driver;
	}

	/**
	* -------------------------------------------------------------------------------
	* M�todos que altera as propriedades do objeto conex�o utilizado pela classe.
	*---------------------------------------------------------------------------------
	*PAR�METROS ($host,$usuario,$senha,$banco,$driver)
	* 1: $host: Espera uma String setada com a defini��o do servidor do banco de dados,
	* por exemplo, LOCALHOST.
	* 2: $usuario: Espera uma String contendo o usu�rio do banco de dados utilizado.
	* 3: $senha: Espera uma String contendo a senha do usu�rio no banco de dados utilizado.
	* 4: $banco: Espera uma String contendo o nome da base de dados utilizada.
	* 5: $driver: String que espera o driver utilizado para conectar a um determinado
	* banco de dados. Sendo os principais: 'firebird', 'mysql', 'mssql', 'postgres'*/

	public function gtiDefineConexaoParam($host,$usuario,$senha,$banco,$driver)
	{
		
		$this->m_host = $host;
		$this->m_usuario = $usuario;
		$this->m_senha = $senha;
		$this->m_esquema = $banco;

		$this->m_driver = $driver;
	}

	/**
	* -------------------------------------------------------------------------------
	* M�todo para ser usado com a string montada diretamente no c�digo. Abra a classe
	* gtiConexao.class.php para editar essas informa��es.
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)*/

	public function gtiDefineConexao()
	{
		$conf = new clsConfig();
		$this->m_host = $conf->GetHost();
		$this->m_usuario = $conf->GetUsuario();
		$this->m_senha = $conf->GetSenha();
		$this->m_esquema = $conf->GetEsquema();

		$this->m_driver = $conf->GetDriver();
	}

	/**
	* -------------------------------------------------------------------------------
	* M�todo que cria a conex�o com o banco definido e em seguida se conecta a ele.
	* Para modificar a conex�o utilize o m�todo gtiDefineConexao().
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)*/

	public function gtiConecta()
	{
		$this->m_conexao = &ADONewConnection($this->m_driver);
		$this->m_conexao->PConnect($this->m_host, $this->m_usuario, $this->m_senha, $this->m_esquema);
	}

     /**
    * -------------------------------------------------------------------------------
    * M�todo que liga ou desliga o debug da conexão com o banco.
    *---------------------------------------------------------------------------------
    *PAR�METROS TRUE / FALSE
    */
/*
    public function gtiDebug($debug)
	{
		$this->m_conexao->debug = $debug;
	}
*/
	/**
	* -------------------------------------------------------------------------------
	* M�todo que desconecta do banco de dados utilizado.
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)*/

	public function gtiDesconecta()
	{
		$this->m_conexao->close();
	}

	/**
	* -------------------------------------------------------------------------------
	* M�todo que executa uma string SQL qualquer no banco de dados, sem retornar
	* nenhum tipo de valor. (INSERT, UPDATE e DELETE)
	*---------------------------------------------------------------------------------
	*PAR�METROS ($sql)
	*1: $sql: String SQL a ser executada.
	**/

	public function gtiExecutaSQL($sql)
	{
		$this->m_conexao->Execute($sql);
	}

	/**
	* -------------------------------------------------------------------------------
	* M�todo que insere automaticamente os valores em uma tabela. Insira os par�metros
	* determinados dentro de uma variavel utilizando-se do m�todo gtiNovaLinha(). Ao
	* adicionar os valores neste array passe-o por par�metro juntamente com o nome da
	* tabela. Os valores s�o inseridos conforme a ordem dos �ndices em correspond�ncia
	* aos campos.
	*---------------------------------------------------------------------------------
	*PAR�METROS ($nomeTabela, $linha)
	*1: $nomeTabela: String que espera o nome da tabela qual executara a consulta.
	*2: $linha: Array com os dados para serem inseridos.
	**/

	public function gtiInsereRegistro($nomeTabela, $linha)
	{
		$tbl = $this->m_conexao->Execute("SELECT * FROM " . $nomeTabela . ";");
		$SQL = $this->m_conexao->GetInsertSQL($tbl,$linha);
		$this->m_conexao->Execute($SQL);
	}

	/**
	* -------------------------------------------------------------------------------
	* M�todo que altera automaticamente os valores em uma tabela. Insira os par�metros
	* determinados dentro de uma variavel utilizando-se do m�todo gtiNovaLinha(). Ao
	* adicionar os valores neste array passe-o por par�metro juntamente com o nome da
	* tabela. Os valores s�o alterados conforme a ordem dos �ndices em correspond�ncia
	* aos campos. O campo chave indica qual registro deve ser alterado.
	*---------------------------------------------------------------------------------
	*PAR�METROS ($nomeTabela, $linha, $chave)
	*1: $nomeTabela: String que espera o nome da tabela qual executara a consulta.
	*2: $linha: Array com os dados para serem inseridos.
	*3: $chave: Chave que discrimina o registro a ser alterado (C�digo por exemplo).
	**/

	public function gtiAlteraRegistro($nomeTabela, $linha, $chave)
	{
		$tbl = $this->m_conexao->Execute("SELECT * FROM " . $nomeTabela . " WHERE".
		$chave . ";");
		$SQL = $this->m_conexao->GetUpdateSQL($tbl,$linha);
		$this->m_conexao->Execute($SQL);
	}

		/**
	* -------------------------------------------------------------------------------
	* Retorna um array preparado para inser��o de dados. Este array pode ser utilizado
	* com os metodos gtiAlteraRegistro e gtiInsereRegistro
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)
	**/
	public function gtiNovaLinha()
	{
		return array();
	}

		/**
	* -------------------------------------------------------------------------------
	* M�todo que com base em uma consulta SQL retorna uma tabela preechida com os d
	* dados pesquisados no banco.
	*---------------------------------------------------------------------------------
	*PAR�METROS ($sql)
	*1:$sql: String SQL a ser executada
	**/
	public function gtiPreencheTabela($sql)
	{
		$tbl = $this->m_conexao->Execute($sql);		
		return $tbl;
	}
	
	public function gtiPreencheArray($sql)
	{
		$tbl = $this->m_conexao->GetAll($sql);			 
		return $tbl;
	}

		/**
	* -------------------------------------------------------------------------------
	* Inicia um processo de transa��o na base de dados
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)
	**/
	public function gtiIniciarTransacao()
	{
		$this->m_conexao->BeginTrans();
	}

	/**
	* -------------------------------------------------------------------------------
	* Efetiva um processo de transa��o iniciado na base de dados (COMMIT)
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)
	**/
	public function gtiEfetivarTransacao()
	{
		$this->m_conexao->CommitTrans();
	}

		/**
	* -------------------------------------------------------------------------------
	* Desfaz um processo de transacao iniciado na base de dados. Cancela os processos
	* executados (ROLLBACK)
	*---------------------------------------------------------------------------------
	*PAR�METROS (nenhum)
	**/
	public function gtiDesfazerTransacao()
	{
		$this->m_conexao->RollbackTrans();
	}
}
?>
