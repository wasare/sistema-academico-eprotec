<?php
require dirname(__FILE__).'/adodb/adodb.inc.php';
require dirname(__FILE__).'/../configuracao.php';

/**
 * CLASSE CARIMBO PARA ASSINATURA
 * @author santiago
 * @since 2009-07-21
 */
class carimbo{

	private $host;
	private $database;
	private $user;
	private $password;
	
	/**
	 * Construtor com parametros de conexao
	 * @param sevidor, usuario, senha, banco de dados
	 * @return string
	 */
	function __construct($host,$user,$password,$database){
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}
	
	/**
	 * Funcao que retorna os carimbos
	 * @return string
	 */
	function listar(){
		
		$conn = ADONewConnection("postgres");//NewADOConnection("postgres");
		$conn->Connect("host=$this->host dbname=$this->database user=$this->user password=$this->password");
		
		$sql = "
		SELECT 
			id, nome, texto, ref_setor
		FROM 
			carimbos 
		ORDER BY 1 DESC;";

		$RsCarimbo = $conn->Execute($sql);

		if (!$RsCarimbo){
			print $conn->ErrorMsg();
			die();
		}
		//<select multiple >
		$resp = '<select size="5" name="carimbo">
                 	<option value="" selected> Sem carimbo </option>';
		
		while(!$RsCarimbo->EOF){
			
			//$resp .= '<input type="radio" name="carimbo" id="carimbo" value="'.$RsCarimbo->fields[0].'" />';
			$resp .= '<option value="'.$RsCarimbo->fields[0].'"> ';
			$resp .= $RsCarimbo->fields[1];
			$resp .= ' </option>';
			
			$RsCarimbo->MoveNext();
		}
		$resp .= '</select>';
		
		$conn->Close();

		return $resp;
	}
	
	/**
	 * Funcao que retorna o nome do carimbo de acordo com o codigo
	 * @param codigo do carimbo
	 * @return string
	 */
	function get_nome($id){
		
		if($id == null){
			return null;
		}else{

			$conn = ADONewConnection("postgres");//NewADOConnection("postgres");
			$conn->Connect("host=$this->host dbname=$this->database user=$this->user password=$this->password");
		
			$sqlCarimbo = "
			SELECT 
				id, nome, texto, ref_setor
			FROM 
				carimbos 
			WHERE	id = $id;";
			
			$RsCarimbo = $conn->Execute($sqlCarimbo);
			
			if (!$RsCarimbo){
				print $conn->ErrorMsg();
				die();
			}
			$resp = $RsCarimbo->fields[1];
			
			$conn->Close();
			
			return $resp;
		}
	}
	
	/**
	 * Funcao que retorna a funcao do carimbo de acordo com o codigo
	 * @param codigo do carimbo
	 * @return string
	 */
	function get_funcao($id){

		if($id == null){
			return null;
		}else{

			$conn = ADONewConnection("postgres");//NewADOConnection("postgres");
			$conn->Connect("host=$this->host dbname=$this->database user=$this->user password=$this->password");
		
			$sqlCarimbo = "
			SELECT 
				id, nome, texto, ref_setor
			FROM 
				carimbos 
			WHERE	id = $id;";
			
			$RsCarimbo = $conn->Execute($sqlCarimbo);
			
			if (!$RsCarimbo){
				print $conn->ErrorMsg();
				die();
			}
			$resp = $RsCarimbo->fields[2];
			
			$conn->Close();
			
			return $resp;
		}
	}
}

//$carimbo = new carimbo($host,$user,$password,$database);
//echo $carimbo->listar();
//echo $carimbo->get_nome($id);
//echo $carimbo->get_funcao($id);