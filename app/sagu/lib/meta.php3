<!-- This script contains the meta schema of the database objects we deal with.--> 
<script language="PHP">
// load database abstraction layer and common functions
require("../../../lib/common.php");

// declaration of a class for customer objects
class Customer
{
  var $number;
  var $title;
  var $name;
  var $address;
  var $location;
  var $country;
  var $email;
  var $phone;
};

// declaration of a class for license objects
class License
{
  var $number;
  var $customer;
  var $software;
  var $seats;
  var $date;
};

// declaration of a class for software objects
class Software
{
  var $number;
  var $title;
  var $description;
};
    
class Meta extends Connection
{
  function GetCustomer($number,$SaguAssert=false)
  {
    $sql = "select id,title,name,address,location,country,email,phone from customer where id='$number'";

    // debug($sql);

    $query = $this->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
      $obj = new Customer;

      list( $obj->number,
            $obj->title,
            $obj->name,
            $obj->address,
            $obj->location,
            $obj->country,
            $obj->email,
            $obj->phone ) = $query->GetRowValues();
    }

    $query->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Customer [$number] not found");

    return $obj;
  }

  function GetLicense($number,$SaguAssert=false)
  {
    $sql = "select id,ref_customer,ref_software,dt_exp,num_seats from license where id='$number'";

    // debug($sql);

    $query = $this->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
      $obj = new License;

      list( $obj->number,
            $obj->customer,
            $obj->software,
            $obj->date,
            $obj->seats ) = $query->GetRowValues();
    }

    $query->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"License [$number] not found");

    return $obj;
  }

  function GetSoftware($number,$SaguAssert=false)
  {
    $sql = "select id,title,info from software where id='$number'";

    // debug($sql);

    $query = $this->CreateQuery($sql);

    if ( $query->MoveNext() )
    {
      $obj = new Software;

      list( $obj->number,
            $obj->title,
            $obj->description ) = $query->GetRowValues();
    }

    $query->Close();

    if ( $SaguAssert )
      SaguAssert(!empty($obj),"Software [$number] not found");

    return $obj;
  }

  function GetKeyCodes($number,$SaguAssert=false)
  {
    $sql = "select id_seqno,value from keycode where id_license = '$number' order by id_seqno";

    // debug($sql);

    $query = $this->CreateQuery($sql);
    
    $n = $query->GetRowCount();

    if ( $n > 0 )
    {
      $i = 0;

      while ( $query->MoveNext() )
        $obj[$i++] = $query->GetValue(2);
    }
    
    $query->Close();

    if ( $SaguAssert )
      SaguAssert($n>0,"No Keycode(s) for license [$number] found");

    return $obj;
  }

  function CheckResultNotEmpty($sql,$msg)
  {
    $query = $this->CreateQuery($sql);
    
    $success = $query->MoveNext();

    $query->Close();

    SaguAssert($success,$msg);
  }

  function CheckCustomerNumber($number)
  {
    $sql = "select 1 from customer where id = '$number'";
    $msg = "Customer number '$number' does not exist!";

    $this->CheckResultNotEmpty($sql,$msg);
  }

  function CheckSoftwareNumber($number)
  {
    $sql = "select 1 from software where id = '$number'";
    $msg = "Software number '$number' does not exist!";

    $this->CheckResultNotEmpty($sql,$msg);
  }
};
</script>
