<? require("../../../../lib/common.php"); ?>

<html>

<script language="PHP">

CheckFormParameters(array("ref_contrato","opcao"));
$conn = new Connection;
$conn->Open();
if ($opcao=='1')
{
  $sql = "update contratos set dt_desativacao=null, ref_motivo_desativacao=null, ref_last_periodo = '$ref_periodo' where id='$ref_contrato'";
}
else
{
  $sql = "update contratos set dt_desativacao=date(now()), ref_motivo_desativacao='$motivo' where id='$ref_contrato'";
}

$ok = @$conn->Execute($sql);  // tire o @ para visualizar mensagens de error do sistema DB

$conn->Close();

SaguAssert($ok,"Nao foi possivel deletar o registro!");
if ($opcao=='1')
{
  SuccessPage("Reativação de Contrato",
              "history.go(-2)");
}
else
{
  SuccessPage("Cancelamento de Contrato",
              "history.go(-2)");
}

</script>
</html>
