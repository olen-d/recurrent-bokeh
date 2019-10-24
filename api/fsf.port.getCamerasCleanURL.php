<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCamerasCleanURL_output = array();
$fsfcms_getCamerasCleanURL_none   = "FSFPGC-None-Found";

// Get the list of cameras
$fsfcms_getCamerasCleanURL_query  = "SELECT DISTINCT " . $fsfcms_images_table . ".camera_id AS camera_id, COUNT(" . $fsfcms_images_table . ".camera_id) AS camera_count, manufacturer, model, slug, description FROM " . $fsfcms_images_table . 
                                    ", " . $fsfcms_cameras_table . " WHERE " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id" . 
                                    " GROUP BY " . $fsfcms_images_table . ".camera_id ORDER BY " . $fsfcms_cameras_table . ".manufacturer ASC, " . $fsfcms_cameras_table . ".model ASC";
$fsfcms_getCamerasCleanURL_result = mysql_query($fsfcms_getCamerasCleanURL_query);
   //echo $fsfcms_getCamerasCleanURL_query;
if($fsfcms_getCamerasCleanURL_result)
  {
  $fsfcms_total_cameras = mysql_num_rows($fsfcms_getCamerasCleanURL_result);
  if($fsfcms_total_cameras > 0)
    { 
    while($fsfcms_getCamerasCleanURL_row = mysql_fetch_assoc($fsfcms_getCamerasCleanURL_result))
      {
      $fsfcms_getCamerasCleanURL_output[] = array(
                                              cameraId => $fsfcms_getCamerasCleanURL_row['camera_id'],
                                              cameraManufacturer => $fsfcms_getCamerasCleanURL_row['manufacturer'],
                                              cameraName => $fsfcms_getCamerasCleanURL_row['model'],
                                              cameraFullName => $fsfcms_getCamerasCleanURL_row['manufacturer'] . " " . $fsfcms_getCamerasCleanURL_row['model'],
                                              cameraDescription => $fsfcms_getCamerasCleanURL_row['description'],
                                              cameraCount => $fsfcms_getCamerasCleanURL_row['camera_count'],
                                              cameraSlug => $fsfcms_getCamerasCleanURL_row['slug'],
                                              cameraCleanURL => "cameras/" . $fsfcms_getCamerasCleanURL_row['slug']
                                              );            
      }
    } else  {
      $fsfcms_getCamerasCleanURL_output['0'] = $fsfcms_getCamerasCleanURL_none;
    }  
  } else  {
  $fsfcms_getCamerasCleanURL_output['0'] = $fsfcms_getCamerasCleanURL_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCamerasCleanURL_output);
 
?>
