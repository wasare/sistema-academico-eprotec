<? require("../../../../lib/common.php"); ?>

<script language="PHP">

CheckFormParameters(array("id"));

$conn = new Connection;

$conn->Open();

$sql = "delete from calendario_academico_compl where id='$id';";

$ok = $conn->Execute($sql);

$conn->Close();

SaguAssert($ok,"N�o foi poss�vel de excluir o registro!");

SuccessPage("Registro exclu�do com sucesso",
            "location='../consulta_inclui_datas_aulas.phtml'");
</script>
