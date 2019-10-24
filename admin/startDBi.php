<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

$fsfcms_general_error   = "HTTP/1.0 500 Internal Server Error";
$fsfcms_specific_error  = "Could not connect to the database";

@$fsfcms_db_link = new mysqli(FSFCMS_DB_HOST, FSFCMS_DB_USERNAME, FSFCMS_DB_PASSWORD, FSFCMS_DB_NAME);  //  SEC Errors are suppressed to
                                                                                                        //  avoid exposing details of the
                                                                                                        //  DB setup. Errors are handled
                                                                                                        //  below and if the user is logged
                                                                                                        //  in, a more specific error is
                                                                                                        //  returned.

if($fsfcms_db_link->connect_error)
  {
  //TODO Check to see if the user is logged in and issue a mysql_error - $fsfcms_specific_error = $fsfcms_db_link->connect_errno() . $fsfcms_db_link->connect_error();
  die("<h1>" . $fsfcms_general_error . "</h1><h2>" . $fsfcms_specific_error . "</h2>");
  }

//  Get global configuration options from the database
if($fsfcms_cfg_stmt = $fsfcms_db_link->prepare("SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = ? LIMIT 1"))        //  Can be used to retrieve any setting, simply do a different binding
  {
  $fsfcms_cfg_setting = "siteAPIURL";
  if($fsfcms_cfg_stmt->bind_param("s",$fsfcms_cfg_setting))
    {
    if($fsfcms_cfg_stmt->execute())
      {
      $fsfcms_cfg_stmt->bind_result($value);
      $fsfcms_cfg_stmt->fetch();
      define('FSFCMS_API_URL',$value);
      } //  TODO Execute failed
    }   //  TODO Bind failed
  }     //  TODO Prepare failed
$fsfcms_cfg_stmt->close();
?>