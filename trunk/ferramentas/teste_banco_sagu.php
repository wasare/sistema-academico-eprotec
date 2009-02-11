
<h1>Exibir</h1>

<?php

require("../lib/common.php"); 

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "SELECT id, descricao FROM cursos;";

$qry = $conn->CreateQuery($sql);

while( $qry->MoveNext() )
{
	list ($id, $descricao) = $qry->GetRowVALUES();
	
	echo $id . " - " . $descricao . "<br />";
}

$conn->Finish();
$conn->Close();

?>

<h1>Executar</h1>

<?php

$sql = "SELECT id, descricao FROM cursos;";

$ok = $conn->Execute($sql);

if ( !$ok )
{
	SaguAssert(0,"Problema ao inserir na tabela de matrícula. Execute o procedimento novamente...");
}else
{
	echo "Deu!";
}
?>
