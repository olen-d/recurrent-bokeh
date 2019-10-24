<?php

require "../admin/ac.php";

// Initialize Script Variables
$output           = array();

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDBi.php";

  // Set up the DB queries
  $query  = "SELECT value FROM " 
          . FSFCMS_CONFIG_TABLE . " WHERE setting = 'adminIncludePath' LIMIT 1";

  if($result = $fsfcms_db_link->query($query))
    {  
    $row                        = $result->fetch_assoc();
    $output['adminIncludePath'] = $row['value'];
    $output['status']           = 200;  
    } else  {
    $output['status']           = 500; 
    }
  header('Content-Type: application/json');
  echo json_encode($output);
  } else  {
  header("HTTP/1.0 403 Forbidden"); // Remember to update this to 401 Unauthorized, which is actually correct.
  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
  }
?>