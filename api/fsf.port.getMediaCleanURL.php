<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getMediaCleanURL_output = array();
$fsfcms_getMediaCleanURL_none   = "FSFPGMediaCURL-None-Found";

// Get the list of media
$fsfcms_getMediaCleanURL_query  =  "SELECT DISTINCT " . $fsfcms_media_table . ".id, manufacturer, name, speed, type, slug FROM " . 
                                        $fsfcms_media_table . ", " . $fsfcms_images_table .
                                        " WHERE " . 
                                        $fsfcms_media_table . ".id = " . $fsfcms_images_table . ".media_id AND " . $fsfcms_images_table . ".post <= '" .
                                        $fsfcms_current_time_mysql_format . "' ORDER BY manufacturer ASC, name ASC, speed ASC";

$fsfcms_getMediaCleanURL_result = mysql_query($fsfcms_getMediaCleanURL_query);

if($fsfcms_getMediaCleanURL_result)
  {
  $fsfcms_total_media = mysql_num_rows($fsfcms_getMediaCleanURL_result);
  if($fsfcms_total_media > 0)
    { 
    while($fsfcms_getMediaCleanURL_row = mysql_fetch_assoc($fsfcms_getMediaCleanURL_result))
      {
      $fsfcms_getMediaCleanURL_output[] = array(
                                                    mediaId           =>  $fsfcms_getMediaCleanURL_row['id'],
                                                    mediaManufacturer =>  $fsfcms_getMediaCleanURL_row['manufacturer'],
                                                    mediaName         =>  $fsfcms_getMediaCleanURL_row['name'],
                                                    mediaSpeed        =>  $fsfcms_getMediaCleanURL_row['speed'],
                                                    mediaType         =>  $fsfcms_getMediaCleanURL_row['type'],
                                                    mediaSlug         =>  $fsfcms_getMediaCleanURL_row['slug']
                                                    );            
      }
    } else  {
      $fsfcms_getMediaCleanURL_output['0'] = $fsfcms_getMediaCleanURL_none;
    }  
  } else  {
  $fsfcms_getMediaCleanURL_output['0'] = $fsfcms_getMediaCleanURL_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getMediaCleanURL_output);
 
?>
