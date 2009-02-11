<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id","data_nova"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$data_antiga = InvData($data_antiga);
$data_nova = InvData($data_nova);

$sql = " update calendario_academico_compl set ";

       if ($data_antiga)
       {
           $sql .= " data_antiga = '$data_antiga', ";
       }
       else
       {
           $sql .= " data_antiga = null, ";
       }
       
$sql.= "    data_nova = '$data_nova' " .
       " where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel de atualizar o registro!");

SuccessPage("Inclusão de Datas de Aulas Remanejadas",
	        "location='../consulta_inclui_datas_aulas.phtml'",
	        "As informações da Aula <b>$descricao</b> foram atualizadas com sucesso.");

</script>
</head>
<body>
</body>
</html>
