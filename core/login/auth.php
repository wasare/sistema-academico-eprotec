<?php
/**
 * Classe de autenticação de usuário
 * @filesource
 * @copyright 2009 IFMG Campus Bambui
 * @author santiago
 * @author wanderson
 * @version 1
 * @since 2009-09-01
 * @package sa
 * @subpackage sa.core.login
 */
class auth {

/**
 * Efetua a autenticação do usuário em um módulo do SA
 * @param Login
 * @param Senha
 * @param Módulo que vai acessar no SA
 * @param conexao com banco de dados
 * @return efetuado ou rejeitado
 */
    public function login($login, $senha, $modulo, $conn) {

        $log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

        //Verifica o usuario no banco
        if(empty($login) OR empty($senha)) {
            $verifica_usuario = false;
        }else {

            $sql = "SELECT COUNT(*) FROM usuario
                    WHERE nome = '$login' AND senha = '$senha'; ";

            //retorna o primeiro valor da consulta
            if($conn->adodb->getOne($sql) == 1) {
                $verifica_usuario = true;
            }else {
                $verifica_usuario = false;
            }
        }

        if($verifica_usuario) {

            //Grava log com sucesso de login
            $log_msg .= $login .' - *** LOGIN ACEITO (host='.
                $param_conn['host'] .',db='.
                $param_conn['database'] .',uid='.
                $login .',pwd=) ***'."\n";

            error_log($log_msg,3,$LOGIN_LOG_FILE);

            $GLOBALS['USERID'] = trim($login);
            $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');

            $_SESSION['sa_auth'] = trim($login) .':'. trim($senha);
            
            $_SESSION['sa_modulo'] = $modulo;

            switch ($modulo) {
                case 'sa_login':
                    header('Location: '. $BASE_URL .'app/index.php');
                    break;
                case 'web_diario_login':
                    header('Location: '. $BASE_URL .'app/webdiario/');
                    break;
                case 'aluno_login':
                    header('Location: '. $BASE_URL .'app/aluno');
                    break;
            }
        }
        else {
            //Grava log com erro de login
            $log_msg .=  $login .' - *** LOGIN RECUSADO (host='.
                $param_conn['host'] .',db='.
                $param_conn['database'] .',uid='.
                $login .',pwd=) ***'."\n";

            error_log($log_msg,3,$LOGIN_LOG_FILE);

            // TODO: retornar o erro de tentativa de login na proprio formulario de login
            //Erro: senha ou usuário inválido
            if ($modulo == 'sa_login') {
                header('Location: '. $BASE_URL .'index.php?sa_msg=1');
            }
            if ($modulo == 'web_diario_login') {
                header('Location: '. $BASE_URL .'index.php?sa_msg=2');
            }
            if ($modulo == 'aluno_login') {
                header('Location: '. $BASE_URL .'index.php?sa_msg=3');
            }
        }
    }


    /**
     * Checa a autenticação do usuário
     * @return void
     */
    public function check_login($BASE_URL, $SESS_TABLE, $LOGIN_LOG_FILE) {

        //require_once(dirname(__FILE__).'/../../config/configuracao.php');
        
        var_dump($_SESSION['sa_auth']);
        //echo ' caminho: '.$BASE_URL . $SESS_TABLE . $LOGIN_LOG_FILE;
        die;

        // TODO: registrar em log quando falhar a verificacao - gravar em banco de dados

        $log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';


        if(empty($_SESSION['sa_auth'])) {
            //FIXME: if verificando o modulo para voltar para a pagina inicial, pode ser aluno
            header('Location: '. $BASE_URL .'index.php?sa_msg=4');
            exit;
        }else {
            
            /* FIXME: Verificar variavel de sessao $_SESSION['sa_auth'] se esta vazia ou exite
               executar uma nova consulta para validar os dados de autenticacao */

            list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

            // verifica e desconecta usuario com duas sessoes simultaneas
            $cont_sess = $GLOBALS['ADODB_SESS_CONN']->getOne("SELECT COUNT(*) FROM $SESS_TABLE WHERE expireref = '". $uid ."';");

            if($cont_sess > 1) {
            // exclui a sessao do usuario no banco de dados
                session::clear_session($uid, NULL);
                session::destroy();

                // grava log de falha de acesso
                $log_msg .= $param_conn['user'] .' - *** LOGIN DUPLICADO (host='.
                    $param_conn['host'] .',db='.
                    $param_conn['database'] .',uid='.
                    $param_conn['user'] .',pwd=) ***'."\n";

                error_log( $log_msg,3,$LOGIN_LOG_FILE);

                // TODO: redirecionar a uma pagina de aviso de sessao expirada por duplicidade
                header('Location: '. $BASE_URL .'index.php?sa_msg=5');
                exit;
            }

            //Criar flag Se validado para substituir o se abaixo
            if(empty($uid) && empty($pwd)) {

                unset($_SESSION['sa_auth']);

                // grava log de falha de acesso
                $log_msg .= $param_conn['user'] .' - *** FALHA AO VERIFICAR LOGIN (host='.
                    $param_conn['host'] .',db='.
                    $param_conn['database'] .',uid='.
                    $param_conn['user'] .',pwd=) ***'."\n";

                error_log( $log_msg,3,$LOGIN_LOG_FILE);

                header('Location: '. $BASE_URL .'index.php?sa_msg=');
                exit;
            }
            else {
                $GLOBALS['USERID'] = $uid;
                $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');
            }
        }
    }

}

?>
