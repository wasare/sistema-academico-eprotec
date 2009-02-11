<? require("../../../../lib/common.php"); ?>

<html>
<head>

<script language="PHP">
CheckFormParameters(array(
                          "id_old",
                          "id_new"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = " insert into cadastro_duplo (" .
       "                               id_old," .
       "                               id_new," .
       "                               tipo)" . 
       " values (" .
       "                               '$id_old'," .
       "                               '$id_new'," .
       "                               'pessoas')";


$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SuccessPage("Inclusão de Cadastro Duplo",
            "location='../cadastro_duplo.phtml'",
            "Cadastro Duplo incluído com sucesso!!!.");

</script>

</head>
<body>
</body>
</html>
