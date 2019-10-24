<?php

require "../admin/ac.php";

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDB.php";

  // Initialize Script Variables
  $fsfcms_getOptions_output           = array();

  // Set up the DB queries
  $fsfcms_getOptions_query  = "SELECT setting, value FROM " . $fsfcms_config_table;
  $fsfcms_getOptions_result = mysql_query($fsfcms_getOptions_query);

  if($fsfcms_getOptions_result)
    {
    if(mysql_num_rows($fsfcms_getOptions_result) > 0)
      {
      while($fsfcms_getOptions_row = mysql_fetch_assoc($fsfcms_getOptions_result))
        {
        $fsfcms_getOptions_setting  = trim($fsfcms_getOptions_row['setting']);
        $fsfcms_getOptions_value    = trim($fsfcms_getOptions_row['value']);
        $fsfcms_getOptions_output["$fsfcms_getOptions_setting"] = $fsfcms_getOptions_value;
        }
      } else  {
      // fail
      }
    } else  {
    //fail
    }

  header('Content-Type: application/json');
  echo json_encode($fsfcms_getOptions_output);
  } else  {
  header("HTTP/1.0 403 Forbidden"); // Remember to update this to 401 Unauthorized, which is actually correct.
  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
  }
?>