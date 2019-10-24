<?php

require "../admin/ac.php";

// Initialize Script Variables
$output                     = array();

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDBi.php";

  if(isset($_POST))
    {
    $format                       = $_POST['optionNewDateTimeFormat'];
    $description                  = $_POST['optionNewDateTimeFormatDescription'];
    $sort_priority                = $_POST['optionNewDateTimeFormatSortPriority'];

    if($statement = $fsfcms_db_link->prepare("INSERT INTO " . FSFCMS_CONFIG_DATETIME_TABLE . "(id,priority,format,description) VALUES ('',?,?,?)"))
      {
      if($statement->bind_param("iss",$sort_priority,$format,$description))
        {
        if(!$statement->execute())
          {
          $output['errorHeader']  = "HTTP/1.0 500 Internal Server Error";
          $output['errorMessage'] = "Execute failed.";
          $output['errorDetail']  = $fsfcms_db_link->error;
          $output['status']       = 500; 
          } 
        } else  {                 //  Bind failed
        $output['errorHeader']    = "HTTP/1.0 500 Internal Server Error";
        $output['errorMessage']   = "Bind failed.";
        $output['errorDetail']    = $fsfcms_db_link->error;
        $output['status']         = 500;        
        }
      } else  {                   //  Prepare failed
      $output['errorHeader']    = "HTTP/1.0 500 Internal Server Error";
      $output['errorMessage']   = "Prepare failed.";
      $output['errorDetail']    = $fsfcms_db_link->error;
      $output['status']         = 500;       
      }
    $statement->close();
    $output['message']  ="Great success! The new date and time format: " . $description . " was successfully added. ";
    $output['status']   = 200;
    } else  {
    header("HTTP/1.0 500 Internal Server Error");
    echo "HTTP/1.0 500 Internal Server Error";
    exit;
    }
  $fsfcms_db_link->close();
  return $output;
  } else  {
  header("HTTP/1.0 403 Forbidden"); // Remember to update this to 401 Unauthorized, which is actually correct.
  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
  }
?>