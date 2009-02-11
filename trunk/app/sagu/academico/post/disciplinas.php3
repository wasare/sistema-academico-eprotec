<? require("../../../../lib/common.php"); ?>
<? require("../../lib/VerificaChaveUnica.php3"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("id",
                          "descricao_disciplina"));

SaguAssert(VerificaChaveUnica("disciplinas", "id", "$id"), "Código já existente");

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = " insert into disciplinas ( " .
       "        id," .
       "        ref_grupo," .
       "        ref_departamento," .
       "        descricao_disciplina," .
       "        descricao_extenso," .
       "        num_creditos," .
       "        carga_horaria " . 
       " ) values (" .
       "        '$id'," .
       "        '$ref_grupo'," .
       "        '$ref_departamento'," .
       "        '$descricao_disciplina'," .
       "        '$descricao_extenso'," .
       "        '$num_creditos'," .
       "        '$carga_horaria')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close(); 

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Disciplinas",
            "location='../disciplinas.phtml'",
            "Disciplina incluída com sucesso!!!",
            "location='../consulta_disciplinas.phtml'");

</script>
</head>
<body>
</body>
</html>
