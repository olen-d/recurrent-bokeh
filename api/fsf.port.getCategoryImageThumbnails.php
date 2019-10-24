<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCategoryImageThumbnails_output = array();

if (isset($_GET['categorySlug']))
  {
  $fsfcms_getCategoryImageThumbnails_category_slug = $_GET['categorySlug'];
  $fsfcms_getCategoryImageThumbnails_category_slug = urldecode($fsfcms_getCategoryImageThumbnails_category_slug); 
  }

// Get the URL for the Thumbnails & Width and Height From the Configuration Table 
$fsfcms_getCategoryImageThumbnails_thumbs_URL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsURL' LIMIT 1";
$fsfcms_getCategoryImageThumbnails_thumbs_URL_result = mysql_query($fsfcms_getCategoryImageThumbnails_thumbs_URL_query);
$fsfcms_getCategoryImageThumbnails_thumbs_URL_row = mysql_fetch_row($fsfcms_getCategoryImageThumbnails_thumbs_URL_result);
$fsfcms_getCategoryImageThumbnails_thumbs_URL = $fsfcms_getCategoryImageThumbnails_thumbs_URL_row[0];

$fsfcms_getCategoryImageThumbnails_thumbs_width_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsWidth' LIMIT 1";
$fsfcms_getCategoryImageThumbnails_thumbs_width_result = mysql_query($fsfcms_getCategoryImageThumbnails_thumbs_width_query);
$fsfcms_getCategoryImageThumbnails_thumbs_width_row = mysql_fetch_row($fsfcms_getCategoryImageThumbnails_thumbs_width_result);
$fsfcms_getCategoryImageThumbnails_thumbs_width = $fsfcms_getCategoryImageThumbnails_thumbs_width_row[0];

$fsfcms_getCategoryImageThumbnails_thumbs_height_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsHeight' LIMIT 1";
$fsfcms_getCategoryImageThumbnails_thumbs_height_result = mysql_query($fsfcms_getCategoryImageThumbnails_thumbs_height_query);
$fsfcms_getCategoryImageThumbnails_thumbs_height_row = mysql_fetch_row($fsfcms_getCategoryImageThumbnails_thumbs_height_result);
$fsfcms_getCategoryImageThumbnails_thumbs_height = $fsfcms_getCategoryImageThumbnails_thumbs_height_row[0];

if (isset($_GET['items']))   
  {
  $fsfcms_getCategoryImageThumbnails_items = $_GET['items']; 
  if (isset($_GET['page']))
    {
    $fsfcms_getCategoryImageThumbnails_page = $_GET['page']; 
    } else  {
    $fsfcms_getCategoryImageThumbnails_page = 1;
    }
  $fsfcms_getCategoryImageThumbnails_offset = ($fsfcms_getCategoryImageThumbnails_page - 1) * $fsfcms_getCategoryImageThumbnails_items;
  $fsfcms_getCategoryImageThumbnails_limit  = " LIMIT " . $fsfcms_getCategoryImageThumbnails_items . " OFFSET " . $fsfcms_getCategoryImageThumbnails_offset;
  } else  {
  $fsfcms_getCategoryImageThumbnails_limit  = "";
  }

//  Get the list of authors
$fsfcms_getCategoryImageThumbnails_authors_query  = "SELECT image_parent_id, " . $fsfcms_users_table. ".id as user_id, username, name_first, name_middle, name_last FROM " .
                                            $fsfcms_authors_table . " INNER JOIN " . $fsfcms_users_table . " ON " . $fsfcms_authors_table . ".user_id = " . 
                                            $fsfcms_users_table . ".id INNER JOIN " . $fsfcms_images_table . " ON " . $fsfcms_authors_table . ".image_parent_id = " . 
                                            $fsfcms_images_table . ".id ORDER BY post DESC, name_last ASC, name_first ASC, name_middle ASC";

$fsfcms_getCategoryImageThumbnails_authors_result = mysql_query($fsfcms_getCategoryImageThumbnails_authors_query);
if($fsfcms_getCategoryImageThumbnails_authors_result)
  {
  if(mysql_num_rows($fsfcms_getCategoryImageThumbnails_authors_result) > 0)
    {
    while($fsfcms_getCategoryImageThumbnails_authors_row = mysql_fetch_assoc($fsfcms_getCategoryImageThumbnails_authors_result))
      {
      $fsfcms_current_image_parent_id = $fsfcms_getCategoryImageThumbnails_authors_row['image_parent_id'];
      $fsfcms_authors_user_id = $fsfcms_getCategoryImageThumbnails_authors_row['user_id'];
      $fsfcms_authors_output[$fsfcms_current_image_parent_id][$fsfcms_authors_user_id] = array  (
                                                                                                authorId => $fsfcms_authors_user_id,
                                                                                                authorUsername => $fsfcms_getCategoryImageThumbnails_authors_row['username'],
                                                                                                authorFirstName => $fsfcms_getCategoryImageThumbnails_authors_row['name_first'],
                                                                                                authorMiddleName => $fsfcms_getCategoryImageThumbnails_authors_row['name_middle'],
                                                                                                authorLastName => $fsfcms_getCategoryImageThumbnails_authors_row['name_last'],
                                                                                                );
      }
    }
  }

//  Get the list of categories
$fsfcms_getCategoryImageThumbnails_categories_query  = "SELECT parent_id, " . $fsfcms_category_names_table. ".id AS category_id, category_name, category_slug FROM " .
                                            $fsfcms_categories_table . " INNER JOIN " . $fsfcms_category_names_table . " ON " . $fsfcms_categories_table . ".category_id = " . 
                                            $fsfcms_category_names_table . ".id INNER JOIN " . $fsfcms_images_table . " ON " . $fsfcms_categories_table . ".parent_id = " . 
                                            $fsfcms_images_table . ".id ORDER BY post DESC, category_name ASC";

$fsfcms_getCategoryImageThumbnails_categories_result = mysql_query($fsfcms_getCategoryImageThumbnails_categories_query);
if($fsfcms_getCategoryImageThumbnails_categories_result)
  {
  if(mysql_num_rows($fsfcms_getCategoryImageThumbnails_categories_result) > 0)
    {
    while($fsfcms_getCategoryImageThumbnails_categories_row = mysql_fetch_assoc($fsfcms_getCategoryImageThumbnails_categories_result))
      {                                                                                                                
      $fsfcms_current_image_parent_id = $fsfcms_getCategoryImageThumbnails_categories_row['parent_id'];
      $fsfcms_categories_category_id = $fsfcms_getCategoryImageThumbnails_categories_row['category_id'];
      $fsfcms_categories_output[$fsfcms_current_image_parent_id][$fsfcms_categories_category_id] = array  (
                                                                                                categoryId => $fsfcms_categories_category_id,
                                                                                                categoryName =>  $fsfcms_getCategoryImageThumbnails_categories_row['category_name'],
                                                                                                categorySlug =>  $fsfcms_getCategoryImageThumbnails_categories_row['category_slug']
                                                                                                );
      }
    }
  }

// Get the list of images by category
$fsfcms_getCategoryImageThumbnails_query  = "SELECT " . $fsfcms_images_table . ".id, filename, title, caption, UNIX_TIMESTAMP(" . $fsfcms_images_table . ".post) AS postedUnixTimestamp, YEAR(" . $fsfcms_images_table . ".post) AS imageYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS imageMonth, title_slug FROM " . $fsfcms_images_table . ", " . $fsfcms_categories_table . ", " . $fsfcms_category_names_table . 
                                          " WHERE " . $fsfcms_images_table . ".id = " . $fsfcms_categories_table .".parent_id AND " . $fsfcms_categories_table . ".category_id = " . $fsfcms_category_names_table . ".id AND " . 
                                          $fsfcms_category_names_table . ".category_slug = '" . $fsfcms_getCategoryImageThumbnails_category_slug . "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" . 
                                          " ORDER BY " . $fsfcms_images_table . ".post DESC" . 
                                          $fsfcms_getCategoryImageThumbnails_limit;
 
$fsfcms_getCategoryImageThumbnails_result = mysql_query($fsfcms_getCategoryImageThumbnails_query);
   
if($fsfcms_getCategoryImageThumbnails_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getCategoryImageThumbnails_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getCategoryImageThumbnails_row = mysql_fetch_assoc($fsfcms_getCategoryImageThumbnails_result))
      {
      $fsfcms_getCategoryImageThumbnails_image_id = $fsfcms_getCategoryImageThumbnails_row['id'];
      $fsfcms_getCategoryImageThumbnails_output[] = array(
                                              id => $fsfcms_getCategoryImageThumbnails_image_id,
                                              title => $fsfcms_getCategoryImageThumbnails_row['title'],
                                              caption => trim($fsfcms_getCategoryImageThumbnails_row['caption']),
                                              authors => $fsfcms_authors_output[$fsfcms_getCategoryImageThumbnails_image_id],
                                              categories => $fsfcms_categories_output[$fsfcms_getCategoryImageThumbnails_image_id],
                                              postedDateUnixTimestamp => $fsfcms_getCategoryImageThumbnails_row['postedUnixTimestamp'],
                                              imageLink => $fsfcms_getCategoryImageThumbnails_row['imageYear'] . "/" . $fsfcms_getCategoryImageThumbnails_row['imageMonth'] . "/" . $fsfcms_getCategoryImageThumbnails_row['title_slug'],
                                              thumbnailURL => $fsfcms_getCategoryImageThumbnails_thumbs_URL . "thumb_" . $fsfcms_getCategoryImageThumbnails_row['filename'],
                                              thumbnailWidth => $fsfcms_getCategoryImageThumbnails_thumbs_width,
                                              thumbnailHeight => $fsfcms_getCategoryImageThumbnails_thumbs_height
                                              );  
      }
    $fsfcms_getCategoryImageThumbnails_output[]['status'] = 200;
    } else  {
    $fsfcms_getImageThumbnails_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getImageThumbnails_output['status']        = 404;
    }  
  } else  {
  $fsfcms_getImageThumbnails_output['errorMessage']  = "Request could not be completed because of a database error.";
  $fsfcms_getImageThumbnails_output['status']        = 500;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCategoryImageThumbnails_output);
?>
