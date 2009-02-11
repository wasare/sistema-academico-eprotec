<? require("../../../../lib/common.php"); ?>
<? require("../../lib/VerificaChaveUnica.php3"); ?>

<html>
<head>
<script language="PHP">
  CheckFormParameters(array("periodo_id",
                            "texto",
            			    "fonte",
            			    "tamanho",
            			    "sequencia",
            			    "ref_status",
            			    "fl_ouvinte"
                            ));

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "select nextval('seq_mensagens')";

  $query = $conn->CreateQuery($sql);

  $success = false;

  if ( $query->MoveNext() )
  {
    $id = $query->GetValue(1);

    $success = true;
  }

  $query->Close();

SaguAssert($success,"Nao foi possivel obter um código para a mensagem!");

$sql = " insert into mensagens ( id," .
       "                         ref_periodo," .
       "                         texto," .
       "                         fonte," .
       "                         tamanho," .
       "                         sequencia, " .
       "	            		 ref_status, " . 
       "            			 fl_ouvinte) " . 
       " values ('$id'," .
       "         '$periodo_id'," .
       "         '$texto'," .
       "         '$fonte'," .
       "         '$tamanho'," .
       "         '$sequencia'," .
       "         '$ref_status'," .
       "         '$fl_ouvinte')";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Finish();
$conn->Close(); 

SaguAssert($ok,"Nao foi possivel inserir o registro!");

SuccessPage("Inclusão de Mensagens",
            "location='../cadastra_mensagens.phtml'",
            "Mensagem incluída com sucesso!!!",
            "location='../consulta_inclui_mensagem.phtml'");

</script>
</head>
<body bgcolor="#FFFFFF">
</body>
</html>
