<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id","mes"));

$conn = new Connection;
$conn->Open();

$sql = "update previsao_lcto set " .
       "    mes = '$mes'" .
       "  where";
       "    id = '$id'";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração do Previsão de Lançamento",
            "history.go(-1)");

</script>