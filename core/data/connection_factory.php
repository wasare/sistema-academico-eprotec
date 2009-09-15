<?php
require_once(dirname(__FILE__).'/../../lib/adodb5/adodb.inc.php');
/**
 * Connection factory
 * usando a bibliote ADODB
 */
class connection_factory {

    private $host;
    private $database;
    private $user;
    private $password;
    private $port;
    private $conn_persistent; //verifica se e conexao persistente
    private $debug;

    public $adodb; //Objeto de ADODB

    /**
     * Construtor da connection factory
     * @param vetor com parametros de configuracao
     * @param default true para conexao persistente
     */
    public function __construct($arr, $conn_persistent = TRUE, $debug = FALSE) {
        $this->host 	= $arr['host'];
        $this->user 	= $arr['user'];
        $this->password = $arr['password'];
        $this->database = $arr['database'];
        $this->port 	= $arr['port'];

        $this->conn_persistent = $conn_persistent;

        $this->debug = $debug;
        $this->Open();
    }

    /**
     * Abre a conexao com o banco de dados
     */
    public function Open() {

        $this->adodb = $GLOBALS['ADODB_SESS_CONN'];

        // reaproveita a conexao da sessao, caso exista uma e seja identica a conexao sendo criada
        if(is_object($this->adodb) && $this->adodb->host == $this->host && $this->adodb->database == $this->database && $this->adodb->user == $this->user && $this->adodb->password == $this->passowrd) {
            ADOdb_session::Persist($connectMode = $this->conn_persistent);
        }
        else {

            $conn_data = "host=$this->host dbname=$this->database user=$this->user password=$this->password";

            $this->adodb = ADONewConnection("postgres");

            if($this->conn_persistent) {
            // Conexao persistente
                if(!$this->adodb->PConnect($conn_data)) {
                    die('<h2 style="color: red">DB: Erro ao conectar com o banco de dados</h2>'.
                        '<div style="background-color: #ffffcc; padding:12px; margin:12px; font-size: 10px; width: 70%;">'.
                        $this->adodb->ErrorMsg() .'</div>');
                }
            }
            else {
            // Conexao nao persistente
                if(!$this->adodb->Connect($conn_data)) {
                    die('<h2 style="color: red">DB: Erro ao conectar com o banco de dados</h2>'.
                        '<div style="background-color: #ffffcc; padding:12px; margin:12px; font-size: 10px; width: 70%;">'.
                        $this->adodb->ErrorMsg() .'</div>');
                }
            }
        }

        $this->adodb->debug = $this->debug;
    }

    /**
     * Fecha conexao
     */
    public function Close() {
        $this->adodb->Close();
    }

    /**
     * Executa instrucoes no banco de dados
     * @param sql
     * @return ResultSet
     */
    public function Execute($sql) {
        if (!$ResultSet = $this->adodb->Execute($sql)) {
            die('<h2 style="color: red">DB: Erro ao executar consulta</h2>'.
                '<div style="background-color: #ffffcc; padding:12px; margin:12px; font-size: 10px; width: 70%;">'.
                $this->adodb->ErrorMsg() .'</div>');
        }
        return $ResultSet;
    }
}

?>
