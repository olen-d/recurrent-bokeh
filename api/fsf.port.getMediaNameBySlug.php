<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getMediaNameBySlug_output = array();

// Initialize Get Variables
$fsfcms_media_slug       = $_GET['mediaSlug'];

// Set Up the DB Queries

$fsfcms_getMediaNameBySlug_query      = "SELECT manufacturer, name, speed FROM " . $fsfcms_media_table . " WHERE slug = '" . $fsfcms_media_slug . "' LIMIT 1";
//echo "<p>" . $fsfcms_getMedoaNameBySlug_query ."</p>";
$fsfcms_getMediaNameBySlug_result     = mysql_query($fsfcms_getMediaNameBySlug_query);

if ($fsfcms_getMediaNameBySlug_result)
  {
  if (mysql_num_rows($fsfcms_getMediaNameBySlug_result) > 0)
    {     
    $fsfcms_getMediaNameBySlug_row       = mysql_fetch_row($fsfcms_getMediaNameBySlug_result);
    $fsfcms_getMediaNameBySlug_output['mediaManufacturer']  = $fsfcms_getMediaNameBySlug_row[0];
    $fsfcms_getMediaNameBySlug_output['mediaName']          = $fsfcms_getMediaNameBySlug_row[1];   
    $fsfcms_getMediaNameBySlug_output['mediaSpeed']         = $fsfcms_getMediaNameBySlug_row[2];
    } else  {
    $fsfcms_getMediaNameBySlug_output[] = null;
    }
  } 

header('Content-Type: application/json');
echo json_encode($fsfcms_getMediaNameBySlug_output);
?>
