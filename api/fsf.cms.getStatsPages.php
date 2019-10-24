<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables

$fsfcms_getStatsPages_output          = array();
$fsfcms_getStatsPages_none            = "No pages with statistics enabled were found. ";

//  Prepare the statement and access the database

$fsfcms_getStatsPages_query = "SELECT page_name, page_slug FROM " . FSFCMS_PAGES_TABLE . " WHERE statistics = 'yes' ORDER BY page_name ASC";

if($fsfcms_getStatsPages_statement  = $fsfcms_db_link->prepare($fsfcms_getStatsPages_query))
  {
    if($fsfcms_getStatsPages_statement->execute())
      {
      if($fsfcms_getStatsPages_statement->bind_result($page_name,$page_slug))
        { 
        while ($fsfcms_getStatsPages_statement->fetch())
          {
          $fsfcms_getStatsPages_output[$page_name] = $page_slug; 
          }
        $fsfcms_getStatsPages_statement->free_result();
        $fsfcms_getStatsPages_output['status']          = 200;
        } else  {
        $fsfcms_getStatsPages_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getStatsPages_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getStatsPages_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getStatsPages_output['status']            = 500;      
      }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getStatsPages_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getStatsPages_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getStatsPages_output);
?>