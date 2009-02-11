<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update instituicoes set " .
       "    id = '$id'," .
       "    nome = '$nome'," .
       "    sucinto = '$sucinto'," .
       "    nome_atual = '$nome_atual'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Instituição",
            "location='../consulta_inclui_instituicoes.phtml'",
            "Instituição alterada com sucesso.");
</script>
</HEAD>
<BODY></BODY>
</HTML>
