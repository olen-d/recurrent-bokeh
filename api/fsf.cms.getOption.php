<?php
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_getOption_output  = array();
$fsfcms_db_error          = "Request could not be completed because of a database error.";
$fsfcms_setting           = $_GET['option'];

if($fsfcms_cfg_stmt = $fsfcms_db_link->prepare("SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = ? AND type = 'cms' LIMIT 1"))
  { 
  if($fsfcms_cfg_stmt->bind_param("s",$fsfcms_setting))
    {
    if($fsfcms_cfg_stmt->execute())
      {
      $fsfcms_cfg_stmt->bind_result($value);
      if($fsfcms_cfg_stmt->fetch())
        {
        $fsfcms_getOption_output['option']  = $value;
        $fsfcms_getOption_output['status']  = 200;
        } else  {
        $fsfcms_getOption_output['errorMessage']    = "Request could not be completed because no results were found in the database.";
        $fsfcms_getOption_output['status']          = 404;
        }
      } else  {
      $fsfcms_getOption_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getOption_output['status']            = 500;
      }
    } else  {
    $fsfcms_getOption_output['errorMessage']        = $fsfcms_db_error . " Bind failed.";
    $fsfcms_getOption_output['status']              = 500;
    }   
  } else  {
  $fsfcms_getOption_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getOption_output['status']                = 500;
  }

$fsfcms_cfg_stmt->close();

header('Content-Type: application/json');
echo json_encode($fsfcms_getOption_output);
?>