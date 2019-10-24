<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCategoryImageIDs_output = array();
$fsfcms_getCategoryImageIDs_none   = "FSFPGCATII-None-Found";

// REMEMBER TO UPGRADE THIS TO TAKE A CATEGORY SLUG AS WELL

if (isset($_GET['categoryId']))
  {
  $fsfcms_getCategoryImageIDs_category_id = urldecode($_GET['categoryId']); 
  }

// Get the list of images by the author
$fsfcms_getCategoryImagesIDs_query  = "SELECT " . $fsfcms_images_table . ".id FROM " . $fsfcms_images_table . ", " . $fsfcms_categories_table .  
                                    " WHERE " . $fsfcms_images_table . ".id = " . $fsfcms_categories_table . ".parent_id AND " . 
                                    $fsfcms_categories_table . ".category_id = " . $fsfcms_getCategoryImageIDs_category_id . " AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" .
                                    " ORDER BY " . $fsfcms_images_table . ".post DESC";
$fsfcms_getCategoryImagesIDs_result = mysql_query($fsfcms_getCategoryImagesIDs_query);
   
if($fsfcms_getCategoryImagesIDs_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getCategoryImagesIDs_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getCategoryImagesIDs_row = mysql_fetch_assoc($fsfcms_getCategoryImagesIDs_result))
      {
      $fsfcms_getCategoryImageIDs_output[] = $fsfcms_getCategoryImagesIDs_row['id'];  
      }
    } else  {
      $fsfcms_getCategoryImageIDs_output['0'] = $fsfcms_getCategoryImageIDs_none;
    }  
  } else  {
  $fsfcms_getCtegoryImageIDs_output['0'] = $fsfcms_getCategoryImageIDs_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCategoryImageIDs_output);
?>
