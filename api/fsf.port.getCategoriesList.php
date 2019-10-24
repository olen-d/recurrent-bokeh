<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

//  Documentation
$doc_filename     = "fsf.port.getCategoriesList.php";
$doc_version      = "0.2";
$doc_updated      = "2017-02-24";
$doc_requirements = "none";
$doc_description  = "Provides a list of category names and attributes including the number of images. If the user is logged in, all categories are returned. Otherwise, only catgory names for images posted up to the current time are provided. ";
$doc_output       = "categoryId, categoryPriority, categoryName, categorySlug, categoryDescription, categoryAdded, categoryImagesCount";

// Initialize Script Variables
$categories_list_output           =   array();
$categories_list_where_clause     =   "WHERE " . FSFCMS_IMAGES_TABLE . ".post <= '" . $fsfcms_current_time_mysql_format . "' ";

if($fsfcms_is_logged_in)
  {
  $categories_list_where_clause   =   "";
  }

// Get the list of categories

$categories_list_query  = "SELECT " . FSFCMS_CATEGORY_NAMES_TABLE . ".id, 
                                                                      category_priority, 
                                                                      category_name, 
                                                                      category_slug, 
                                                                      category_description, 
                                                                      category_added, 
                                                                      COUNT(" . FSFCMS_CATEGORIES_TABLE . ".category_id) AS category_images_count FROM " . FSFCMS_CATEGORY_NAMES_TABLE . 
                                                                      " LEFT JOIN " . FSFCMS_CATEGORIES_TABLE . " ON " . 
                                                                      FSFCMS_CATEGORIES_TABLE . ".category_id = " . FSFCMS_CATEGORY_NAMES_TABLE . ".id 
                                                                      LEFT JOIN " . FSFCMS_IMAGES_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_TABLE .".parent_id " . $categories_list_where_clause . 
                                                                      "GROUP BY " . FSFCMS_CATEGORIES_TABLE . ".category_id ORDER BY category_name ASC";

if($categories_list_result = $fsfcms_db_link->query($categories_list_query))
  {                 
  while($categories_list_row = $categories_list_result->fetch_assoc())
    {                           
    $categories_list_output[] = array(
                                        categoryId          =>  $categories_list_row['id'],
                                        categoryPriority    =>  $categories_list_row['category_priority'],
                                        categoryName        =>  $categories_list_row['category_name'],
                                        categorySlug        =>  $categories_list_row['category_slug'],
                                        categoryDescription =>  $categories_list_row['category_description'],
                                        categoryAdded       =>  $categories_list_row['category_added'],
                                        categoryImagesCount =>  $categories_list_row['category_images_count']
                                      );    
    }

  $categories_list_result->close();
  $categories_list_output['status']          = 200;
  } else  {
  $categories_list_output['errorMessage']    = "Request could not be completed because no results were found in the database.";
  $categories_list_output['status']          = 404;
  }

header('Content-Type: application/json');
echo json_encode($categories_list_output); 
?>