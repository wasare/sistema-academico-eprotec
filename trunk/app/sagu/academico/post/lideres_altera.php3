<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "ref_periodo", 
                          "ref_disciplina_ofer", 
                          "ref_disciplina_ofer_compl",
                          "ref_pessoa"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " update lideres set " .
       "    ref_pessoa = '$ref_pessoa' " .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Inclus�o de L�deres de Turma",
	        "location='../consulta_inclui_lideres.phtml'",
	        "As informa��es do L�der foi atualizada com sucesso.");

</script>
</head>
<body>
</body>
</html>
