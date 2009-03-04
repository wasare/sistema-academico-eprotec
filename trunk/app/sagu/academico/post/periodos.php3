<?php

require("../../../../lib/common.php");
//require("../../lib/vestibular/common.php3");
require("../../lib/InvData.php3");

?>
<html>
<head>
<?php

CheckFormParameters(array("id",
                          "descricao",
                          "dt_inicial",
                          "dt_final",
                          "ref_historico",
                          "ref_historico_taxa",
                          "ref_historico_cancel",
                          "media",
                          "media_final",
                          "dt_inicio_aula"));

$dt_inicial = InvData($dt_inicial);
$dt_final = InvData($dt_final);
$dt_inicio_aula = InvData($dt_inicio_aula);

if ( $fl_livro_matricula == "yes" )
{ $fl_livro_matricula = '1'; }
else
{ $fl_livro_matricula = '0'; }

$conn = new Connection;

$conn->Open();
$conn->Begin();

$ref_historico_dce = $ref_historico_dce ? $ref_historico_dce : 0;

$sql = " insert into periodos (id," .
       "                       descricao," .
       "                       ref_anterior," .
       "                       ref_cobranca," .
       "                       ref_origem," .
       "                       ref_historico," .
       "                       ref_historico_bolsa," .
       "                       ref_historico_dce," .
       "                       dt_inicial," .
       "                       dt_final," .
       "                       ref_local," .
       "                       tx_dce_normal," .
       "                       tx_dce_vest," .
       "                       ref_historico_taxa," .
       "                       ref_historico_cancel," .
       "                       tx_acresc," .
       "                       tx_cancel," .
       "                       tx_banco," .
       "                       ref_status_vest," .
       "                       fl_livro_matricula," .
       "                       media, " .
       "                       media_final, " .
       "                       fl_gera_financeiro, " .
       "                       dt_inicio_aula)" .
       "  values (" .
       "                       '$id'," .
       "                       '$descricao'," .
       "                       '$ref_anterior'," .
       "                       '$ref_cobranca'," .
       "                       '$ref_origem'," .
       "                       '$ref_historico'," .
       "                       '0'," .
       "                       '$ref_historico_dce'," .
       "                       '$dt_inicial'," .
       "                       '$dt_final'," .
       "                       '$ref_local'," .
       "                       '$tx_dce_normal'," .
       "                       '$tx_dce_vest'," .
       "                       '$ref_historico_taxa'," .
       "                       '$ref_historico_cancel'," .
       "                       '$tx_acresc'," .
       "                       '$tx_cancel'," .
       "                       '$tx_banco'," .
       "                       '$ref_status_vest'," .
       "                       '$fl_livro_matricula'," .
       "                       '$media'," .
       "                       '$media_final'," .
       "                       '$fl_gera_financeiro'," .
       "                       '$dt_inicio_aula')";

$ok = $conn->Execute($sql);

$err= $conn->GetError();

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível inserir o registro!<BR><BR>$err");

SuccessPage("Inclusão de Período",
            "location='../periodos.phtml'",
            "O código do período é <b>$id</b>.",
            "location='../consulta_periodos.phtml'");
?>
</HEAD>
<BODY></BODY>
</HTML>
