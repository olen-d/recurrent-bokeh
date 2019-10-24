<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTotalImagesByMedia_output = array();
$fsfcms_getTotalImagesByMedia_none   = "FSFMTotal-Images-By-Media-None-Found";

if (isset($_GET['mediaSlug']))
  {
  $fsfcms_getTotalImagesByMedia_media_slug = $_GET['mediaSlug']; 
  }

// Get the total number of images
$fsfcms_getTotalImagesByMedia_query  = "SELECT COUNT(" . $fsfcms_images_table . ".id) AS total_images_by_media FROM " . $fsfcms_images_table .  
                                        " INNER JOIN " . $fsfcms_media_table . " ON " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id" .
                                        " WHERE " . $fsfcms_media_table . ".slug = '" . $fsfcms_getTotalImagesByMedia_media_slug . "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'";

$fsfcms_getTotalImagesByMedia_result = mysql_query($fsfcms_getTotalImagesByMedia_query);
   
if($fsfcms_getTotalImagesByMedia_result)
  {
  $fsfcms_getTotalImagesByMedia_row      = mysql_fetch_row($fsfcms_getTotalImagesByMedia_result);
  $fsfcms_getTotalImagesByMedia_output['totalImagesByMedia'] = number_format($fsfcms_getTotalImagesByMedia_row[0]);  
  } else  {
  $fsfcms_getTotalImagesByMedia_output['0'] = $fsfcms_getTotalImagesByMedia_none;
  }  

header('Content-Type: application/json');
echo json_encode($fsfcms_getTotalImagesByMedia_output);
?>
