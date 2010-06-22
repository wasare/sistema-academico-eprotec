<?php

require("../../../../lib/common.php");
require("../../lib/InvData.php3");

$id = $_POST['id'];
$campus = $_POST['campus'];
$sala = $_POST['sala'];
$capacidade = $_POST['capacidade'];

$conn = new Connection;

$conn->Open();

$sql = "update salas set " .
       "    id         = '$id'," .
       "    ref_campus = '$campus'," .
       "    numero     = '$sala'," .
       "    capacidade = '$capacidade'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);  
SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

$err= $conn->GetError();

$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");
SuccessPage("Alteração Cadastro de Salas",
            "location='../cadastro_salas.phtml'",
            "As informações da sala foram atualizadas com sucesso.");
?>
