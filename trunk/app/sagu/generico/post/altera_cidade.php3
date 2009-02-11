<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id","nome","cep"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "update aux_cidades set " .
       "    nome = '$nome'," .
       "    cep = '$cep'," .
       "    ref_pais = '$ref_pais'," .
       "    ref_estado = '$ref_estado' " .//, " .
//       "    praca = '$praca'" .
       "  where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Cidade",
            "location='../consulta_cidades.phtml'",
	        "As informações da Cidade <b>$nome</b> foram atualizadas com sucesso.");
</script>
