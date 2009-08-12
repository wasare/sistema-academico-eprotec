<?php 

require("../../../../lib/common.php");

$nome       = $_POST['nome'];
$sucinto    = $_POST['sucinto'];
$nome_atual = $_POST['nome_atual']; 

CheckFormParameters(array("nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = "select nextval('seq_instituicoes_id')";

$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id = $query->GetValue(1);
  
  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero de motivo!");

$sql = " insert into instituicoes (" .
       "                               id," .
       "                               nome," .
       "                               sucinto, " . 
       "                               nome_atual) " . 
       " values (" .
       "                               '$id'," .
       "                               '$nome'," .
       "                               '$sucinto', " .
       "                               '$nome_atual') ";
	      

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Instituição",
            "location='../inclui_instituicao.phtml'",
            "O código da Instituição é $id",
            "location='../consulta_inclui_instituicoes.phtml'");

?>
<html>
<head>
</head>
<body>
</body>
</html>
