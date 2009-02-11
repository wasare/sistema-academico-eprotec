<script language="PHP">
// load database abstraction layer
require("../../../lib/common.php");


// ---------------------------------------------------------------------------
// Textos de mensagens
// ---------------------------------------------------------------------------
$msg_0001 = "Cannot be empty!";
$msg_0002 = "Vestibular not found: ";


// ---------------------------------------------------------------------------
// Definicao de classes e objetos
// ---------------------------------------------------------------------------

class Vestibular
{
  var $id;
  var $ref_empresa;
  var $descricao;
  var $observacao;
  var $max_pontos;
  var $min_valido;
  var $ensalamento_lingua; 
};

// returns a vestibular object for the specified number
function GetVestibular($id,$SaguAssert)
{
  global $msg_0001,$msg_0002;

  // strip leading and trailing blanks
  $id = trim($id);

  // check for valid parameter content
  CheckInputValue("ID",$id != "",$msg_0001);

  $conn = new Connection;
  $conn->Open();
  
  // definicao comando SQL
  $sql = "select id, ref_empresa, descricao, observacao," .
         "       max_pontos,min_valido, ensalamento_lingua" .
	 "  from vestibular where id='$id'";

  // debug($sql);

  $query = $conn->CreateQuery($sql);

  if ( $query->MoveNext() )
  {
    $obj = new Vestibular;

    list( $obj->id,
          $obj->ref_empresa,
          $obj->descricao,
          $obj->observacao,
	  $obj->max_pontos,
	  $obj->min_valido,
	  $obj->ensalamento_lingua
        ) = $query->GetRowValues();
  }

  $query->Close();
  
  $conn->Close();

  if ( $SaguAssert )
    SaguAssert(!empty($obj),$msg_0002 . $id);

  return $obj;
}

</script>

