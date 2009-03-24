<?php

require("../../../../lib/common.php");


$ref_campus    = $_GET['ref_campus'];
$ref_curso     = $_GET['ref_curso'];
$ref_professor = $_GET['ref_professor'];


$conn = new Connection;
$conn->Open();

$sql = "delete from coordenadores 
		where 
		ref_campus = '$ref_campus' and 
		ref_curso = '$ref_curso' and 
		ref_professor = '$ref_professor'";


$ok = $conn->Execute($sql);
$conn->Close();

saguassert($ok,"Não foi possível de excluir o coordenador!");

SuccessPage("Coordenador excluído do curso com sucesso",
            "location='../coordenadores.phtml'");

?>