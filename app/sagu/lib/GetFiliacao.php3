<?
  /**
   *
   */
  function GetFiliacao($id,$SaguAssert)
  {
    $sql = "select pai_nome,mae_nome from filiacao where id = '$id'";

    $conn = new Connection;

    $conn->Open();

    $query = @$conn->CreateQuery($sql);

    if ( @$query->MoveNext() )
      $obj = $query->GetRowValues();

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Filia��o [<b><i>$id</b></i>] n�o cadastrada ou c�digo inv�lido!");

    return $obj;
  }

?>