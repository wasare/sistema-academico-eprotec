<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array(
                          "nome",
                          "sucinto"));

$conn = new Connection;

$conn->Open();
$conn->Begin();
  
$sql = "select nextval('seq_cursos_externos')";

$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id = $query->GetValue(1);
  
  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero de motivo!");

$sql = "insert into cursos_externos (" .
       "                               id," .
       "                               nome," .
       "                               sucinto," . 
       "                               obs)" .
       "       values (" .
       "                               '$id'," .
       "                               '$nome'," .
       "                               '$sucinto'," .
       "                               '$obs')";

$ok = $conn->Execute($sql);

SaguAssert($ok,"Nao foi possivel inserir o registro!");

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!");

SuccessPage("Inclusão de Cursos Externos",
            "location='../inclui_curso_externo.phtml'",
            "O código do Curso Externo é $id",
            "location='../consulta_inclui_curso_externo.phtml'");
</script>
</head>
<body>
</body>
</html>
