<?php
/**
 * Classe de controle de acesso de url
 * @filesource
 * @copyright 2009 IFMG Campus Bambui
 * @author santiago
 * @version 1
 * @since 2009-09-29
 * @package sa
 * @subpackage sa.core.login
 */
class acl {

    /**
     * Verifica se tem acesso a uma url
     * @return verdadeiro ou falso
     */
    public function has_access ($url, connection_factory $conn) {

        global $sa_usuario_id, $BASE_DIR;


        $url_completo = '/'. str_replace($BASE_DIR,'',$url);
        $url_raiz     = str_replace(basename($url_completo),'',$url_completo);

        $arr_dirs     = explode('/',$url_raiz);

        array_pop($arr_dirs);

        foreach($arr_dirs as $dir){
            if(empty($dir)){
                $dir_1 = "/";
            }else {
                $dir_1 .= $dir."/";
            }
            $where_in .= "'".$dir_1."', ";
        }

        $sql_url = "SELECT ref_papel
                    FROM url, papel_url
                    WHERE
                        url_id = ref_url AND
                        url IN ($where_in '$url_completo');";

        $rs_url    = $conn->Execute($sql_url);
        $roles_url = $rs_url->GetArray();
        $arr_url   = array();

        foreach($roles_url as $row_url)
            $arr_url[] = $row_url['ref_papel'];


        //-- busca os papeis do usuario

        $sql_usr = "SELECT ref_papel
                    FROM usuario_papel
                    WHERE ref_usuario = $sa_usuario_id";

        $rs_usr    = $conn->Execute($sql_usr);
        $roles_usr = $rs_usr->GetArray();
        $arr_usr   = array();

        foreach($roles_usr as $row_usr)
            $arr_usr[] = $row_usr['ref_papel'];


        //-- Verifica os papeis do usuario e url para acesso

        $arr = array_intersect($arr_usr, $arr_url);

        if(!count($arr) > 0) {
            return false;
        }else{
            return true;
        }


    }
    
    /**
    * Verifica se o usuario tem permissao para acessar uma url
    * @param url de acesso
    * @param conexao com banco de dados
    * @return efetuado ou rejeitado acesso a arquivo
    */
    public static function check($url, connection_factory $conn){
        
        $acl = new acl();

        if(!$acl->has_access($url, $conn)){
            die('<center><h2>Sem permiss&atilde;o para acessar esta p&aacute;gina.</h2>'.
                '<a href="javascript:history.back(-1)">Voltar</a></center>');
        }
    }

}

?>
