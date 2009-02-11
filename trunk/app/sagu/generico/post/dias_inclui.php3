<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("id", "nome", "abrv"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into dias (id," .
       "                   nome, " .
       "                   abrv)" .
       " values (" .
       "                   '$id'," .
       "                   '$nome'," .
       "                   '$abrv')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Dias",
            "location='../dias_inclui.phtml'",
            "O código do Dia é $id");
</script>

</head>
<body>
</body>
</html>
