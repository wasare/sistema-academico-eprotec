<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array("descricao_depto"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into departamentos (descricao)" .
       " values ('$descricao_depto')";



$ok = $conn->Execute($sql);

$err= $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel inserir o registro!<BR><BR>$err");

SuccessPage("Inclus�o de Departamento",
            "location='../departamento_inclui.phtml'",
            "Departamento inclu�do com sucesso!!!",
            "location='../consulta_inclui_departamentos.phtml'");
</script>
</HEAD>
<BODY></BODY>
</HTML>
