<script language="PHP">
// load database abstraction layer
require("meta.php3");

// returns a software object for the specified number
function GetSoftware($number)
{
  // strip leading and trailing blanks
  $number = trim($number);

  // check for valid parameter content
  CheckInputValue("Number",$number != "","Cannot be empty!");

  $conn = new Meta;
  $conn->Open();
  $software = $conn->GetSoftware($number,true);
  $conn->Close();
  $conn->CheckError();

  SaguAssert(!empty($software),"No valid software object");

  return $software;
}

// add new software record to the database and reread it after
// insertion has occurred to ensure that everything went well
//
// Hint: For convenience we may want to have a function like
//         InsertSoftware(...);
//       which may be placed into a MetaSoftware class, where
//       we also can define an appropriate update function
function InsertSoftware($number,$title,$info)
{
  // strip leading and trailing blanks
  $number = trim($number);
  $title  = trim($title);
  $info   = trim($info);

  // check for valid parameter content
  CheckInputValue("Number",$number != "","Cannot be empty!");
  CheckInputValue("Title",$title != "","Cannot be empty!");
  CheckInputValue("Description",$info != "","Cannot be empty!");

  // the SLQ statement for inserting a new software record
  $sql = "insert into software (id,title,info) values ('$number','$title','$info')";

  $conn = new Meta;                          // create meta database object
  $conn->Open();                             // establish the connection
  $conn->Execute($sql);                      // execute the query
  $software = $conn->GetSoftware($number);   // try to retrieve the inserted record
  $conn->Close();                            // close the connection
  $conn->CheckError();                       // track potential errors

  return $software;

  // this makes sure, we will continue only if a valid software object
  // has been retrieved
  SaguAssert(!empty($software),"No valid software object!");
};

// the function to update a software record, performs
// the update and will return the updated software
// object
function UpdateSoftware($number,$title,$info)
{
  // strip leading and trailing blanks from input parameters
  $number = trim($number);
  $title  = trim($title);
  $info   = trim($info);

  // check content of parameters, not that a call to
  // CheckInputValue() will not return if the condition
  // is not evaluated as true.
  CheckInputValue("Number",$number!="","Cannot be empty");
  CheckInputValue("Title",$title!="","Cannot be empty");
  CheckInputValue("Description",$info!="","Cannot be empty");

  $sql = "update software set title = '$title', info = '$info' where id = '$number'";

  $conn = new Meta;
  $conn->Open();
  if ( $conn->Execute($sql) )
    $software = $conn->GetSoftware($number,true);
  $conn->Close();
  $conn->CheckError();

  // finally make sure we return a valid software object
  SaguAssert(!empty($software),"No valid software object!");

  return $software;
}

// the function to delete a software record, performs
// the deletion and will return the old software
// object
function DeleteSoftware($number)
{
  // strip leading and trailing blanks from input parameters
  $number = trim($number);

  // check content of parameters, not that a call to
  // CheckInputValue() will not return if the condition
  // is not evaluated as true.
  CheckInputValue("Number",$number!="","Cannot be empty");

  $sql = "delete from software where id = '$number'";

  $conn = new Meta;
  $conn->Open();
  $software = $conn->GetSoftware($number,true);
  $conn->Execute($sql);
  $conn->Close();
  $conn->CheckError();

  // finally make sure we return a valid software object
  SaguAssert(!empty($software),"No valid software object!");

  return $software;
}
</script>
