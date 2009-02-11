<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("descricao",
                          "ref_tipo_motivo"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = "select nextval('seq_motivos')";

$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id = $query->GetValue(1);
  
  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero de motivo!");

$sql = " insert into motivos ( " .
       "        id," .
       "        descricao, " .
       "        ref_tipo_motivo)" . 
       " values ( " .
       "        '$id'," .
       "        '$descricao', " .
       "        '$ref_tipo_motivo')";


$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SuccessPage("Inclusão de Motivos",
            "location='../motivos.phtml'",
            "Motivo incluído com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
