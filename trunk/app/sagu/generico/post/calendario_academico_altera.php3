<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id","ref_periodo","dia_semana","data_aula"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$data_aula = InvData($data_aula);

$sql = " update calendario_academico set " .
       "    ref_periodo = '$ref_periodo', " .
       "    dia_semana = '$dia_semana', " .
       "    data_aula = '$data_aula' " .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Data de Aula",
	        "location='../consulta_inclui_calendario_academico.phtml'",
	        "As informações da data da aula no calendário acadêmico foram atualizadas com sucesso!!!");
</script>
</head>
<body>
</body>
</html>
