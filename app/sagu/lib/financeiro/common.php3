
<script language="PHP">

/*
  // mensagens

  class Curso
  {
    var $id;
    var $descricao;
  };
*/

  /**
   *
   */
  function GetEmpresa($id,$SaguAssert)
  {
    $sql = "select id,razao_social from configuracao_empresa where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(2);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Empresa [$id] nao definido!");

    return $obj;
  }

 /**
   *
   */
  function GetBolsa($id,$SaguAssert)
  {
    $sql = "select cod_bolsa,descricao from aux_bolsas where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1) . $query->GetValue(2);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Bolsa [$id] nao definido!");

    return $obj;
  }

 /**
   *
   */
  function GetCursoConta($id,$SaguAssert)
  {
    $sql = "select curso_desc(ref_curso) from contas where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Conta [$id] nao definido!");

    return $obj;
  }

 /**
   *
   */
  function GetHistorico($id,$SaguAssert)
  {
    $sql = "select id,descricao from historicos where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(2);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Historicos [$id] nao definido!");

    return $obj;
  }

</script>