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
    public function login($login, $senha, $modulo, $conn) 
    {
        $log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

        if($login == '' OR empty($senha)) 
        {
            exit(header('Location: '. $BASE_URL .'index.php?sa_msg=Nome de usuário e senha não preenchidos.'));
        }
        else {

            $sql = "SELECT COUNT(*) FROM usuario
                    WHERE nome = '$login' AND
                    senha = '". md5(trim($senha)) ."' AND 
                    ativado = 'TRUE'; ";

            //retorna o primeiro valor da consulta
            if($conn->adodb->getOne($sql) == 1) 
            {
                $verifica_usuario = true;
            }
            else 
            {
                $verifica_usuario = false;
            }

            if($verifica_usuario) 
            {
                $log_msg .= $login .' - *** LOGIN ACEITO (host='.
                    $param_conn['host'] .',db='.
                    $param_conn['database'] .',uid='.
                    $login .',pwd=) ***'."\n";

                error_log($log_msg,3,$LOGIN_LOG_FILE);

                $GLOBALS['USERID'] = trim($login);
                $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');

                $_SESSION['sa_auth'] = trim($login) .':'. trim($senha);

                $_SESSION['sa_modulo'] = $modulo;

                switch ($modulo)
                {
                    case 'sa_login':
                        exit(header('Location: '. $BASE_URL .'app/index.php'));
                        break;
                    case 'web_diario_login':
                        exit(header('Location: '. $BASE_URL .'app/webdiario/'));
                        break;
                    case 'aluno_login':
                        exit(header('Location: '. $BASE_URL .'app/aluno'));
                        break;
                }
            }
            else 
            {
                $log_msg .=  $login .' - *** LOGIN RECUSADO (host='.
                    $param_conn['host'] .',db='.
                    $param_conn['database'] .',uid='.
                    $login .',pwd=) ***'."\n";

                error_log($log_msg,3,$LOGIN_LOG_FILE);

                if ($modulo == 'sa_login') {
                    exit(header('Location: '. $BASE_URL .'index.php?sa_msg=Senha ou usuário inválido'));
                }
                if ($modulo == 'web_diario_login') {
                    exit(header('Location: '. $BASE_URL .'index.php?sa_msg=Senha ou usuário inválido'));
                }
                if ($modulo == 'aluno_login') {
                    exit(header('Location: '. $BASE_URL .'index.php?sa_msg=Senha ou usuário inválido'));
                }
            }
        }
    }


    /**
     * Checa a autenticação do usuário
     * @return void
     */
    public function check_login($BASE_URL, $SESS_TABLE, $LOGIN_LOG_FILE) 
    {

        if($_SESSION['sa_modulo'] == 'aluno_login')
        {
            //Redirecionamento de alunos
            $redirecionamento = '';
        }
        else
        {
            $redirecionamento = $BASE_URL .'index.php?sa_msg=';
        }

        if(!isset($_SESSION['sa_auth']) OR empty($_SESSION['sa_auth'])) 
        {
            exit(header('Location: '. $redirecionamento .'Sem permissão ou sua sessao expirou.'));
        }
        else 
        {
            list($uid, $pwd) = explode(":",$_SESSION['sa_auth']);

            // verifica e desconecta usuario com duas sessoes simultaneas
            $sql = "SELECT COUNT(*) 
                    FROM $SESS_TABLE 
                    WHERE expireref = '". $uid ."';";
            
            $cont_sess = $GLOBALS['ADODB_SESS_CONN']->getOne($sql);

            $log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

            if($cont_sess > 1) 
            {
                session::clear_session($uid, NULL);
                session::destroy();

                $log_msg .= $param_conn['user'] .' - *** LOGIN DUPLICADO (host='.
                    $param_conn['host'] .',db='.
                    $param_conn['database'] .',uid='.
                    $param_conn['user'] .',pwd=) ***'."\n";

                error_log( $log_msg,3,$LOGIN_LOG_FILE);

                exit(header('Location: '. $redirecionamento .'Sessao expirada por duplicidade de acesso.'));
            }
            elseif($cont_sess == 1)
            {
                $GLOBALS['USERID'] = $uid;
                $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');
            }
            else
            {
                unset($_SESSION['sa_auth']);

                $log_msg .= $param_conn['user'] .' - *** FALHA AO VERIFICAR LOGIN (host='.
                    $param_conn['host'] .',db='.
                    $param_conn['database'] .',uid='.
                    $param_conn['user'] .',pwd=) ***'."\n";

                error_log( $log_msg,3,$LOGIN_LOG_FILE);

                exit(header('Location: '. $redirecionamento .'Sessão expirada ou inexistente.'));
            }
        }
    }

}

?>
