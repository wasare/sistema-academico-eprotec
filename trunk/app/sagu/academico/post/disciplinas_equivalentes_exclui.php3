<?php

require("../../common.php");

$id = $_GET['id'];

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "delete from disciplinas_equivalentes where id='$id';";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Disciplina Equivalente exclu�da com sucesso",
            "location='../consulta_disciplinas_equivalentes.phtml'");

?>