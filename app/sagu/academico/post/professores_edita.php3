<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id",
                          "ref_professor",
                          "ref_departamento",
                          "dt_ingresso"));

$dt_ingresso = InvData($dt_ingresso);

$conn = new Connection;

$conn->Open();

$sql = " UPDATE professores SET " .
       "        ref_professor = '$ref_professor'," .
       "        ref_departamento = '$ref_departamento'," .
       "        dt_ingresso = '$dt_ingresso' " .
       " WHERE id = '$id'";


$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

SuccessPage("Alteração de Professores",
            "location='../consulta_inclui_professores.phtml'",
            "Professor alterado com sucesso!!!.") 

</script>
</head>
<body>
</body>
</html>
