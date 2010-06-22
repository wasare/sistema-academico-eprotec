<?php

require("../../../../lib/common.php"); 

$id = $_GET['id'];

CheckFormParameters(array("id"));

$conn = new Connection;

$conn->Open();

$sql = " delete from pre_requisitos " .
       " where id = '$id' ";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Nao foi possivel excluir o registro!");

SuccessPage("Exclusão de Pré-Requisito",
            "location='../consulta_inclui_pre_requisito.phtml'");

?>