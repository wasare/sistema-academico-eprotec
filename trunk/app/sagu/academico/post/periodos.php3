<?php

require("../../../../lib/common.php");
require("../../lib/InvData.php3");


$id                   = $_POST['id'];
$descricao            = $_POST['descricao'];
$ref_anterior         = $_POST['ref_anterior'];
$ref_cobranca         = $_POST['ref_cobranca'];
$ref_origem           = $_POST['ref_origem'];
$origem               = $_POST['origem'];
$ref_historico        = $_POST['ref_historico'];
$ref_local            = $_POST['ref_local'];
$dt_inicial           = $_POST['dt_inicial'];
$dt_final             = $_POST['dt_final'];
$media                = $_POST['media'];
$media_final          = $_POST['media_final'];
$dt_inicio_aula       = $_POST['dt_inicio_aula'];
$tx_acresc            = $_POST['tx_acresc'];
$tx_cancel            = $_POST['tx_cancel'];
$ref_historico_taxa   = $_POST['ref_historico_taxa'];
$ref_historico_cancel = $_POST['ref_historico_cancel'];
$ref_status_vest      = $_POST['ref_status_vest'];
$fl_gera_financeiro   = $_POST['fl_gera_financeiro'];

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
<html>
<head>
</HEAD>
<BODY></BODY>
</HTML>
