<? require("../../../../lib/common.php"); ?>
<html>
<head>
<title>Cadastro de Conv�nio M�dico</title>
<script language="PHP">

CheckFormParameters(array("nome"));

$id = GetIdentity('seq_convenios_medicos');

$conn = new Connection;

$conn->Open();
$conn->Begin();
    
$sql = " insert into convenios_medicos (" .
       "     id," .
       "     nome" .
       " ) values (" .
       "     '$id'," .
       "     '$nome'" .
       " )";

$ok = $conn->Execute($sql);

$err = $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!<br><br>$err");

SuccessPage("Inclus�o de Conv�nios M�dicos",
            "location='../convenios_inclui.phtml'",
            "O c�digo do Conv�nio M�dico � $id");

</script>
</head>
<body>
</body>
</html>
