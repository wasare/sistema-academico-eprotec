<? 

require("../../common.php"); 

$id = $_GET['id'];

$conn = new Connection;

$conn->Open();

$sql = "delete from areas_ensino where id='$id';";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../areas_ensino.phtml'");

?>