<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getImageThumbnailbyID_output = array();
$fsfcms_getImageThumbnailbyID_none   = "FSFPGITBI-None-Found";

if (isset($_GET['image_id']))
  {
  $fsfcms_getImageThumbnailbyID_image_ID = $_GET['image_id'];
  
  $fsfcms_thumbs_URL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsURL' LIMIT 1";
  $fsfcms_thumbs_URL_result = mysql_query($fsfcms_thumbs_URL_query);
  $fsfcms_thumbs_URL_row = mysql_fetch_row($fsfcms_thumbs_URL_result);
  $fsfcms_thumbs_URL = $fsfcms_thumbs_URL_row[0];
  //filename
  $fsfcms_image_filename_query = "SELECT filename FROM " . $fsfcms_images_table . " WHERE id = " . $fsfcms_getImageThumbnailbyID_image_ID . " AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'";
  $fsfcms_image_filename_result = mysql_query($fsfcms_image_filename_query);
  $fsfcms_image_filename_row = mysql_fetch_row($fsfcms_image_filename_result);
  $fsfcms_image_filename = $fsfcms_image_filename_row[0];
  $fsfcms_getImageThumbnailbyID_output['thumbnailURL'] = $fsfcms_thumbs_URL . "thumb_" . $fsfcms_image_filename;
  $fsfcms_thumbnail_size = getimagesize($fsfcms_getImageThumbnailbyID_output['thumbnailURL']);
  $fsfcms_getImageThumbnailbyID_output['thumbnailWidth'] = $fsfcms_thumbnail_size[0];
  $fsfcms_getImageThumbnailbyID_output['thumbnailHeight'] = $fsfcms_thumbnail_size[1];
  } else  {
  $fsfcms_getImageThumbnailbyID_output['0'] = $fsfcms_getImageThumbnailbyID_none;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getImageThumbnailbyID_output);
  
?>
