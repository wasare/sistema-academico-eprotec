<!-- Função que Retorna Parametros Financ. e Contáb.        -->
<!-- Usada para Obter parâmetros financeiros e contábeis    -->
<!-- Autor Pablo Dall'Oglio, Ultima modificação: 11/06/2001 -->

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
