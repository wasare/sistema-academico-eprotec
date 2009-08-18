<?php

require("../../common.php");

$id = $_GET['id'];

$conn = new Connection;

$conn->Open();

$sql = "delete from salas" .
          " where id='$id'";

$ok = $conn->Execute($sql);

$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
               "location='../cadastro_salas.phtml'",
               "A sala foi excluída com sucesso.");

?>
