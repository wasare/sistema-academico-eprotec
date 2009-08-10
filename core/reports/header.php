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

        $RsEmpresa = $conn->Execute("SELECT razao_social, sigla FROM configuracao_empresa WHERE id = 1");

		$resp = '<div align="center" style="margin:8px;">
                    <img src="'.$path_images.'logo.jpg" />
                	<br /><br />
					<font face="verdana" size="3">'.$RsEmpresa->fields[0].'</font>
				</div>';
			
        $conn->Close();

		return $resp;
    }
}

?>
