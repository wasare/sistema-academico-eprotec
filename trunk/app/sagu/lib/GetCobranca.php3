<?
  /**
   *
   */
  function GetCobranca($id,$SaguAssert)
  {
    $sql = "select id,descricao from tipos_cobr where id = '$id'";

    $conn = new Connection;

    $conn->Open();

    $query = @$conn->CreateQuery($sql);

    if ( @$query->MoveNext() )
      $obj = $query->GetValue(2);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Cobran�a [<b><i>$id</b></i>] n�o cadastrada ou c�digo inv�lido!");

    return $obj;
  }

?>