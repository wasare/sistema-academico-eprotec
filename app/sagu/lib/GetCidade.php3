<?
  /**
   *
   */
  function GetCidade($id,$SaguAssert)
  {
    $sql = "select nome from aux_cidades where id = '$id'";

    $conn = new Connection;

    $conn->Open();

    $query = @$conn->CreateQuery($sql);

    if ( @$query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Cidade [<b><i>$id</b></i>] n�o cadastrada ou c�digo Inv�lido!");

    return $obj;
  }

?>