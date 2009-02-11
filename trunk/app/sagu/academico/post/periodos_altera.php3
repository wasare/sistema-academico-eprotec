<? require("../../../../lib/common.php"); ?>
<? require("../../lib/InvData.php3"); ?>
<html>
<head>
<script language="PHP">

CheckFormParameters(array("id",
                          "ref_anterior",
                          "ref_cobranca",
                          "ref_historico",
                          //"ref_historico_dce",
                          "descricao",
                          "dt_inicial",
                          "dt_final",
                          //"tx_dce_normal",
                          //"tx_dce_vest",
                          "ref_historico_taxa",
                          "ref_historico_cancel",
                          //"tx_acresc",
                          //"tx_cancel",
                          //"tx_banco",
                          //"ref_status_vest",
                          "ref_local",
                          "ref_origem",
                          //"fl_livro_matricula",
                          "media",
                          "media_final",
                          "dt_inicio_aula"));

$ref_historico_dce = $ref_historico_dce ? $ref_historico_dce : '0';

$conn = new Connection;

$conn->Open();
$conn->Begin();

if ( $fl_livro_matricula == "yes" )
{ $fl_livro_matricula = '1'; }
else
{ $fl_livro_matricula = '0'; }

$dt_inicial = InvData($dt_inicial);
$dt_final = InvData($dt_final);
$dt_inicio_aula = InvData($dt_inicio_aula);

$tx_acresc = $tx_acresc ? $tx_acresc : '0';
$tx_cancel = $tx_cancel ? $tx_cancel : '0';
              
$sql = " update periodos set " .
       "    id = '$id'," .
       "    ref_anterior = '$ref_anterior'," .
       "    ref_cobranca = '$ref_cobranca'," .
       "    ref_origem = '$ref_origem'," .
       "    ref_historico = '$ref_historico'," .
//       "    ref_historico_dce = '$ref_historico_dce'," .
       "    descricao = '$descricao'," .
       "    dt_inicial = '$dt_inicial'," .
       "    dt_final = '$dt_final'," .
//       "    tx_dce_normal = '$tx_dce_normal'," .
//       "    tx_dce_vest = '$tx_dce_vest'," .
       "    ref_historico_taxa = '$ref_historico_taxa'," .
       "    ref_historico_cancel = '$ref_historico_cancel'," .
       "    tx_acresc = '$tx_acresc'," .
       "    tx_cancel = '$tx_cancel'," .
       "    tx_banco = '$tx_banco'," .
//       "    ref_status_vest = '$ref_status_vest'," .
       "    ref_local = '$ref_local'," .
//       "    fl_livro_matricula = '$fl_livro_matricula'," .
       "    fl_gera_financeiro = '$fl_gera_financeiro'," .
       "    media = '$media', " .
       "    media_final = '$media_final', " .
       "    dt_inicio_aula = '$dt_inicio_aula'" .
       " where id = '$id'";

$ok = $conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Finish();
$conn->Close();

SaguAssert($ok,"Não foi possível alterar o registro!");
SuccessPage("Alteração de Período",
            "location='../consulta_periodos.phtml'",
            "Período alterado com sucesso.");
</script>
