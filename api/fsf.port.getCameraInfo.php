<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCameraInfo_output = array();

// Autodetect whether an ID or slug has been provided
if(isset($_GET['cameraId']))
  {
  $fsfcms_camera_id         = $_GET['cameraId'];
  $fsfcms_getCameraInfo_where_clause  = "id = " . $fsfcms_camera_id; 
  } elseif(isset($_GET['cameraSlug']))  {
  $fsfcms_camera_slug       = $_GET['cameraSlug'];
  $fsfcms_getCameraInfo_where_clause  = "slug = '" . $fsfcms_camera_slug . "'";
  }
  
// Set Up the DB Queries

$fsfcms_getCameraInfo_query = "SELECT id, manufacturer, model, slug, description, camera_added FROM " . $fsfcms_cameras_table . 
                                " WHERE " . $fsfcms_getCameraInfo_where_clause . " LIMIT 1";

$fsfcms_getCameraInfo_result     = mysql_query($fsfcms_getCameraInfo_query);

if($fsfcms_getCameraInfo_result)
  {
  if(mysql_num_rows($fsfcms_getCameraInfo_result) > 0)
    {     
    $fsfcms_getCameraInfo_row       = mysql_fetch_row($fsfcms_getCameraInfo_result);
    $fsfcms_getCameraInfo_output['cameraId']            = $fsfcms_getCameraInfo_row[0];
    $fsfcms_getCameraInfo_manufacturer                  = $fsfcms_getCameraInfo_row[1];
    $fsfcms_getCameraInfo_name                          = $fsfcms_getCameraInfo_row[2];
    $fsfcms_getCameraInfo_output['cameraManufacturer']  = $fsfcms_getCameraInfo_manufacturer;
    $fsfcms_getCameraInfo_output['cameraName']          = $fsfcms_getCameraInfo_name; 
    $fsfcms_getCameraInfo_output['cameraFullName']      = $fsfcms_getCameraInfo_manufacturer . " " . $fsfcms_getCameraInfo_name;    
    $fsfcms_getCameraInfo_output['cameraSlug']          = $fsfcms_getCameraInfo_row[3];
    $fsfcms_getCameraInfo_output['cameraDescription']   = $fsfcms_getCameraInfo_row[4];
    $fsfcms_getCameraInfo_output['cameraAdded']         = $fsfcms_getCameraInfo_row[5];
    } else  {
    $fsfcms_getCameraInfo_output = null;
    } 
  } else  {
  $fsfcms_getCameraInfo_output = null;
  }
header('Content-Type: application/json');
echo json_encode($fsfcms_getCameraInfo_output);
?>
