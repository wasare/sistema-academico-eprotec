<? require("../../../../lib/common.php"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_curso",
                          "ref_disciplina",
                          "tipo"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$ref_disciplina_pre = $ref_disciplina_pre ? $ref_disciplina_pre : 'NULL';

$sql = "insert into pre_requisitos ( " .
       "    ref_curso," .
       "    ref_disciplina," .
       "    ref_disciplina_pre," .
       "    ref_area," .
       "    horas_area," .
       "    tipo" .
       "  ) values ( " .
       "    '$ref_curso'," .
       "    '$ref_disciplina'," .
       "    $ref_disciplina_pre," .
       "    '$ref_area'," .
       "    '$horas_area'," .
       "    '$tipo'" .
       "  )";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Pré-Requisito incluído com sucesso!",
            "location='../inclui_pre_requisito.phtml'",
            "Pré-Requisito incluído com sucesso!",
            "location='../consulta_inclui_pre_requisito.phtml'");

</script>

</head>
<body bgcolor="#FFFFFF">
</body>
</html>
