<?
// Função que autentica o login dos usuários via LDAP ou via senha SAGU.
// É obrigatório ter o include do arquivo common no formulário que chama 
// esta função pois as definições de servidores de LDAP e BD estão
// definidas lá. - Beto - 22/07/2004
function AutenticaLogin($uid, $pwd, $auth)
{
    if (($uid != '') && ($pwd != '') && ($uid != '0') && ($pwd != '0'))
    {        
        if ($auth == 'ldap')
        {
            global $ldap_server, $ldap_base, $ldap_port, $ldap_admin_name, $ldap_admin_pass, $ldap_object, $ldap_name; 
            
            $ds=ldap_connect($ldap_server, $ldap_port);
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            $r=ldap_bind($ds, $ldap_admin_name, $ldap_admin_pass);
    
            if ($r)
            {
                $sr= ldap_search($ds, $ldap_base, str_replace('%0%',$uid, $ldap_object), array('dn',$ldap_name));
                @$info=ldap_get_entries($ds,$sr);
    
                if ($info[0]['dn'])
                {
                    @$r=ldap_bind($ds, $info[0]['dn'], $pwd);
                    if ($r)
                    {
                        ldap_close($ds);
                        return 1;
                    }
                    else
                    {
                        ldap_close($ds);
                        return 0;
                    }
                }
                else
                {
                    ldap_close($ds);
                    return 0;
                }
            }
            ldap_close($ds);
        }
        else
        {
            global $LoginDB, $LoginUID, $LoginPWD, $LoginHost;

            $conn = pg_connect("host=$LoginHost port=5432 user=$LoginUID password=$LoginPWD dbname=$LoginDB");
            if ($conn)
            {
                $sql = " select nome, " .
                       "        senha " .
                       " from pessoas " .
                       " where id ='$uid' and " .
                       "       senha = '$pwd' and " .
                       "       senha <> 0";
                                          
                $query = pg_exec($conn, $sql);
       
                if (pg_numrows($query)>0)   // Senha OK
                {
                    pg_freeresult($query);
                    pg_close($conn);
                    return 1;
                }
                else                        // Senha não OK
                {
                    pg_freeresult($query);
                    pg_close($conn);
                    return 0;
                }
            }
        }
    }
}
?>
