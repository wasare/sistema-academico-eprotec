<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_periodo","ref_disciplina_ofer","ref_disciplina_ofer_compl","data_nova"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$data_antiga = InvData($data_antiga);
$data_nova = InvData($data_nova);

$sql = " insert into calendario_academico_compl (" .
       "         ref_periodo, " .
       "         ref_disciplina_ofer," .
       "         ref_disciplina_ofer_compl," .
       "         data_antiga, " .
       "         data_nova) " .
       " values ('$ref_periodo'," .
       "         '$ref_disciplina_ofer'," .
       "         '$ref_disciplina_ofer_compl',";
       
       if ($data_antiga)
       {
           $sql .= " '$data_antiga',";
       }
       else
       {
           $sql .= " null,";
       }
       
$sql.= "         '$data_nova')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Datas de Aulas Remanejadas",
            "location='../datas_aula_inclui.phtml'",
            "Data de Aula Remanejada incluída com sucesso!!!.",
            "location='../consulta_inclui_datas_aulas.phtml'");

</script>

</head>
<body>
</body>
</html>
