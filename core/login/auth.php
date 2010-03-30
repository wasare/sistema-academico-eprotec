<?php
/**
 * Classe de autentica��o de usu�rio
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

    protected $redirect_url, $base_url, $sess_table, $log_file;

    function __construct($base_url,$log_file='login.log',$sess_table='sessao') {

        $this->base_url = $base_url;
        $this->sess_table = $sess_table;

        // TODO: melhorar tratamento de logs
        $this->log_file = $log_file;

        $this->redirect_url = $base_url;
    }


    /**
     * Efetua a autentica��o do usu�rio em um m�dulo do SA
     * @param Login
     * @param Senha
     * @param M�dulo que vai acessar no SA
     * @param conexao com banco de dados
     * @return boolean
     */
    public function login($login, $senha, $modulo, $conn) {

        $ret = FALSE;

        $log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

        if(empty($login) || empty($senha)) {
            exit(header('Location: '. $this->base_url .'app/login/index.php?sa_msg=Nome de usu�rio e senha n�o preenchidos.'));
        }
        else {

            $sql = "SELECT u.id,ref_pessoa,nome_campus FROM usuario u, campus c
                    WHERE nome = '$login' AND
                    senha = '". hash('sha256',trim($senha)) ."' AND
                    c.id = ref_campus AND
                    ativado = 'TRUE'; ";

            $usuario = $GLOBALS['ADODB_SESS_CONN']->getAll($sql);

            // retorna o primeiro valor da consulta
            if(count($usuario) == 1) {

                list($usuario) = $usuario;

                // CONFIGURA OS PARAMETRO DE TRATAMENTO DE EXPIRACAO DA SESSAO
                $GLOBALS['USERID'] = $login;
                $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');

                // CONFIGURA AS VARIAVEIS DA SESSAO DE LOGIN
                $_SESSION['sa_auth'] = $login .':'. hash('sha256',$senha) .':'. $usuario[0] .':'. $usuario[1];
                $_SESSION['sa_modulo'] = $modulo;
                $_SESSION['sa_campus'] = $usuario[2];

                // for�a atualiza��o da sess�o recriando o ID da sess�o
                adodb_session_regenerate_id();

                $log_msg .= $login .' - *** LOGIN ACEITO ***'."\n";

                error_log($log_msg,3,$this->log_file);

                $this->reg_log('LOGIN ACEITO');

                $ret = TRUE;
            }
            else {
                $log_msg .=  $login .' - *** LOGIN RECUSADO ***'."\n";

                error_log($log_msg,3,$this->log_file);

                $this->reg_log('LOGIN RECUSADO', $_SERVER["PHP_SELF"], $login, $modulo);

            }
        }

        return $ret;

    }


    /**
     * Checa a autentica��o do usu�rio
     * @return void
     */
    public function check_login($sessao) {

        $sessao->resume();

        list($sa_usuario,$sa_senha,$sa_usuario_id,$sa_ref_pessoa) = explode(":",$_SESSION['sa_auth']);
        $sa_modulo = $_SESSION['sa_modulo'];

        if($sa_modulo == 'aluno_login') {
            // Redirecionamento de alunos
            $redirecionamento = '';
        }
        else {
            $redirecionamento = $this->redirect_url .'app/login/index.php?sa_msg=';
        }

        if(!isset($_SESSION['sa_auth']) || empty($_SESSION['sa_auth'])) {
            exit(header('Location: '. $redirecionamento .'Sem permiss�o de acesso ou sess�o expirada'));
        }
        else {
            $log_msg = $_SERVER['REMOTE_ADDR'] .' - ['. date("d/m/Y H:i:s") .'] - ';

            // verifica e desconecta usuario com mais de uma sessao simultanea
            $sql = "SELECT COUNT(*)
                    FROM $this->sess_table
                    WHERE expireref = '". $sa_usuario ."';";

            $cont_sess = $GLOBALS['ADODB_SESS_CONN']->getOne($sql);

            if($cont_sess > 1) {

                $sessao->clear_session($sa_usuario, NULL);
                $sessao->destroy();

                $log_msg .= $sa_usuario .' - *** LOGIN DUPLICADO ***'."\n";

                error_log( $log_msg,3,$this->log_file);

                exit(header('Location: '. $redirecionamento .'Sess�o expirada por duplicidade de acesso.'));
            }
            elseif($cont_sess == 1) {
                // CONFIGURA OS PARAMETRO DE TRATAMENTO DE EXPIRACAO DA SESSAO
                $GLOBALS['USERID'] = $sa_usuario;
                $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'] = array('USERID','session::clear_session');

                // CONFIGURA AS VARIAVEIS DA SESSAO DE LOGIN
                $_SESSION['sa_auth'] = $sa_usuario .':'. $sa_senha .':'. $sa_usuario_id .':'. $sa_ref_pessoa;
                //$_SESSION['sa_modulo'] = $sa_modulo;
            }
            else {
                $sessao->clear_session($sa_usuario, NULL);
                $sessao->destroy();

                $log_msg .= $sa_usuario .' - *** FALHA AO VERIFICAR LOGIN ***'."\n";

                error_log($log_msg,3,$this->log_file);

                exit(header('Location: '. $redirecionamento .'Sess�o expirada ou inexistente.'));
            }
        }
    }

    /**
     * Registra logs no banco de dados
     * @return void
     */
    public function reg_log($status, $pagina='', $usuario='', $modulo='') {

        list($sa_usuario,$sa_senha,$sa_usuario_id,$sa_ref_pessoa) = explode(":",$_SESSION['sa_auth']);

        $pagina = empty ($pagina) ? $_SERVER['PHP_SELF'] : $pagina;
        $usuario = empty($usuario) ? $sa_usuario : $usuario;
        $modulo = empty($modulo) ? $_SESSION['sa_modulo'] : $modulo;
        $sa_senha = empty($sa_senha) ? '-' : $sa_senha;

        if ($modulo == 'web_diario_login') {

            $ip = $_SERVER["REMOTE_ADDR"];
            $sql_store = htmlspecialchars("$usuario");
            $sql_log = 'INSERT INTO diario_log (usuario, data, hora, ip_acesso, pagina_acesso, status, senha_acesso) VALUES ';
            $sql_log .= '(\''.$sql_store.'\',\''. date("Y-m-d") .'\',\''. date("H:i:s") .'\','."'$ip','$pagina','$status','$sa_senha');";

            if (isset($GLOBALS['ADODB_SESS_CONN']))
                $GLOBALS['ADODB_SESS_CONN']->Execute($sql_log);
        }
    }
    /**
     * Configura a URL raiz do sistema
     * @return void
     */
    public function base_url($url) {
        $this->base_url = $url;
    }


    /**
     * Configura a URL de redirecionamento
     * @return void
     */
    public function redirect_url($url) {
        $this->redirect_url = $url;
    }

    /**
     * Configura o caminho para o arquivo de log
     * @return void
     */
    public function log_file($path) {
        $this->log_file = $path;
    }
}

?>
