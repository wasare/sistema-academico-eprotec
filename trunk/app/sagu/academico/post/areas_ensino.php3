<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array(
                          "area"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = " insert into areas_ensino (" .
       "                               area)" . 
       " values (" .
       "                               '$area')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SuccessPage("Inclusão de Áreas de Ensino",
            "location='../areas_ensino.phtml'",
            "Área de Ensino incluída com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
