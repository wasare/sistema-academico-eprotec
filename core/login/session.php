<?php

/**
 * Controle de sessao
 * @author wanderson
 *
 */
require_once(dirname(__FILE__).'/../../lib/adodb5/session/adodb-cryptsession2.php');

class session {

    function __construct($conn_options, $persist = TRUE, $debug = FALSE, $sess_table = 'sessao') {
      
      $ret = FALSE;

        list($host, $database, $user, $password, $port) = array_values($conn_options);

        $options['table'] = $sess_table;

        ADOdb_Session::config('postgres',$host,$user,$password,$database,$options);
        // adodb_sess_open(false,false,$connectMode = $persist);
        ADODB_Session::open(false,false,$connectMode = $persist);

        if(isset($GLOBALS['ADODB_SESS_CONN']) && is_object($GLOBALS['ADODB_SESS_CONN'])) {
            ADOdb_session::Persist($connectMode = $persist);
            $GLOBALS['ADODB_SESS_CONN']->debug = $debug;            
            @session_start();
         }
    }


    public static function refresh() {
        $random = rand(1,2);
        if (($random % 2) == 0) adodb_session_regenerate_id();
    }

    public static function destroy() {
        unset($_SESSION);
        session_destroy();
    }

    // forca eliminacao da sessao do usuario no banco
    // TODO: redirecionar o usuario para uma pagina com aviso de sessao expirada
    public static function clear_session($expireref, $sesskey) {

        if(is_object($GLOBALS['ADODB_SESS_CONN'])) {
            $GLOBALS['ADODB_SESS_CONN']->Execute("DELETE FROM sessao WHERE expireref = '". $expireref ."';");
            // limpa outras sessoes expiradas e inativas por mais de 15 minutos (900 segundos)
            ADODB_Session::gc(900);
        }
    }

    public static function resume() {

      if(isset($GLOBALS['ADODB_SESS_CONN']) && is_object($GLOBALS['ADODB_SESS_CONN'])) {
            ADOdb_session::Persist($connectMode = $persist);
            $GLOBALS['ADODB_SESS_CONN']->debug = $debug;
            @session_start();
        }       
    }
}

?>
