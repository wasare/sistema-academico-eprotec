<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<?

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
SuccessPage("Altera��o Cadastro de Salas",
            "location='../cadastro_salas.phtml'",
            "As informa��es da sala foram atualizadas com sucesso.");
?>
