<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array(
                          "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = "insert into tipos_curso (descricao) values ('$descricao')";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SuccessPage("Inclus�o de Tipos de Cursos",
            "location='../tipos_curso.phtml'",
            "Tipo de Curso inclu�do com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>