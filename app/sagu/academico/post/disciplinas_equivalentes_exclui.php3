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

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Disciplina Equivalente excluída com sucesso",
            "location='../consulta_disciplinas_equivalentes.phtml'");

?>