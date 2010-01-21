<?php
/**
 * Cabecalho de relatorios
 *
 */
class header{

    private $param_conn;

    function __construct($arr){
        $this->param_conn = $arr;
    }

    function get_empresa($path_images){

		$conn = new connection_factory($this->param_conn);

        $empresa = $conn->get_row("SELECT razao_social, sigla FROM configuracao_empresa WHERE id = 1");

		return  '<br />
		         <img src="'.$path_images.'logo.jpg" alt="Instituto Federal de Minas Gerais" title="Instituto Federal de Minas Gerais"/>
                 <br /><br />'. $empresa['razao_social'];
    }
}

?>