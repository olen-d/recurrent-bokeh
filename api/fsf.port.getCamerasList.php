<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCamerasList_output = array();
$fsfcms_getCamerasList_none   = "FSFPGCAT-None-Found";

// Get the list of cameras
$fsfcms_getCamerasList_query  =  "SELECT id, manufacturer, model, slug, description, camera_added FROM " . 
                                        $fsfcms_cameras_table . " ORDER BY manufacturer ASC, model ASC";

$fsfcms_getCamerasList_result = mysql_query($fsfcms_getCamerasList_query);

if($fsfcms_getCamerasList_result)
  {
  $fsfcms_total_cameras = mysql_num_rows($fsfcms_getCamerasList_result);
  if($fsfcms_total_cameras > 0)
    { 
    while($fsfcms_getCamerasList_row = mysql_fetch_assoc($fsfcms_getCamerasList_result))
      {
      $fsfcms_getCamerasList_output[] = array(
                                                    cameraId            =>  $fsfcms_getCamerasList_row['id'],
                                                    cameraManufacturer  =>  $fsfcms_getCamerasList_row['manufacturer'],
                                                    cameraName          =>  $fsfcms_getCamerasList_row['model'],
                                                    cameraFullName      =>  $fsfcms_getCamerasList_row['manufacturer'] . " " . $fsfcms_getCamerasList_row['model'],
                                                    cameraDescription   =>  $fsfcms_getCamerasList_row['description'],
                                                    cameraSlug          =>  $fsfcms_getCamerasList_row['slug'],
                                                    cameraCleanURL      =>  "cameras/" . $fsfcms_getCamerasList_row['slug'],
                                                    cameraAdded         =>  $fsfcms_getCamerasList_row['camera_added']
                                                    );            
      }
    } else  {
      $fsfcms_getCamerasList_output['0'] = $fsfcms_getCamerasList_none;
    }  
  } else  {
  $fsfcms_getCamerasList_output['0'] = $fsfcms_getCamerasList_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCamerasList_output); 
?>
