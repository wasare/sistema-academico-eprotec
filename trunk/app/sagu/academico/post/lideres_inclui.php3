<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_periodo","ref_disciplina_ofer","ref_disciplina_ofer_compl","ref_pessoa"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into lideres (" .
       "         ref_periodo, " .
       "         ref_pessoa, " .
       "         ref_disciplina_ofer," .
       "         ref_disciplina_ofer_compl) " .
       " values ('$ref_periodo'," .
       "         '$ref_pessoa', " .
       "         '$ref_disciplina_ofer'," .
       "         '$ref_disciplina_ofer_compl')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel inserir o registro!");

SuccessPage("Inclus�o de L�deres de Turma",
            "location='../lideres_inclui.phtml'",
            "L�der de Turma inclu�do com sucesso!!!.",
            "location='../consulta_inclui_lideres.phtml'");

</script>

</head>
<body>
</body>
</html>
