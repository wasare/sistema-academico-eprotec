<!-- Fun��o que Retorna Parametros Financ. e Cont�b.        -->
<!-- Usada para Obter par�metros financeiros e cont�beis    -->
<!-- Autor Pablo Dall'Oglio, Ultima modifica��o: 11/06/2001 -->

<script language="PHP">
function GetIntParam($conn, $param)
{

  $sql = "select int4(conteudo) from conf_finan_cont where chave='$param'";
  $query = $conn->CreateQuery($sql);
  if ($query)
  {
    if ($query->MoveNext())
    { $retorno = $query->GetValue(1); }
    else
    { $retorno = null; }

    $query->Close();
    return $retorno;
  }
  else
  {
    return null;
  }
}
</script>
