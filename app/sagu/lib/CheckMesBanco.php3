<!-- Função que verifica se para determinado mês de um      -->
<!-- período acadêmico, já foi emitido cobrança bancária    -->
<!-- Autor Pablo Dall'Oglio, Ultima modificação: 08/02/2001 -->

<script language="PHP">
function CheckMesBanco($ref_periodo, $seq_titulo, $conn)
{
  $sql = "Select datahora from log_titulos where ref_periodo='$ref_periodo' and sequencia='$seq_titulo'";
  $query = $conn->CreateQuery($sql);

  $retorno = !$query->MoveNext();
  $query->Close();
  return $retorno;
}
</script>
