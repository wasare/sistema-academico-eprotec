<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("nome"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "select nextval('seq_aux_paises')";

$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id_paises = $query->GetValue(1);

  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero do Pa�s!");    

$sql = " insert into aux_paises (id," .
       "                         nome)" .
       " values (" .
       "                         '$id_paises'," .
       "                         '$nome')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SaguAssert($ok,"N�o foi poss�vel inserir o registro!");

SuccessPage("Inclus�o de Pa�ses",
            "location='../paises_inclui.phtml'",
            "O c�digo do Pa�s � $id_paises");
</script>

</head>
<body>
</body>
</html>
