<?
function Busca_Nome($id)
{
  $conn = new Connection;
  $conn-> Open();

  $sql = " select nome " .
         " from pessoas " .
         " where id = '$id'";
 
  $query = $conn->CreateQuery($sql);
  $sucess = false;
  if ( $query->MoveNext() )
  {
      $sucess = true;
      list ( $nome ) = $query->GetRowValues();
  }
  SaguAssert($sucess,"Pessoa n�o Cadastrada!");
  $query->Close();
  $conn->Close();
  return $nome;
}
?>
