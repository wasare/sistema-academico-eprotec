<?php

require("../../common.php"); 

$id = $_GET['id'];

CheckFormParameters(array("id"));

$conn = new Connection;
$conn->Open();

$sql = " delete from professores where id = '$id'";

$ok = $conn->Execute($sql);
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o professor!");
SuccessPage("Professor exclu�do com sucesso",
            "location='../consulta_inclui_professores.phtml'");

?>