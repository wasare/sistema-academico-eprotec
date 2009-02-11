<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<HTML>
<HEAD>

<script language="PHP">
$conn = new Connection;

$conn->Open();

$dt_exame = InvData($dt_exame);

$sql = " delete from dt_exames_periodos " .
       " where ref_periodo = '$ref_periodo' and " .
       "       dt_exame = '$dt_exame' and " .
       "       dia_semana = '$dia_semana' and " .
       "       ref_campus = '$ref_campus'";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Não foi possível de excluir o registro!");

SuccessPage("Registro excluído com sucesso",
            "location='../consulta_exames_periodo_inclui.phtml'");
</script>
</HEAD>
<BODY></BODY>
</HTML>
