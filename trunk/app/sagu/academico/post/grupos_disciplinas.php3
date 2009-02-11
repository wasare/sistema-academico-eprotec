<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
  CheckFormParameters(array(
                            "descricao"));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "select nextval('seq_grupos_disciplinas')";
  
$query = $conn->CreateQuery($sql);

$success = false;

if ( $query->MoveNext() )
{
  $id = $query->GetValue(1);
  
  $success = true;
}

$query->Close();

SaguAssert($success,"Nao foi possivel obter um numero para o Grupo!");

$sql = " insert into grupos_disciplinas (" .
       "                               id," .
       "                               descricao)" . 
       " values (" .
       "                               '$id'," .
       "                               '$descricao')";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Grupo de Disciplinas",
            "location='../grupos_disciplinas.phtml'",
            "O id do grupo é <b>$id</b>.",
            "location='../consulta_inclui_grupos_disciplinas.phtml'");
</script>
