<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("nome",
                          "texto",
                          "ref_setor"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = " insert into carimbos (nome, texto, ref_setor)" . 
       " values ('$nome', '$texto', '$ref_setor')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SuccessPage("Inclusão de Carimbos",
            "location='../carimbos.phtml'",
            "Carimbo incluído com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
