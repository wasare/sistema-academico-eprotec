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

$sql = "insert into dt_exames_periodos" .
       "  (" .
       "    ref_periodo," .
       "    ref_campus," .
       "    dt_exame," .
       "    descricao," .
       "    fl_exame," .
       "    dia_semana" .
       "  )" .
       "  values" .
       "  (" .
       "    '$ref_periodo'," .
       "    '$ref_campus'," .
       "    '$dt_exame'," .
       "    '$descricao'," .
       "    '$fl_exame'," .
       "    '$dia_semana'" .
       "  )";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Datas de Exame",
            "location='../exames_periodo_inclui.phtml'",
            "Data do Exame incluído com sucesso!!!.",
            "location='../consulta_exames_periodo_inclui.phtml'");

</script>
</head>
<body>
</body>
</html>
