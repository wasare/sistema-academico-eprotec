<?php

require("../../common.php");


$ref_campus    = $_GET['ref_campus'];
$ref_curso     = $_GET['ref_curso'];
$ref_professor = $_GET['ref_professor'];


$conn = new Connection;
$conn->Open();

$sql = "delete from coordenador 
		where 
		ref_campus = '$ref_campus' and 
		ref_curso = '$ref_curso' and 
		ref_professor = '$ref_professor'";


$ok = $conn->Execute($sql);
$conn->Close();

saguassert($ok,"N�o foi poss�vel de excluir o coordenador!");

SuccessPage("Coordenador exclu�do do curso com sucesso",
            "location='../coordenadores.phtml'");

?>
