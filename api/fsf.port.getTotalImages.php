<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTotalImages_output = array();
$fsfcms_getTotalImages_none   = "FSFPGTotal-Images-None-Found";

// Get the total number of images
$fsfcms_getTotalImages_query  = "SELECT COUNT(id) AS total_images FROM " . $fsfcms_images_table .  
                                " WHERE " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'";

$fsfcms_getTotalImages_result = mysql_query($fsfcms_getTotalImages_query);
   
if($fsfcms_getTotalImages_result)
  {
  $fsfcms_getTotalImages_row      = mysql_fetch_row($fsfcms_getTotalImages_result);
  $fsfcms_getTotalImages_output['totalImages'] = number_format($fsfcms_getTotalImages_row[0]);  
  } else  {
  $fsfcms_getTotalImages_output['0'] = $fsfcms_getTotalImages_none;
  }  

header('Content-Type: application/json');
echo json_encode($fsfcms_getTotalImages_output);
?>
