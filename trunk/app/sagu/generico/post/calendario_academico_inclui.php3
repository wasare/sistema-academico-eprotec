<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_periodo", "dia_semana", "data_aula"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$data_aula= InvData($data_aula);

$sql = " insert into calendario_academico (" .
       "         ref_periodo, " .
       "         dia_semana, " .
       "         data_aula)" .
       " values ('$ref_periodo'," .
       "         '$dia_semana'," .
       "         '$data_aula')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

$nova_data = mktime(0,0,0, substr($data_aula, 5,2), substr($data_aula, 8,2), substr($data_aula, 0,4)) + mktime(0,0,0,1,8,1970);

$data_aula = date("d", $nova_data) . '/' . date("m", $nova_data) . '/' . date("Y", $nova_data); 

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Data de Aula no Calendário Acadêmico",
            "location='../calendario_academico_inclui.phtml?ref_periodo=$ref_periodo&dia_semana=$dia_semana&data_aula=$data_aula'",
            "Data de Aula incluída com sucesso!!!.",
            "location='../consulta_inclui_calendario_academico.phtml'");

</script>

</head>
<body>
</body>
</html>
