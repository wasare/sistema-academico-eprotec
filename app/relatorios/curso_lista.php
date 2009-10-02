<?php

require_once '../../app/setup.php';

$conn = new connection_factory($param_conn);


if(!isset($_POST)){
    echo '';
}else{

    foreach($_POST as $chave => $valor) {
        $nome_campo = trim($chave);
        $termo_pesquisa = trim($valor);
        break;
    }

	$sql = "SELECT descricao, id 
		FROM cursos
		WHERE lower(to_ascii(descricao)) like lower(to_ascii('%". $termo_pesquisa ."%')) 
		ORDER BY descricao DESC LIMIT 10;";
	$sql = iconv("utf-8", "iso-8859-1", $sql);
	$RsCurso = $conn->Execute($sql);


	while(!$RsCurso->EOF){
        $resp .= '<a href="javascript:'. $nome_campo .'_send(\''. $RsCurso->fields[1] .'\', \''. $RsCurso->fields[0] .'\')">'. $RsCurso->fields[0] .' - '. $RsCurso->fields[1] .'</a><br />';
        $RsCurso->MoveNext();
    }
    $resp .= '<a href="javascript:'. $nome_campo .'_fechar();" style="text-align: right;">Fechar</a>';
    echo $resp;
	
}
?>
