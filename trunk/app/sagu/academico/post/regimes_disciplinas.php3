<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("descricao"));

$conn = new Connection;

$conn->Open();

$sql = " insert into regimes_disciplinas ( " .
       "        descricao)" . 
       " values ( " .
       "        '$descricao')";


$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

SuccessPage("Inclusão de Regimes das Disciplinas",
            "location='../regimes_disciplinas.phtml'",
            "Regimes das Disciplinas incluído com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
