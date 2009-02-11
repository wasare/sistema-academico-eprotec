<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("ref_professor",
                          "ref_departamento",
                          "dt_ingresso"));

$dt_ingresso = InvData($dt_ingresso);

$conn = new Connection;

$conn->Open();

$sql = " insert into professores ( " .
       "        ref_professor," .
       "        ref_departamento," .
       "        dt_ingresso)" . 
       " values ( " .
       "        '$ref_professor'," .
       "        '$ref_departamento', " .
       "        '$dt_ingresso')";


$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

SuccessPage("Inclusão de Professores",
            "location='../professores_inclui.phtml'",
            "Professor incluído com sucesso!!!.", 
            "location='../consulta_inclui_professores.phtml'");

</script>
</head>
<body>
</body>
</html>
