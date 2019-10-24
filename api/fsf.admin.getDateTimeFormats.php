<?php

require "../admin/ac.php";

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDB.php";

  // Initialize Script Variables
  $fsfcms_getDateTimeFormats_output           = array();

  // Set up the DB queries
  $fsfcms_getDateTimeFormats_query  = "SELECT id, format, description FROM " . FSFCMS_CONFIG_DATETIME_TABLE . " ORDER BY description ASC";
  $fsfcms_getDateTimeFormats_result = mysql_query($fsfcms_getDateTimeFormats_query);

  if($fsfcms_getDateTimeFormats_result)
    {
    if(mysql_num_rows($fsfcms_getDateTimeFormats_result) > 0)
      {
      while($fsfcms_getDateTimeFormats_row = mysql_fetch_assoc($fsfcms_getDateTimeFormats_result))
        {
        $fsfcms_getDateTimeFormats_output[] = array(
                                                    dateTimeFormatId          =>  $fsfcms_getDateTimeFormats_row['id'],
                                                    dateTimeFormat            =>  $fsfcms_getDateTimeFormats_row['format'],
                                                    dateTimeFormatDescription =>  $fsfcms_getDateTimeFormats_row['description'],
                                                    );
        }
      $fsfcms_getDateTimeFormats_output[]['status']     = 200;
      } else  {
      $fsfcms_getDateTimeFormats_output['errorMessage'] = "Request could not be completed because no results were found in the database.";
      $fsfcms_getDateTimeFormats_output['status']       = 404;
      }
    } else  {
    $fsfcms_getDateTimeFormats_output['errorMessage']   = "Request could not be completed because of a database error.";
    $fsfcms_getDateTimeFormats_output['status']         = 500;
    }

  header('Content-Type: application/json');
  echo json_encode($fsfcms_getDateTimeFormats_output);
  } else  {
  header("HTTP/1.0 403 Forbidden");
  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
  }
?>