<?php


//5043 - 5098
//5100 - 5222
//5346 - 5402
//5408 - 5860

// Na tabela do sagu documentos consulte o ultimo registro 
$inicio = 5408;
// cadastre ate o ultimo cadastro de pessoa fisica
$final = 5860;

for($x = $inicio; $x <= $final; $x++){
	
	$qryDoc .= "INSERT INTO documentos(ref_pessoa) VALUES('$x');";

}

echo $qryDoc;

?>