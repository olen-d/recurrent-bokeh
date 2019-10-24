<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getMediaInfo_output = array();

// Autodetect whether an ID or slug has been provided
if(isset($_GET['mediaId']))
  {
  $fsfcms_media_id         = $_GET['mediaId'];
  $fsfcms_getMediaInfo_where_clause  = "id = " . $fsfcms_media_id; 
  } elseif(isset($_GET['mediaSlug']))  {
  $fsfcms_media_slug       = $_GET['mediaSlug'];
  $fsfcms_getMediaInfo_where_clause  = "slug = '" . $fsfcms_media_slug . "'";
  }
  
// Set Up the DB Queries

$fsfcms_getMediaInfo_query = "SELECT id, manufacturer, name, speed, type, slug, media_added FROM " . $fsfcms_media_table . 
                                " WHERE " . $fsfcms_getMediaInfo_where_clause . " LIMIT 1";

$fsfcms_getMediaInfo_result     = mysql_query($fsfcms_getMediaInfo_query);

if($fsfcms_getMediaInfo_result)
  {
  if(mysql_num_rows($fsfcms_getMediaInfo_result) > 0)
    {     
    $fsfcms_getMediaInfo_row  = mysql_fetch_row($fsfcms_getMediaInfo_result);
    $fsfcms_getMediaInfo_output['mediaId']            = $fsfcms_getMediaInfo_row[0];
    $fsfcms_getMediaInfo_output['mediaManufacturer']  = $fsfcms_getMediaInfo_row[1];
    $fsfcms_getMediaInfo_output['mediaName']          = $fsfcms_getMediaInfo_row[2];
    $fsfcms_getMediaInfo_output['mediaSpeed']         = $fsfcms_getMediaInfo_row[3];  
    $fsfcms_getMediaInfo_output['mediaType']          = $fsfcms_getMediaInfo_row[4];    
    $fsfcms_getMediaInfo_output['mediaSlug']          = $fsfcms_getMediaInfo_row[5];
    $fsfcms_getMediaInfo_output['mediaAdded']         = $fsfcms_getMediaInfo_row[6];
    } else  {
    $fsfcms_getMediaInfo_output = null;
    } 
  } else  {
  $fsfcms_getMediaInfo_output = null;
  }
header('Content-Type: application/json');
echo json_encode($fsfcms_getMediaInfo_output);
?>
