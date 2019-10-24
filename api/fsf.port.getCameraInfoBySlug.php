<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCameraInfoBySlug_output = array();

// Initialize Get Variables
$fsfcms_camera_slug       = $_GET['cameraSlug'];

// Set Up the DB Queries

$fsfcms_getCameraInfoBySlug_query      = "SELECT manufacturer, model, description FROM " . $fsfcms_cameras_table . " WHERE slug = '" . $fsfcms_camera_slug . "' LIMIT 1";
//echo "<p>" . $fsfcms_getMedoaNameBySlug_query ."</p>";
$fsfcms_getCameraInfoBySlug_result     = mysql_query($fsfcms_getCameraInfoBySlug_query);

if ($fsfcms_getCameraInfoBySlug_result)
  {
  if (mysql_num_rows($fsfcms_getCameraInfoBySlug_result) > 0)
    {     
    $fsfcms_getCameraInfoBySlug_row       = mysql_fetch_row($fsfcms_getCameraInfoBySlug_result);
    $fsfcms_getCameraInfoBySlug_camera_mfg  = $fsfcms_getCameraInfoBySlug_row[0];
    $fsfcms_getCameraInfoBySlug_camera_name = $fsfcms_getCameraInfoBySlug_row[1];
    $fsfcms_getCameraInfoBySlug_output['cameraManufacturer']  = $fsfcms_getCameraInfoBySlug_camera_mfg;
    $fsfcms_getCameraInfoBySlug_output['cameraName']          = $fsfcms_getCameraInfoBySlug_camera_name; 
    $fsfcms_getCameraInfoBySlug_output['cameraFullName']      = $fsfcms_getCameraInfoBySlug_camera_mfg . " " . $fsfcms_getCameraInfoBySlug_camera_name;    
    $fsfcms_getCameraInfoBySlug_output['cameraDescription']   = $fsfcms_getCameraInfoBySlug_row[2];
    } else  {
    $fsfcms_getCameraInfoBySlug_output[] = null;
    }
  } 

header('Content-Type: application/json');
echo json_encode($fsfcms_getCameraInfoBySlug_output);
?>
