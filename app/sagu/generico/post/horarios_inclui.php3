<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("descricao", "ref_turno", "hora_ini", "hora_fim","dt_inicial","dt_final","hora_aula_dia","num_encontros_minimo","ref_periodo"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$dt_inicial = $dt_inicial . "-" . date("Y");
$dt_inicial = InvData($dt_inicial);

$dt_final = $dt_final . "-" . date("Y");
$dt_final = InvData($dt_final);

$sql = " insert into horarios (" .
       "         descricao, " .
       "         turno, " .
       "         hora_ini, " .
       "         hora_fim," .
       "         dt_inicial," .
       "         dt_final," .
       "         hora_aula_dia, " .
       "         num_encontros_minimo, " .
       "         ref_periodo)" .
       " values ('$descricao'," .
       "         '$ref_turno'," .
       "         '$hora_ini'," .
       "         '$hora_fim'," .
       "         '$dt_inicial'," .
       "         '$dt_final', " .
       "         '$hora_aula_dia', " .
       "         '$num_encontros_minimo', " .
       "         '$ref_periodo')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possível inserir o registro!");

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Horarios",
            "location='../horarios_inclui.phtml'",
            "Horário incluído com sucesso!!!.",
            "location='../consulta_inclui_horarios.phtml'");

</script>

</head>
<body>
</body>
</html>
