<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTotalImagesByCamera_output = array();
$fsfcms_getTotalImagesByCamera_none   = "FSFPGTotal-Images-By-Camera-None-Found";

if (isset($_GET['cameraSlug']))
  {
  $fsfcms_getTotalImagesByCamera_camera_slug = $_GET['cameraSlug']; 
  }

// Get the total number of images
$fsfcms_getTotalImagesByCamera_query  = "SELECT COUNT(" . $fsfcms_images_table . ".id) AS total_images_by_camera FROM " . $fsfcms_images_table .  
                                        " INNER JOIN " . $fsfcms_cameras_table . " ON " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id" .
                                        " WHERE " . $fsfcms_cameras_table . ".slug = '" . $fsfcms_getTotalImagesByCamera_camera_slug . "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'";

$fsfcms_getTotalImagesByCamera_result = mysql_query($fsfcms_getTotalImagesByCamera_query);
   
if($fsfcms_getTotalImagesByCamera_result)
  {
  $fsfcms_getTotalImagesByCamera_row      = mysql_fetch_row($fsfcms_getTotalImagesByCamera_result);
  $fsfcms_getTotalImagesByCamera_output['totalImagesByCamera'] = number_format($fsfcms_getTotalImagesByCamera_row[0]);  
  } else  {
  $fsfcms_getTotalImagesByCamera_output['0'] = $fsfcms_getTotalImagesByCamera_none;
  }  

header('Content-Type: application/json');
echo json_encode($fsfcms_getTotalImagesByCamera_output);
?>
