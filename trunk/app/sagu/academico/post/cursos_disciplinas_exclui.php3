<?php 

require("../../common.php");

$ref_curso      = $_GET['ref_curso'];
$ref_disciplina = $_GET['ref_disciplina'];
$ref_campus     = $_GET['ref_campus'];

$conn = new Connection;

$conn->Open();

$sql = "delete from cursos_disciplinas where ref_curso='$ref_curso' and ref_campus='$ref_campus' and ref_disciplina='$ref_disciplina';"; 

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../consulta_inclui_cursos_disciplinas.phtml'");

?>