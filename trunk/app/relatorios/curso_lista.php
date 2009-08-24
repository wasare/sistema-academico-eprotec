<?php

require_once '../../configs/configuracao.php';

$conn = new connection_factory($param_conn);

if($_POST['param'] == ''){
	echo '';	
}else{
	$sql = "SELECT descricao, id 
		FROM cursos
		WHERE lower(to_ascii(descricao)) like lower(to_ascii('%".$_POST['param']."%')) 
		ORDER BY descricao DESC LIMIT 10;";
	$sql = iconv("utf-8", "iso-8859-1", $sql);
	$RsCurso = $conn->Execute($sql);
	
	while(!$RsCurso->EOF){
		$resp .= "<a href=\"javascript:send('".$RsCurso->fields[1]."', '".$RsCurso->fields[0]."')\">".$RsCurso->fields[0]." - ".$RsCurso->fields[1]."</a><br />";
		$RsCurso->MoveNext();
	}
	$resp .= '<a href="javascript:fechar()" style="text-align: right;">Fechar</a>';
	echo $resp;
}
?>