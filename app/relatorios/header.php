<?php


function hd_empresa($con, $logo){

	$RsEmpresa = $con->Execute("SELECT razao_social, sigla FROM configuracao_empresa WHERE id = 1");
	
	if (!$RsEmpresa){
	    print $con->ErrorMsg();
	    die();
	}

	$resp = '<div align="center" style="margin:8px;"><img src="'.$logo.'" /><br />
		<font face="verdana" size="3">'.$RsEmpresa->fields[0].'</font>
		</div>';

	return $resp;
}

//echo hd_empresa($Conexao, '../../images/armasbra.jpg');

?>
