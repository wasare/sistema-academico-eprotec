<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
			              "descricao"));

$conn = new Connection;

$conn->Open();

$turno = substr($turno, 0, 1);

$sql = "update regimes_disciplinas set " .
       "    descricao = '$descricao'" .
       "  where id = '$id'";

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Curso",
            "location='../regimes_disciplinas.phtml'",
            "Regime alterado com sucesso.");
</script>
