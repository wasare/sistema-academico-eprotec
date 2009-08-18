<?php require_once("../../common.php"); ?>

<html>
<head>
<title>Atualização de Filiação</title>
<script language="PHP">

CheckFormParameters(array("id"));

SaguAssert($pai_nome || $mae_nome, "É necessário informar pelo menos o nome do pai ou da mãe!!!");

$conn = new Connection;

$conn->Open();

$sql = " update filiacao set " .
       "    id = '$id'," .
       "    pai_nome = '$pai_nome'," .
       "    pai_fone = '$pai_fone'," .
       "    pai_profissao = '$pai_profissao'," .
       "    pai_instrucao = '$pai_instrucao'," .
       "    pai_loc_trabalho = '$pai_loc_trabalho'," .
       "    mae_nome = '$mae_nome'," .
       "    mae_fone = '$mae_fone'," .
       "    mae_profissao = '$mae_profissao'," .
       "    mae_instrucao = '$mae_instrucao'," .
       "    mae_loc_trabalho = '$mae_loc_trabalho'" .
       "  where id = '$id'";

$ok = $conn->Execute($sql);

$conn->Close();

SaguAssert($ok,"Nao foi possivel atualizar o registro!");

SuccessPage("Filiação Atualizada com Sucesso",
            "location='javascript:window.close()'",
            "");

</script>
