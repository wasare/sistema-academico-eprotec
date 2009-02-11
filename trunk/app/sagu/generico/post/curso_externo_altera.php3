<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "nome",
                          "sucinto"));

$conn = new Connection;

$conn->Open();

$sql = " update cursos_externos set " .
       "    id = '$id'," .
       "    nome = '$nome'," .
       "    sucinto = '$sucinto'," .
       "    obs = '$obs'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Cursos Externos",
            "location='../consulta_inclui_curso_externo.phtml'",
            "Curso Externo alterado com sucesso.");
</script>
</HEAD>
<BODY>
</BODY>
</HTML>
