<!-- Função que Verifica Data Limite contábil               -->
<!-- Usada para impedir lançamentos novos dentro do limite  -->
<!-- Autor Pablo Dall'Oglio, Ultima modificação: 10/01/2001 -->

<script language="PHP">
function CheckDataLimite($data_limite,$conn)
{
  $sql_test = "select '$data_limite' > max(data) from limites_contabeis";
  $query = $conn->CreateQuery($sql_test);

  if ( $query->MoveNext() )
     list($is_liberado) = $query->GetRowValues();
  $query->Close();
  return ($is_liberado=='t');
}
</script>
