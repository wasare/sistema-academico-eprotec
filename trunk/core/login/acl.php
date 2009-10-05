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
     * Verifica se o usuario tem permissao para acessar uma url
     * @param codigo do usuario
     * @param url de acesso
     * @param conexao com banco de dados
     * @return efetuado ou rejeitado
     */
    public function check ($id_usuario, $url, connection_factory $conn) {

        if(empty($id_usuario) or empty($url)) {
            return false;
        }
        else
        {

            //-- buscar os papeis que a url passada por parametro tem permissao

            $sql_url = "SELECT ref_papel
                        FROM url, papel_url
                        WHERE
                            url_id = ref_url AND
                            url = '$url';";

            $rs_url = $conn->Execute($sql_url);

            $roles_url = $rs_url->GetArray();

            $arr_url = array();

            foreach($roles_url as $row_url)
                $arr_url[] = $row_url['ref_papel'];


            //-- busca os papeis do usuario

            $sql_usr = "SELECT ref_papel
                        FROM usuario_papel
                        WHERE 
                            ref_usuario = $id_usuario";

            $rs_usr    = $conn->Execute($sql_usr);

            $roles_usr = $rs_usr->GetArray();

            $arr_usr = array();

            foreach($roles_usr as $row_usr)
                $arr_usr[] = $row_usr['ref_papel'];


            //-- Verifica se o usuario tem permissao para acessar esta url

            $arr = array_intersect($arr_usr, $arr_url);

            if(count($arr) > 0)
            {
                print 'Permitido';
            }
            else
            {
                print 'Sem permissao';
            }

        }
    }
}

/**
 * TESTE DA CLASSE
 */
require_once("../../app/setup.php");

acl::check(115, __FILE__, new connection_factory($param_conn));

//$teste = new acl();
//$teste->check(115, '/app/setup.php', new connection_factory($param_conn));

$caminho_relativo_completo = '/'. str_replace($BASE_DIR,'',__FILE__);
$caminho_relativo_raiz = str_replace(basename($caminho_relativo_completo),'',$caminho_relativo_completo);

echo '<br />', $caminho_relativo_completo;
echo '<br />', $caminho_relativo_raiz.'<br />';
print_r($_SESSION);
?>
