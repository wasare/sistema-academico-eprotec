<script language="PHP">

// load database abstraction layer
require("meta.php3");

// ------------------------------------------------------------------
// Purpose: Retrieves the license object for the specified number
//          If no license can be found, the FatalExit() function
//          will be called with an appropriate error message
// ------------------------------------------------------------------
function GetLicense($number)
{
  // strip leading and trailing blanks
  $number = trim($number);

  // check for valid parameter content
  CheckInputValue("Number",$number != "","Cannot be empty!");

  $meta = new Meta;
  $meta->Open();
  $license = $meta->GetLicense($number,true);
  $meta->Close();
  $meta->CheckError();

  SaguAssert(!empty($license),"No valid license object");

  return $license;
}

// ------------------------------------------------------------------
// Purpose: Inserts a new license represented by the passed 
//          parameters. The parameters are checked for consistency
//          and if any inconsistency is detected, the FatalExit() 
//          function is called with an appropriate error message.
// ------------------------------------------------------------------
function InsertLicense($number,$customer,$software,$seats)
{
  // trim input parameters
  $number   = trim($number);
  $customer = trim($customer);
  $software = trim($software);
  $seats    = trim($seats);

  // check parameter contents
  CheckInputValue("Number",$number!="","Cannot be empty");
  CheckInputValue("Customer",$customer!="","Cannot be empty");
  CheckInputValue("Software",$software!="","Cannot be empty");
  CheckInputValue("Seats",$seats > 0 && $seats < 256,"Value must be > 0 and < 256");

  // get connection to database
  $meta = new Meta;
  $meta->Open();
  $meta->CheckCustomerNumber($customer);
  $meta->CheckSoftwareNumber($software);

  $sql = "insert into license (id,ref_customer,ref_software,num_seats)" .
         "  values ('$number','$customer','$software',$seats)";

  $success = false;

  // begins a transaction block
  $meta->Begin();

  if ( $meta->Execute($sql) )
  {
    $seqno = 0;
    $code  = '0123456789ABCDEF';

    $sql = "insert into keycode (id_license,id_seqno,value) values ( '$number', $seqno, '$code' )";

    if ( $meta->Execute($sql) )
    {
      $success = true;

      for ( $i=1; $i<=$seats; $i++ )
      {
        $seqno++;
        $code  = '0123456789ABCDEF';

        $sql = "insert into keycode (id_license,id_seqno,value) values ( '$number', $seqno, '$code' )";

        if ( !$meta->Execute($sql) )
        {
          $success = false;
          break;
        }
      }
    }
  }

  if ( $meta->Finish() )
    $license = $meta->GetLicense($number);

  $meta->Close();

  // this statement checks if an error occurred and if so, a FatalExit is
  // generated, which causes the current script to be abandoned
  $meta->CheckError();

  // and finally make sure, we will continue with a valid license object
  SaguAssert(!empty($license),"Empty license object!");

  return $license;
}

// ------------------------------------------------------------------
// Purpose: Inserts a new license represented by the passed 
//          parameters. The parameters are checked for consistency
//          and if any inconsistency is detected, the FatalExit() 
//          function is called with an appropriate error message.
//
//          Depending on the $seats parameter, the keycode associated
//          with the license object are untouched, reduced (for less
//          seats = downgrading) or increased (for more seats = 
//          upgrading)
// ------------------------------------------------------------------
function UpdateLicense($number,$customer,$software,$seats)
{
  // trim input parameters
  $number   = trim($number);
  $customer = trim($customer);
  $software = trim($software);
  $seats    = trim($seats);

  // check parameter contents
  CheckInputValue("Number",$number!="","Cannot be empty");
  CheckInputValue("Customer",$customer!="","Cannot be empty");
  CheckInputValue("Software",$software!="","Cannot be empty");
  CheckInputValue("Seats",$seats > 0 && $seats < 256,"Value must be > 0 and < 256");

  // get connection to database
  $meta = new Meta;
  $meta->Open();

  // check for existing license
  $license = $meta->GetLicense($number,true);

  // check for existing customer number
  $meta->CheckCustomerNumber($customer);

  // check for existing software number
  $meta->CheckSoftwareNumber($software);

  // build up update statement
  $sql = "update license set" .
         "  ref_customer = '$customer'," .
         "  ref_software = '$software'," .
         "  num_seats = $seats" .
         "  where id = '$number'";

  $success = false;

  // begins a transaction block
  $meta->Begin();

  if ( $meta->Execute($sql) )
  {
    // depending on the number of seats, we leave the keycodes
    // as is, up- or downgrade them

    // equal number of seats? then we´re done
    if ( $seats == $license->seats )
      $success = true;

    // downgrading license needs a new product keycode and to
    // delete the exceeding user keycodes
    else if ( $seats < $license->seats )
    {
      $code  = '0123456789ABCDEF';

      $sql = "update keycode set value = '$code' where id_license = '$number' and id_seqno = 0";

      if ( $meta->Execute($sql) )
      {
        $sql = "delete from keycode where id_license = '$number' and id_seqno > $seats";

        $success = $meta->Execute($sql);
      }
    }

    // upgrading license needs a new product keycode and to
    // insert the additional user keycodes
    else if ( $seats > $license->seats )
    {
      $code  = '0123456789ABCDEF';

      $sql = "update keycode set value = '$code' where id_license = '$number' and id_seqno = 0";

      if ( $meta->Execute($sql) )
      {
        $success = true;

        for ( $seqno=$license->seats+1; $seqno<=$seats; $seqno++ )
        {
          $code  = '0123456789ABCDEF';

          $sql = "insert into keycode (id_license,id_seqno,value) values ( '$number', $seqno, '$code' )";

          if ( !$meta->Execute($sql) )
          {
            $success = false;
            break;
          }
        }
      }
    }
  }

  // commits all actions of the current transaction block
  $success = $meta->Finish();

  if ( $success )
    $license = $meta->GetLicense($number);

  $meta->Close();

  // this statement checks if an error occurred and if so, a FatalExit is
  // generated, which causes the current script to be abandoned
  $meta->CheckError();

  // and finally make sure, we will continue with a valid license object
  SaguAssert(!empty($license),"Empty license object!");

  return $license;
}

// ------------------------------------------------------------------
// Purpose: Deletes a license entry from the database. In addition,
//          all associated keycodes will be deleted as well.
// ------------------------------------------------------------------
function DeleteLicense($number)
{
  // strip leading and trailing spaces from parameters
  $number   = trim($number);

  // check for valid input values
  CheckInputValue("number",$number!="","Cannot be empty");

  // get database connection
  $meta = new Meta;
  $meta->Open();

  // check for existing license
  $license = $meta->GetLicense($number,true);

  // execute related SLQ commands within one transaction block
  $meta->Begin();
  $meta->Execute("delete from keycode where id_license = '$number'");
  $meta->Execute("delete from license where id = '$number'");
  $meta->Finish();
  $meta->Close();
  $meta->CheckError();

  SaguAssert(!empty($license),"No valid license object");

  return $license;
}
</script>
