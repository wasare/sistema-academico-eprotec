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

SuccessPage("Inclus�o de �reas de Ensino",
            "location='../areas_ensino.phtml'",
            "�rea de Ensino inclu�da com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
