<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getMediaImageIDs_output = array();
$fsfcms_getMediaImageIDs_none   = "FSFPGMediaImageIDs-None-Found";

// REMEMBER TO UPGRADE THIS TO TAKE A CATEGORY SLUG AS WELL

if (isset($_GET['mediaId']))
  {
  $fsfcms_getMediaImageIDs_media_id = urldecode($_GET['mediaId']); 
  }

// Get the list of images by media
$fsfcms_getMediaImagesIDs_query   = "SELECT " . $fsfcms_images_table . ".id FROM " . $fsfcms_images_table .   
                                    " WHERE " . $fsfcms_images_table . ".media_id = " . $fsfcms_getMediaImageIDs_media_id . " AND " . 
                                    $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" .
                                    " ORDER BY " . $fsfcms_images_table . ".post DESC";
//echo      "<p>" . $fsfcms_getMediaImagesIDs_query . "</p>";
$fsfcms_getMediaImagesIDs_result = mysql_query($fsfcms_getMediaImagesIDs_query);
   
if($fsfcms_getMediaImagesIDs_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getMediaImagesIDs_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getMediaImagesIDs_row = mysql_fetch_assoc($fsfcms_getMediaImagesIDs_result))
      {
      $fsfcms_getMediaImageIDs_output[] = $fsfcms_getMediaImagesIDs_row['id'];  
      }
    } else  {
      $fsfcms_getMediaImageIDs_output['0'] = $fsfcms_getMediaImageIDs_none;
    }  
  } else  {
  $fsfcms_getMediaImageIDs_output['0'] = $fsfcms_getMediaImageIDs_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getMediaImageIDs_output);
?>
