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

SaguAssert($ok,"N�o foi poss�vel inserir o registro!");

SuccessPage("Inclus�o de Turnos",
            "location='../turnos_inclui.phtml'",
            "O c�digo do Turno � $id");
</script>

</head>
<body>
</body>
</html>
