<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id","descricao", "ref_turno", "hora_ini", "hora_fim","dt_inicial","dt_final","hora_aula_dia","num_encontros_minimo","ref_periodo"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$dt_inicial = $dt_inicial . "-" . date("Y");
$dt_inicial = InvData($dt_inicial);

$dt_final = $dt_final . "-" . date("Y");
$dt_final = InvData($dt_final);

$sql = " update horarios set " .
       "    descricao = '$descricao', " .
       "    turno = '$ref_turno', " .
       "    hora_ini = '$hora_ini', " .
       "    hora_fim = '$hora_fim', " .
       "    dt_inicial = '$dt_inicial', " .
       "    dt_final = '$dt_final', " .
       "    hora_aula_dia = '$hora_aula_dia', " .
       "    num_encontros_minimo = '$num_encontros_minimo', " .
       "    ref_periodo = '$ref_periodo' " .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Alteração de Horários",
	        "location='../consulta_inclui_horarios.phtml'",
	        "As informações do horario <b>$descricao</b> foram atualizadas com sucesso.");
</script>
</head>
<body>
</body>
</html>
