<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update grupos_disciplinas set " .
       "    id = '$id'," .
       "    descricao = '$descricao'" .
       " where id='$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel alterar o registro!");
SuccessPage("Altera��o de Grupo de Disciplina",
            "location='../consulta_inclui_grupos_disciplinas.phtml'",
            "Grupo/Disciplina alterado com sucesso.");
</script>
</HEAD>
<BODY></BODY>
</HTML>
