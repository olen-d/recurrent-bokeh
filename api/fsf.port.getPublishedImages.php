<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getPublishedImages_output = array();
$fsfcms_getPublishedImages_none   = "FSFPGPublished-Images-None-Found";

// Get the total number of images
$fsfcms_getPublishedImages_query  = "SELECT COUNT(id) AS total_images FROM " . $fsfcms_images_table .  
                                " WHERE " . $fsfcms_images_table . ".post < NOW()";

$fsfcms_getPublishedImages_result = mysql_query($fsfcms_getPublishedImages_query);
   
if($fsfcms_getPublishedImages_result)
  {
  $fsfcms_getPublishedImages_row      = mysql_fetch_row($fsfcms_getPublishedImages_result);
  $fsfcms_getPublishedImages_output['publishedImages'] = number_format($fsfcms_getPublishedImages_row[0]);  
  } else  {
  $fsfcms_getPublishedImages_output['0'] = $fsfcms_getPublishedImages_none;
  }  

header('Content-Type: application/json');
echo json_encode($fsfcms_getPublishedImages_output);
?>
