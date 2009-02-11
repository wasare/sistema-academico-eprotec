<? require("../../../../lib/common.php"); ?>

<html>
<head>
<script language="PHP">
CheckFormParameters(array("razao_social",
                          "sigla",
                          "rua",
                          "complemento",
                          "bairro",
                          "cep",
                          "ref_cidade"));

$id_config_empresa = GetIdentity("seq_configuracao_empresa");

$conn = new Connection;
$conn->Open();
$conn->Begin();

$sql = "insert into configuracao_empresa" .
       "  (" .
       "    id," .
       "    razao_social," .
       "    sigla," .
       "    logotipo," .
       "    rua," .
       "    complemento," .
       "    bairro," .
       "    cep," .
       "    ref_cidade" .
       "  )" .
       "  values" .
       "  (" .
       "    '$id_config_empresa'," .
       "    '$razao_social'," .
       "    '$sigla'," .
       "    '$logotipo'," .
       "    '$rua'," .
       "    '$complemento'," .
       "    '$bairro'," .
       "    '$cep'," .
       "    '$ref_cidade'" .
       "  )";

$ok = $conn->Execute($sql);

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Nao foi possivel inserir o registro!");
SuccessPage("Inclusão de Empresa",
            "location='../configuracao_empresa.phtml'",
            "O código da empresa é <b>$id_config_empresa</b>.");
</script>
