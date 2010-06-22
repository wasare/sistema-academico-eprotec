<?php 

require("../../../../lib/common.php"); 

$nome = $_POST['nome'];


CheckFormParameters(array("nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "select nextval('seq_aux_paises')";

$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id_paises = $query->GetValue(1);

  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero do País!");    

$sql = " insert into aux_paises (id," .
       "                         nome)" .
       " values (" .
       "                         '$id_paises'," .
       "                         '$nome')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Países",
            "location='../paises_inclui.phtml'",
            "O código do País é $id_paises");
?>
<html>
<head>
</head>
<body>
</body>
</html>
