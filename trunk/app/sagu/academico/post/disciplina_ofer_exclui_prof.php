<?php 

require("../../common.php");

$ref_professor      = (int) $_GET['ref_professor'];
$ref_disciplina_ofer = (int) $_GET['ref_disciplina_ofer'];

$conn = new Connection;

$conn->Open();

$sql = "delete from disciplinas_ofer_prof where ref_disciplina_ofer = $ref_disciplina_ofer and ref_professor = $ref_professor;"; 

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../atualiza_disciplina_ofer.phtml?id=$ref_disciplina_ofer'");

?>
