<!-- Fun��o que Verifica Data Limite cont�bil               -->
<!-- Usada para impedir lan�amentos novos dentro do limite  -->
<!-- Autor Pablo Dall'Oglio, Ultima modifica��o: 10/01/2001 -->

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
