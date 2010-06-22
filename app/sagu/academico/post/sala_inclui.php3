<?php

require("../../../../lib/common.php");
require("../../lib/InvData.php3");


$sala = $_POST['sala'];
$ref_campus = $_POST['ref_campus'];
$capacidade = $_POST['capacidade'];

CheckFormParameters( array("sala",
                           "ref_campus",
                           "capacidade") );

$conn = new Connection;
$conn->Open();
$conn->Begin();
 
$sql = "insert into salas ( numero," .
       "                    ref_campus," .
       "                    capacidade )" .
       " values ('$sala'," .
       "         '$ref_campus'," .
       "         '$capacidade')";

$ok = $conn->Execute($sql);
$err= $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!<BR><BR>$err");

SuccessPage("Inclusão de Salas",
            "location='../sala_inclui.phtml'",
            "Sala incluída com sucesso!!!.",
            "location='../cadastro_salas.phtml'");


?>
<html>
<head>
<title>Sala Cadastrada</title>
</head>
<body>
</body>
</html>