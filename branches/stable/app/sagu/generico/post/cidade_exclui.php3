<? 

require("../../../../lib/common.php"); 

$id = $_GET['id'];

?>

<html>
<head>
<?php 

CheckFormParameters(array("id"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " delete from aux_cidades" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel excluir o registro!");
SuccessPage("Exclus�o de Cidades",
            "location='../consulta_cidades.phtml'");

?>