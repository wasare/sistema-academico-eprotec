<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array( "nome", "abrv"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$id = $abrv;

$sql = " insert into turnos (id," .
       "                     nome, " .
       "                     abrv)" .
       " values (" .
       "                     '$id'," .
       "                     '$nome'," .
       "                     '$abrv')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Turnos",
            "location='../turnos_inclui.phtml'",
            "O código do Turno é $id");
</script>

</head>
<body>
</body>
</html>
