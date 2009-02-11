<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">

CheckFormParameters(array("ref_empresa",
                          "nome_campus",
                          "cidade_campus"));

$id_campus = GetIdentity('seq_campus');

$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = "insert into campus" .
       "  (" .
       "    id," .
       "    ref_empresa," .
       "    nome_campus," .
       "    cidade_campus" .
       "  )" .
       "  values" .
       "  (" .
       "    $id_campus," .
       "    '$ref_empresa'," .
       "    '$nome_campus'," .
       "    '$cidade_campus'" .
       "  )";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");
SuccessPage("Inclusão de Campus",
            "location='../campus_inclui.phtml'",
            "O código do campus é <b>$id_campus</b>.");
</script>
