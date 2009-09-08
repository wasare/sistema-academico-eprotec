<?php

/**
 * Controle de sessao
 * @author wanderson
 *
*/

class session {
    
	private function __construct() {}


	public static function refresh() {

        if ((rand()%10) == 0) adodb_session_regenerate_id();
		//adodb_session_regenerate_id();
		//echo 'chamou';
    }

	public static function destroy() {
        unset($_SESSION);
        session_destroy();
    }


	public static function init($info_connection, $persist = TRUE, $debug = FALSE, $table = 'sessao') {  

		require_once(dirname(__FILE__).'/../../lib/adodb5/session/adodb-cryptsession2.php');
		
		$ret = FALSE;
			
		list($host, $database, $user, $password, $port) = array_values($info_connection);
			
		$options['table'] = $table;

		ADOdb_Session::config('postgres',$host,$user,$password,$database,$options);
		adodb_sess_open(false,false,$connectMode = $persist);
				
		if(isset($GLOBALS['ADODB_SESS_CONN']) && is_object($GLOBALS['ADODB_SESS_CONN']))
				$ret = TRUE;	

		if($ret == TRUE)
		{
			ADOdb_session::Persist($connectMode = $persist);
			$GLOBALS['ADODB_SESS_CONN']->debug = $debug;
			session::refresh();
		}
        
		@session_start();
		
	}
}

?>
