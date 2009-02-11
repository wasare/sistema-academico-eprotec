
<script language="PHP">

  // mensagens


  class Curso
  {
    var $id;
    var $descricao;
  };

  /**
   *
   */
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

  /**
   *
   */
  function GetCursoNome($id,$SaguAssert)
  {
    $sql = "select descricao from cursos where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Curso [$id] nao definido!");

    return $obj;
  }

  /**
   *
   */
  function GetCampusNome($id,$SaguAssert)
  {
    global $g_campus_nome;

    if ( $SaguAssert )
      SaguAssert($id == 0,"Campus [$id] nao definido!");

   return $g_campus_nome;
  }


  /**
   *
   */
  function GetPessoaNome($id,$SaguAssert)
  {
    $sql = "select nome,rg_numero,cod_cpf_cgc from pessoas where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetRowValues();

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Pessoa [$id] nao definido!");

    return $obj;
  }

  /**
   *
   */
  function GetInscricaoPessoa($id,$SaguAssert)
  {
    $sql = "select A.id,A.nome2,A.rg_numero,A.cod_cpf_cgc" .
           "  from pessoas A, vest_inscricoes B" .
           "  where B.id = $id AND A.id = B.ref_pessoa";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetRowValues();

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Pessoa [$id] nao definido!");

    return $obj;
  }

  /**
   *
   */
  function GetLinguaNome($id,$SaguAssert)
  {
    $sql = "select A.descricao from vest_lingua A".
           "  where ( A.descricao = $id )";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Lingua [$id] nao oferecio para o vestibular [$id_vest]!");

    return $obj;
  }



  //
  function VerificaInscricaoUnica($ref_pessoa,$ref_vest)
  {
    $conn = new Connection;

    $conn->Open();

    $sql = "select 1 from vest_inscricoes where ref_pessoa = $ref_pessoa and ref_vestibular = '$ref_vest'";

    $query = $conn->CreateQuery($sql);

    $success = !$query->MoveNext();

    $query->Close();

    $conn->Close();

    SaguAssert($success,"Pessoa ja cadastrada neste vestibular!");
  }

  /**
   *
   */
  function Get_Cidade($id,$SaguAssert)
  {
    $sql = "select nome from aux_cidades where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Cidade [$id] nao definido!");

    return $obj;
  }


    function Get_Filiacao($id,$SaguAssert)
  {
    $sql = "select pai_nome,mae_nome from filiacao where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetRowValues();

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Filiação [$id] nao definida!");

    return $obj;
  }

  function VerificaChaveUnica($tabela, $campo, $valor)
  {
    $conn = new Connection;

    $conn->Open();

    $sql = "select 1 from $tabela where $campo = $valor";

    $query = $conn->CreateQuery($sql);

    $success = !$query->MoveNext();

    $query->Close();

    $conn->Close();

    return $success;
  }


   /**
   *
   */
  function Get_Campus($id,$SaguAssert)
  {
    $sql = "select nome_campus from campus where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Campus [$id] nao definido!");

    return $obj;
  }
  /**
  *
  */
  function Get_Lingua($id,$SaguAssert)
  {
    $sql = "select descricao from vest_lingua where id=$id";

    $conn = new Connection;

    $conn->Open();

    $query = $conn->CreateQuery($sql);

    if ( $query->MoveNext() )
      $obj = $query->GetValue(1);

    $query->Close();

    $conn->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Língua [$id] nao definido!");

    return $obj;
  }


</script>
