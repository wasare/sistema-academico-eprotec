<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("setor"));

$conn = new Connection;

$conn->Open();

$sql = " insert into sagu_setores ( " .
       "        nome_setor," .
       "        email)" . 
       " values ( " .
       "        '$setor'," .
       "        '$email')";


$ok = $conn->Execute($sql);

saguassert($ok,"Nao foi possivel inserir o registro!");

$conn->Close();

SuccessPage("Inclusão de Setor",
            "location='../setores.phtml'",
            "Setor incluído com sucesso!!!.");

</script>
</head>
<body>
</body>
</html>
