<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCameraImageIDs_output = array();
$fsfcms_getCameraImageIDs_none   = "FSFPGCII-None-Found";

if (isset($_GET['cameraSlug']))
  {
  $fsfcms_getCameraImageIDs_camera_slug = urldecode($_GET['cameraSlug']); 
  }

// Get the list of images by the author
$fsfcms_getCameraImagesIDs_query  = "SELECT " . $fsfcms_images_table . ".id FROM " . $fsfcms_images_table . ", " . $fsfcms_cameras_table .    
                                    " WHERE " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_cameras_table. ".slug = '" . $fsfcms_getCameraImageIDs_camera_slug . "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" .
                                    " ORDER BY " . $fsfcms_images_table . ".post DESC";
$fsfcms_getCameraImagesIDs_result = mysql_query($fsfcms_getCameraImagesIDs_query);
   
if($fsfcms_getCameraImagesIDs_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getCameraImagesIDs_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getCameraImagesIDs_row = mysql_fetch_assoc($fsfcms_getCameraImagesIDs_result))
      {
      $fsfcms_getCameraImageIDs_output[] = $fsfcms_getCameraImagesIDs_row['id'];  
      }
    } else  {
      $fsfcms_getCameraImageIDs_output['0'] = $fsfcms_getCameraImageIDs_none;
    }  
  } else  {
  $fsfcms_getCameraImageIDs_output['0'] = $fsfcms_getCameraImageIDs_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCameraImageIDs_output);
?>
