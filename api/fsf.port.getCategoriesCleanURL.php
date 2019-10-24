<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCategoriesCleanURL_output = array();
$fsfcms_getCategoriesCleanURL_none   = "FSFPGCAT-None-Found";

// Get the list of categories
$fsfcms_getCategoriesCleanURL_query  =  "SELECT DISTINCT " . $fsfcms_category_names_table . ".id, category_priority, category_name, category_slug, category_description FROM " . 
                                        $fsfcms_category_names_table . ", " . $fsfcms_categories_table . ", " . $fsfcms_images_table .
                                        " WHERE " . $fsfcms_category_names_table . ".id = " . $fsfcms_categories_table . ".category_id AND " . 
                                        $fsfcms_categories_table . ".parent_id = " . $fsfcms_images_table . ".id AND " . $fsfcms_images_table . ".post <= '" .
                                        $fsfcms_current_time_mysql_format . "' ORDER BY category_name ASC";

$fsfcms_getCategoriesCleanURL_result = mysql_query($fsfcms_getCategoriesCleanURL_query);

if($fsfcms_getCategoriesCleanURL_result)
  {
  $fsfcms_total_categories = mysql_num_rows($fsfcms_getCategoriesCleanURL_result);
  if($fsfcms_total_categories > 0)
    { 
    while($fsfcms_getCategoriesCleanURL_row = mysql_fetch_assoc($fsfcms_getCategoriesCleanURL_result))
      {
      $fsfcms_getCategoriesCleanURL_output[] = array(
                                                    categoryId          =>  $fsfcms_getCategoriesCleanURL_row['id'],
                                                    categoryPriority    =>  $fsfcms_getCategoriesCleanURL_row['category_priority'],
                                                    categoryName        =>  $fsfcms_getCategoriesCleanURL_row['category_name'],
                                                    categorySlug        =>  $fsfcms_getCategoriesCleanURL_row['category_slug'],
                                                    categoryDescription =>  $fsfcms_getCategoriesCleanURL_row['category_description']
                                                    );            
      }
    } else  {
      $fsfcms_getCategoriesCleanURL_output['0'] = $fsfcms_getCategoriesCleanURL_none;
    }  
  } else  {
  $fsfcms_getCategoriesCleanURL_output['0'] = $fsfcms_getCategoriesCleanURL_none;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCategoriesCleanURL_output);
 
?>
