<?php
$fsfcms_is_logged_in == false;

require "../admin/cfg.php";
require "../admin/startDBi.php";
require "../admin/ac.php";

//if ($fsfcms_is_logged_in == true)
//  {
  // Initialize Script Variables
  $fsfcms_getState_output   = array();
  $fsfcms_db_error          = "Request could not be completed because of a database error.";
  $fsfcms_state_slug        = $_GET['stateSlug'];

  if($fsfcms_cfg_stmt = $fsfcms_db_link->prepare("SELECT name, ansi, gpo, keyword_slug FROM " . FSFCMS_STATES_TABLE . " INNER JOIN " . FSFCMS_KEYWORDS_TABLE . " ON " . FSFCMS_STATES_TABLE . ".name = " . FSFCMS_KEYWORDS_TABLE . ".keyword WHERE " . FSFCMS_KEYWORDS_TABLE . ".keyword_slug = ? LIMIT 1"))
    { 
    if($fsfcms_cfg_stmt->bind_param("s",$fsfcms_state_slug))
      {
      if($fsfcms_cfg_stmt->execute())
        {
        $fsfcms_cfg_stmt->bind_result($state_name,$ansi,$gpo,$keyword_slug);
        if($fsfcms_cfg_stmt->fetch())
          {
          $fsfcms_getState_output['stateName']      = $state_name;
          $fsfcms_getState_output['stateAbbrANSI']  = $ansi;
          $fsfcms_getState_output['stateAbbrGPO']   = $gpo;
          $fsfcms_getState_output['stateSlug']      = $keyword_slug;
          $fsfcms_getState_output['status']  = 200;
          } else  {
          $fsfcms_getState_output['errorMessage']    = "Request could not be completed because no results were found in the database.";
          $fsfcms_getState_output['status']          = 404;
          }
        } else  {
        $fsfcms_getState_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
        $fsfcms_getState_output['status']            = 500;
        }
      } else  {
      $fsfcms_getState_output['errorMessage']        = $fsfcms_db_error . " Bind failed.";
      $fsfcms_getState_output['status']              = 500;
      }   
    } else  {
    $fsfcms_getState_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
    $fsfcms_getState_output['status']                = 500;
    }

  $fsfcms_cfg_stmt->close();
//  } else  {
//  header("HTTP/1.0 403 Forbidden"); // Remember to update this to 401 Unauthorized, which is actually correct.
//  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
 // exit;
//  }
  
header('Content-Type: application/json');
echo json_encode($fsfcms_getState_output);
?>