<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getImageCategories_output = array();
$fsfcms_getImageCategories_none   = "FSFPGIC-None-Found";

// Initialize Get Variables
$fsfcms_getImage_id       = $_GET['image_id'];

$fsfcms_getImageCategories_query  = "SELECT " . $fsfcms_images_table . ".title, " . $fsfcms_categories_table . ".category_id, " . $fsfcms_category_names_table . ".category_name, " . $fsfcms_category_names_table . ".category_slug FROM " . 
                                    $fsfcms_images_table . ", " . $fsfcms_categories_table . ", " . $fsfcms_category_names_table . 
                                    " WHERE " . $fsfcms_images_table . ".id = " . $fsfcms_categories_table . ".parent_id AND " . $fsfcms_categories_table . ".category_id = " . $fsfcms_category_names_table . ".id AND " . 
                                    $fsfcms_images_table . ".id = " . $fsfcms_getImage_id . " ORDER BY " . $fsfcms_category_names_table . ".category_name ASC";
$fsfcms_getImageCategories_result = mysql_query($fsfcms_getImageCategories_query);

if($fsfcms_getImageCategories_result)
  {
  $fsfcms_total_categories = mysql_num_rows($fsfcms_getImageCategories_result);
  if($fsfcms_total_categories > 0)
    {
    while($fsfcms_getImageCategories_row = mysql_fetch_assoc($fsfcms_getImageCategories_result))
      {
      $fsfcms_getImageCategories_output[] = array (
                                                  categoryId    =>  $fsfcms_getImageCategories_row['category_id'],
                                                  categorySlug  =>  $fsfcms_getImageCategories_row['category_slug'],
                                                  categoryName  =>  $fsfcms_getImageCategories_row['category_name']
                                                  );    
      }
      $fsfcms_getImageCategories_output['status'] = 200;
    } else  {
    $fsfcms_getImageCategories_output['0'] = $fsfcms_getImageCategories_none;
    } 
  } else  {
  $fsfcms_getImageCategories_output['0'] = $fsfcms_getImageCategories_none;
  }

// Taken care of at the database level
// natcasesort($fsfcms_getImageKeywords_output);  // Sort the keywords in a non-case sensative natural order (i.e. like a human would)

header('Content-Type: application/json');
echo json_encode($fsfcms_getImageCategories_output);

?>