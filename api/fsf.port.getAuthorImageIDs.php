<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getAuthorImageIDs_output = array();
$fsfcms_getAuthorImageIDs_none   = "FSFPGAII-None-Found";

if (isset($_GET['authorID']))
  {
  $fsfcms_getAuthorImageIDs_author_ID = $_GET['authorID']; 
  }

// Get the list of images by the author
$fsfcms_getAuthorImagesIDs_query  = "SELECT " . $fsfcms_images_table . ".id FROM " . $fsfcms_users_table . ", " . $fsfcms_authors_table . ", " . $fsfcms_images_table .  
                                    " WHERE " . $fsfcms_users_table . ".id = " . $fsfcms_authors_table . ".user_id AND " . $fsfcms_authors_table . ".image_parent_id = " . $fsfcms_images_table . ".id AND " . $fsfcms_users_table . ".id = " . $fsfcms_getAuthorImageIDs_author_ID . " AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . 
                                    "' ORDER BY " . $fsfcms_images_table . ".post DESC";
$fsfcms_getAuthorImagesIDs_result = mysql_query($fsfcms_getAuthorImagesIDs_query);
   
if($fsfcms_getAuthorImagesIDs_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getAuthorImagesIDs_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getAuthorImagesIDs_row = mysql_fetch_assoc($fsfcms_getAuthorImagesIDs_result))
      {
      $fsfcms_getAuthorImageIDs_output[] = $fsfcms_getAuthorImagesIDs_row['id'];  
      }
    } else  {
      $fsfcms_getAuthorImageIDs_output['0'] = $fsfcms_getAuthorImageIDs_none;
    }  
  } else  {
  $fsfcms_getAuthorImageIDs_output['0'] = $fsfcms_getAuthorImageIDs_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getAuthorImageIDs_output);
?>
