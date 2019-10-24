<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getMediaList_output = array();
$fsfcms_getMediaList_none   = "FSFPGCAT-None-Found";

// Get the list of cameras
$fsfcms_getMediaList_query  =  "SELECT id, manufacturer, name, speed, type, slug, media_added FROM " . 
                                        $fsfcms_media_table . " ORDER BY manufacturer ASC, name ASC, speed ASC";

$fsfcms_getMediaList_result = mysql_query($fsfcms_getMediaList_query);

if($fsfcms_getMediaList_result)
  {
  $fsfcms_total_media = mysql_num_rows($fsfcms_getMediaList_result);
  if($fsfcms_total_media > 0)
    { 
    while($fsfcms_getMediaList_row = mysql_fetch_assoc($fsfcms_getMediaList_result))
      {
      $fsfcms_getMediaList_output[] = array(
                                                    mediaId           =>  $fsfcms_getMediaList_row['id'],
                                                    mediaManufacturer =>  $fsfcms_getMediaList_row['manufacturer'],
                                                    mediaName         =>  $fsfcms_getMediaList_row['name'],
                                                    mediaSpeed        =>  $fsfcms_getMediaList_row['speed'],
                                                    mediaType         =>  $fsfcms_getMediaList_row['type'],
                                                    mediaSlug         =>  $fsfcms_getMediaList_row['slug'],
                                                    mediaAdded        =>  $fsfcms_getMediaList_row['media_added']
                                                    );            
      }
    } else  {
      $fsfcms_getMediaList_output['0'] = $fsfcms_getMediaList_none;
    }  
  } else  {
  $fsfcms_getMediaList_output['0'] = $fsfcms_getMediaList_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getMediaList_output); 
?>
