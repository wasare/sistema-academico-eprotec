<? require("../../../../lib/common.php"); ?>

<html>
    <head>
        <title>Cadastro de Cidade</title>

        <script language="PHP">

CheckFormParameters(array("nome",
                          "cep",
                          "ref_pais",
                          "ref_estado"));

$id = GetIdentity('seq_aux_cidades');

$conn = new Connection;

$conn->Open();
$conn->Begin();

$sql = "insert into aux_cidades (" .
       "     id," .
       "     nome," .
       "     cep," .
       "     ref_pais," .
       "     ref_estado" .
       " ) values (" .
       "     '$id'," .
       "     '$nome'," .
       "     '$cep'," .
       "     '$ref_pais'," .
       "     '$ref_estado'" .
       " )";

$ok = $conn->Execute($sql);

$err = $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!<br><br>$err");

SuccessPage("Inclusão de Cidades",
            "location='../cidades_inclui.phtml'",
            "O código da Cidade é $id",
            "location='../consulta_cidades.phtml'");

        </script>
    </head>
    <body>
    </body>
</html>
