<?php
/**
 * Classe de autenticação de usuário
 * @filesource
 * @copyright 2009 IFMG Campus Bambui
 * @author santiago
 * @version 1
 * @package sistema-academico
 * @subpackage sistema-academico.core.login
 */
class auth {


    public function __construct() {
    //
    }

    /**
     * Autentica usuario no SA
     * @param usuario
     * @param senha
     * @return Sessao de usuario logado
     */
    public function login($auth_user, $auth_pwd) {

        $logado = '';//recebe variavel de sessao do usuario

        if(empty($logado)) {

            if(empty($auth_user) OR empty($auth_pwd)) {
                return 'Usuário e/ou senha não preenchido!';
            }else {

                require_once('../../configs/configuracao.php');
                require_once('../data/connection_factory.php');

                $conn = new connection_factory($param_conn);

                $sql = "SELECT COUNT(*) 
                        FROM usuario
                        WHERE
                            nome = '$auth_user' AND
                            senha = '$auth_pwd'; ";
                
                //retorna um unico valor - uma variavel simples
                if($conn->adodb->getOne($sql) == 1) {

                    $this->set_user($auth_user);
                    
                    //redirecionar para a pagina inicio.php
                    
                    return 'Usuário '.$auth_user.' autenticado...';

                }else {
                    return 'Usuário e/ou senha incorreto!';
                }
            }
        }else {
            return 'conectado';
        }
    }

    /**
     * Destroi a autenticacao do usuario logado
     * @return void
     */
    public function logout() {
    //destroi a variavel de sessao do usuario
    }

    public function set_user($auth_user) {
    //cria a variavel de sessao do usuario
    }

    public function get_user() {
    //busca a variavel de sessao do usuario logado
    }

    /*
    public function save($user){
        //insere e altera usuario
    }
    
    public function remove($id){
        //remove usuario
    }
    
    public function fetch_all(){
        //exibe todos os usuarios
    }
    
    public function find($user){
        //pesquisa usuario
    }
    */
}

$au = new auth();
echo $au->login('teste','123');

?>
