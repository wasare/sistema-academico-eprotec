<? require("../../../lib/common.php"); ?> 
<?
  $PID  = getmypid();
  $file = "/tmp/sql.log.$PID";
 
  $rc = @system("../bin/currlog $file");
  
  SaguAssert(!$rc,"Could not obtain SQL log file!");

  include($file);

  unlink($file);
?>

