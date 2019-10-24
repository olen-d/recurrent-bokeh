<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getImageKeywords_output = array();
$fsfcms_getImageKeywords_none   = "FSFPGIK-None-Found";

// Initialize Get Variables
$fsfcms_getImage_id       = $_GET['image_id'];

$fsfcms_getImageKeywords_query  = "SELECT keyword FROM " . $fsfcms_keywords_table . " WHERE image_parent_id = " . $fsfcms_getImage_id . " ORDER BY keyword ASC";
$fsfcms_getImageKeywords_result = mysql_query($fsfcms_getImageKeywords_query);

if($fsfcms_getImageKeywords_result)
  {
  $fsfcms_total_keywords = mysql_num_rows($fsfcms_getImageKeywords_result);
  if($fsfcms_total_keywords > 0)
    { 
    while($fsfcms_getImageKeywords_row = mysql_fetch_assoc($fsfcms_getImageKeywords_result))
      {
      $fsfcms_getImageKeywords_output[] = $fsfcms_getImageKeywords_row['keyword'];    
      }
    } else  {
    $fsfcms_getImageKeywords_output['0'] = $fsfcms_getImageKeywords_none;
    } 
  } else  {
  $fsfcms_getImageKeywords_output['0'] = $fsfcms_getImageKeywords_none;
  }

// Taken care of at the database level
// natcasesort($fsfcms_getImageKeywords_output);  // Sort the keywords in a non-case sensative natural order (i.e. like a human would)

header('Content-Type: application/json');
echo json_encode($fsfcms_getImageKeywords_output);

?>
