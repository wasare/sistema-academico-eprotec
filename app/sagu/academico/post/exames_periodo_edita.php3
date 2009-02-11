<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_periodo",
                          "ref_campus",
                          "dt_exame",
                          "descricao",
                          "dia_semana"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$dt_exame = InvData($dt_exame);
$dt_exame1 = InvData($dt_exame1);

$sql = "update dt_exames_periodos set " .
       "  ref_campus = '$ref_campus'," .
       "  dt_exame = '$dt_exame'," .
       "  descricao = '$descricao'," .
       "  fl_exame = '$fl_exame'," .
       "  dia_semana = '$dia_semana'" .
       "  where ref_periodo = '$ref_periodo' and dt_exame = '$dt_exame1' and dia_semana = '$dia_semana1' and ref_campus = '$ref_campus1'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteraçao de Datas de Exame",
            "location='../consulta_exames_periodo_inclui.phtml'",
            "Data do Exame alterada com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
