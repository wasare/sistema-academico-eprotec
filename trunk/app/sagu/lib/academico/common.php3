
<script language="PHP">

  // mensagens

/*
  class Curso
  {
    var $id;
    var $descricao;
  };
*/

  /**
   *
   */
/*
  function GetCurso($id,$SaguAssert)
  {
    $sql = "select id,descricao from cursos where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    $obj = new Curso;

    if ( $query->MoveNext() )
      list( $obj->id,
            $obj->descricao ) = $query->GetRowValues();

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Curso [$id] nao definido!");

    return $obj;
  }
*/
   /**
   *
   */
  function Get_Motivacao($id,$SaguAssert)
  {
    $sql = "select descricao from motivos where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Motivo [$id] nao definido!");

    return $obj;
  }


</script>