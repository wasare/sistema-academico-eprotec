<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array(
                          "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = "insert into tipos_motivos (descricao) values ('$descricao')";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SuccessPage("Inclusão de Tipos de Motivos",
            "location='../tipos_motivos.phtml'",
            "Tipo de Motivo incluído com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
