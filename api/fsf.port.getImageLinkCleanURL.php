<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getImageLinkCleanURL_output = array();

// Initialize Get Variables
$fsfcms_current_image_id       = $_GET['image_id'];

// Set Up the DB Queries

$fsfcms_getImageLink_clean_URL_query      = "SELECT title, YEAR(" . $fsfcms_images_table . ".post) AS imageYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS imageMonth, title_slug FROM " . $fsfcms_images_table . " WHERE (" . $fsfcms_images_table . ".id = " . $fsfcms_current_image_id . ") LIMIT 1";
//echo $fsfcms_getImageLink_clean_URL_query ;
$fsfcms_getImageLink_clean_URL_result     = mysql_query($fsfcms_getImageLink_clean_URL_query);
if(mysql_num_rows($fsfcms_getImageLink_clean_URL_result) > 0)
  {
  $fsfcms_getImageLink_clean_URL_row        = mysql_fetch_row($fsfcms_getImageLink_clean_URL_result);
  $fsfcms_getImageLink_image_title          = $fsfcms_getImageLink_clean_URL_row[0];
  $fsfcms_getImageLink_clean_URL            = $fsfcms_getImageLink_clean_URL_row[1] . "/" . $fsfcms_getImageLink_clean_URL_row[2] . "/" . $fsfcms_getImageLink_clean_URL_row[3];
  } else  {
  $fsfcms_getImageLink_image_title = null;
  $fsfcms_getImageLink_clean_URL = null;
  }
  
$fsfcms_getImageLinkCleanURL_output['imageLink']     = $fsfcms_getImageLink_clean_URL;
$fsfcms_getImageLinkCleanURL_output['imageTitle']        = $fsfcms_getImageLink_image_title;  // TODO: Returning imageTitle is depracated
$fsfcms_getImageLinkCleanURL_output['status'] = 200;  // TODO: Fix this to report an actual error

header('Content-Type: application/json');
echo json_encode($fsfcms_getImageLinkCleanURL_output);
?>