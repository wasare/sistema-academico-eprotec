<?
  /**
   *
   */
  function GetLocalPgto($id,$SaguAssert)
  {
    $sql = "select id,descricao from locais_pgto where id = '$id'";

    $conn = new Connection;

    $conn->Open();

    $query = @$conn->CreateQuery($sql);

    if ( @$query->MoveNext() )
      $obj = $query->GetValue(2);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Local [<b><i>$id</b></i>] não cadastrada ou código inválido!");

    return $obj;
  }

?>