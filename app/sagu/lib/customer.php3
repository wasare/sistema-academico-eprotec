
<script language="PHP">
// load database abstraction layer
require("meta.php3");

function InsertCustomer($number,$title,$name,$address,$location,$country,$email,$phone)
{
  // strip leading and trailing spaces from parameters
  $number   = trim($number);
  $title    = trim($title);
  $name     = trim($name);
  $address  = trim($address);
  $location = trim($location);
  $country  = trim($country);
  $email    = trim($email);
  $phone    = trim($phone);

  // check for valid input values
  CheckInputValue("number",$number!="","Cannot be empty");
  CheckInputValue("title",$title!="","Cannot be empty");
  CheckInputValue("name",$name!="","Cannot be empty");
  CheckInputValue("address",$address!="","Cannot be empty");
  CheckInputValue("location",$location!="","Cannot be empty");
  CheckInputValue("country",$country!="","Cannot be empty");
  CheckInputValue("email",$email!="","Cannot be empty");
  CheckInputValue("phone",$phone!="","Cannot be empty (enter 00 if not available)");

  // the SQL insert statement for the new customer
  $sql = "insert into customer (id,title,name,address,location,country,email,phone)" .
         "  values ('$number','$title','$name','$address','$location','$country','$email','$phone')";

  $conn = new Meta;
  $conn->Open();
  if ( $conn->Execute($sql) )
    $customer = $conn->GetCustomer($number,true);
  $conn->Close();
  $conn->CheckError();

  SaguAssert(!empty($customer),"No valid customer object");

  return $customer;
}

function UpdateCustomer($number,$title,$name,$address,$location,$country,$email,$phone)
{
  // strip leading and trailing spaces from parameters
  $number   = trim($number);
  $title    = trim($title);
  $name     = trim($name);
  $address  = trim($address);
  $location = trim($location);
  $country  = trim($country);
  $email    = trim($email);
  $phone    = trim($phone);

  // check for valid input values
  CheckInputValue("number",$number!="","Cannot be empty");
  CheckInputValue("title",$title!="","Cannot be empty");
  CheckInputValue("name",$name!="","Cannot be empty");
  CheckInputValue("address",$address!="","Cannot be empty");
  CheckInputValue("location",$location!="","Cannot be empty");
  CheckInputValue("country",$country!="","Cannot be empty");
  CheckInputValue("email",$email!="","Cannot be empty");
  CheckInputValue("phone",$phone!="","Cannot be empty (enter 00 if not available)");

  // build up the SQL update statement
  $sql = "update customer set       " .
         "  title    = '$title',    " .
         "  name     = '$name',     " .
         "  address  = '$address',  " .
         "  location = '$location', " .
         "  country  = '$country',  " .
         "  email    = '$email',    " .
         "  phone    = '$phone'     " .
         "where id = '$number'";

  $conn = new Meta;
  $conn->Open();

  if ( $conn->Execute($sql) )
    $customer = $conn->GetCustomer($number,true);

  $conn->Close();
  $conn->CheckError();

  SaguAssert(!empty($customer),"No valid customer object");

  return $customer;
}

function DeleteCustomer($number)
{
  // strip leading and trailing spaces from parameters
  $number   = trim($number);

  // check for valid input values
  CheckInputValue("number",$number!="","Cannot be empty");

  // build up the SQL delete statement
  $sql = "delete from customer where id = '$number'";

  $conn = new Meta;
  $conn->Open();
  $customer = $conn->GetCustomer($number,true);
  $conn->Execute($sql);
  $conn->Close();
  $conn->CheckError();

  SaguAssert(!empty($customer),"No valid customer object");

  return $customer;
}
</script>
