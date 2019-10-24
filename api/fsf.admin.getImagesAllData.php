<?php

//  Author:       Olen Daelhousen
//  Version:      0.5
//  Last Updated: 2016  02  15
//
//  TODO:
//
//  1. Update to use MYSQLI
//  2.  Update to use prepared statements

require "../admin/ac.php";

$fsfcms_get_images_all_data_output  = array();

$fsfcms_get_images_all_data_page    = $_GET['page'];
$fsfcms_get_images_all_data_items   = $_GET['imagesPerPage'];

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDB.php";
  
  $fsfcms_get_images_all_data_imageURL_query    = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portImageURL' LIMIT 1";
  $fsfcms_get_images_all_data_imageURL_result   = mysql_query($fsfcms_get_images_all_data_imageURL_query);
  $fsfcms_get_images_all_data_imageURL_row      = mysql_fetch_row($fsfcms_get_images_all_data_imageURL_result);
  $fsfcms_get_images_all_data_imageURL          = $fsfcms_get_images_all_data_imageURL_row[0];
  
  
  $fsfcms_get_images_all_data_ImagePath_query   = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portImagePath' LIMIT 1"; 
  $fsfcms_get_images_all_data_ImagePath_result  = mysql_query($fsfcms_get_images_all_data_ImagePath_query);
  $fsfcms_get_images_all_data_ImagePath_row     = mysql_fetch_row($fsfcms_get_images_all_data_ImagePath_result);
  $fsfcms_get_images_all_data_ImagePath         = $fsfcms_get_images_all_data_ImagePath_row[0];

  $fsfcms_get_images_all_data_offset = ($fsfcms_get_images_all_data_page - 1) * $fsfcms_get_images_all_data_items;
  $fsfcms_get_images_all_data_limit  = " LIMIT " . $fsfcms_get_images_all_data_items . " OFFSET " . $fsfcms_get_images_all_data_offset;

  // Set up filters
  if(isset($_GET['filter']) && isset($_GET['filterId']))
    {
    if($_GET['filter'] == "category")
      {
      $filter_id  = mysql_real_escape_string($_GET['filterId']); //  REM TODO SEC  Change this to mysqli when updating to mysqli driver
      $query = "SELECT " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id,
                                              " . FSFCMS_IMAGES_TABLE . ".id AS image_id,
                                              " . FSFCMS_IMAGES_TABLE . ".filename, 
                                              " . FSFCMS_IMAGES_TABLE . ".title,
                                              " . FSFCMS_IMAGES_TABLE . ".title_slug,
                                              " . FSFCMS_IMAGES_TABLE . ".caption,
                                              " . FSFCMS_IMAGES_TABLE . ".post, 
                                              " . FSFCMS_AUTHORS_TABLE . ".user_id, 
                                              " . FSFCMS_USERS_TABLE . ".name_first, 
                                              " . FSFCMS_USERS_TABLE . ".name_middle, 
                                              " . FSFCMS_USERS_TABLE . ".name_last, 
                                              " . FSFCMS_CAMERAS_TABLE . ".manufacturer AS camera_manufacturer, 
                                              " . FSFCMS_CAMERAS_TABLE . ".model AS camera_model, 
                                              " . FSFCMS_MEDIA_TABLE . ".manufacturer AS media_manufacturer, 
                                              " . FSFCMS_MEDIA_TABLE . ".name AS media_name, 
                                              " . FSFCMS_MEDIA_TABLE . ".speed AS media_speed, 
                                              " . FSFCMS_MEDIA_TABLE . ".type AS media_type,
                                              categories_t.categories,   
                                              GROUP_CONCAT(keyword ORDER BY keyword ASC) AS keywords 
                                              FROM " . FSFCMS_IMAGES_TABLE . " 
                                              LEFT JOIN " . FSFCMS_CATEGORIES_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_TABLE . ".parent_id
                                              LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id
                                              LEFT JOIN " . FSFCMS_KEYWORDS_TABLE . " ON " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " . FSFCMS_KEYWORDS_TABLE . ".id
                                              INNER JOIN " . FSFCMS_AUTHORS_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_AUTHORS_TABLE . " .image_parent_id 
                                              INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".user_id = " . FSFCMS_USERS_TABLE . ".id 
                                              INNER JOIN " . FSFCMS_CAMERAS_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".camera_id = " . FSFCMS_CAMERAS_TABLE . ".id 
                                              INNER JOIN " . FSFCMS_MEDIA_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".media_id = " . FSFCMS_MEDIA_TABLE . ".id 
                                              LEFT JOIN 
                                                (SELECT " . FSFCMS_CATEGORIES_TABLE . ".parent_id, 
                                                        " . FSFCMS_CATEGORY_NAMES_TABLE . ".category_name, 
                                                        GROUP_CONCAT(category_name ORDER BY category_name ASC) as categories 
                                                        FROM " . FSFCMS_CATEGORIES_TABLE . "
                                                        INNER JOIN " . FSFCMS_CATEGORY_NAMES_TABLE . " ON " . FSFCMS_CATEGORIES_TABLE . ".category_id  = " . FSFCMS_CATEGORY_NAMES_TABLE . ".id    
                                                        GROUP BY " . FSFCMS_CATEGORIES_TABLE . ".parent_id) AS categories_t
                                              ON " . FSFCMS_IMAGES_TABLE . ".id = categories_t.parent_id " .                                                       
                                              "WHERE " . FSFCMS_CATEGORIES_TABLE . ".category_id = " . $filter_id . "
                                              GROUP BY image_parent_id 
                                              ORDER BY " .FSFCMS_IMAGES_TABLE . ".post DESC" .  
                                              $fsfcms_get_images_all_data_limit;
//echo "<p>" . $query . "</p>";
      }
    } else  {
  $query = "SELECT " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id,
                                              " . $fsfcms_images_table . ".id AS image_id,
                                              " . $fsfcms_images_table . ".filename, 
                                              " . $fsfcms_images_table . ".title,
                                              " . $fsfcms_images_table . ".title_slug,
                                              " . $fsfcms_images_table . ".caption,
                                              " . $fsfcms_images_table . ".post, 
                                              " . $fsfcms_authors_table . ".user_id, 
                                              " . $fsfcms_users_table . ".name_first, 
                                              " . $fsfcms_users_table . ".name_middle, 
                                              " . $fsfcms_users_table . ".name_last, 
                                              " . $fsfcms_cameras_table . ".manufacturer AS camera_manufacturer, 
                                              " . $fsfcms_cameras_table . ".model AS camera_model, 
                                              " . $fsfcms_media_table . ".manufacturer AS media_manufacturer, 
                                              " . $fsfcms_media_table . ".name AS media_name, 
                                              " . $fsfcms_media_table . ".speed AS media_speed, 
                                              " . $fsfcms_media_table . ".type AS media_type,
                                              categories_t.categories,   
                                              GROUP_CONCAT(keyword ORDER BY keyword ASC) AS keywords 
                                              FROM " . $fsfcms_images_table . " 
                                              LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " . $fsfcms_images_table . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id
                                              LEFT JOIN " . $fsfcms_keywords_table . " ON " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " . $fsfcms_keywords_table . ".id
                                              INNER JOIN " . $fsfcms_authors_table . " ON " . $fsfcms_images_table . ".id = " . $fsfcms_authors_table . " .image_parent_id 
                                              INNER JOIN " . $fsfcms_users_table . " ON " . $fsfcms_authors_table . ".user_id = " . $fsfcms_users_table . ".id 
                                              INNER JOIN " . $fsfcms_cameras_table . " ON " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id 
                                              INNER JOIN " . $fsfcms_media_table . " ON " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id 
                                              LEFT JOIN 
                                                (SELECT " . $fsfcms_categories_table . ".parent_id, 
                                                        " . $fsfcms_category_names_table . ".category_name, 
                                                        GROUP_CONCAT(category_name ORDER BY category_name ASC) as categories 
                                                        FROM " . $fsfcms_categories_table . "
                                                        INNER JOIN " . $fsfcms_category_names_table . " ON " . $fsfcms_categories_table . ".category_id  = " . $fsfcms_category_names_table . ".id    
                                                        GROUP BY " . $fsfcms_categories_table . ".parent_id) AS categories_t
                                              ON " . $fsfcms_images_table . ".id = categories_t.parent_id " .                                                       
                                              "GROUP BY image_parent_id 
                                              ORDER BY " . $fsfcms_images_table . ".post DESC" .  
                                              $fsfcms_get_images_all_data_limit;    
    
    
    
    } 


//echo "<p>cheeseburger: " . $fsfcms_get_images_all_data_query     . "</p>";   exit;
  $fsfcms_get_images_all_data_result  = mysql_query($query);
  if($fsfcms_get_images_all_data_result)
    {
    if (mysql_num_rows($fsfcms_get_images_all_data_result) > 0)
      {
      while ($fsfcms_get_images_all_data_row = mysql_fetch_assoc($fsfcms_get_images_all_data_result))
        {
        $fsfcms_get_images_all_data_image_dimensions  = getimagesize($fsfcms_get_images_all_data_imageURL.$fsfcms_get_images_all_data_row['filename']);
        $fsfcms_get_images_all_data_image_filesize    = filesize($fsfcms_get_images_all_data_ImagePath.$fsfcms_get_images_all_data_row['filename']);

        $fsfcms_get_images_all_data_output[]  = array (
                                                      URL                 => $fsfcms_get_images_all_data_imageURL, 
                                                      id                  => $fsfcms_get_images_all_data_row['image_id'],
                                                      filename            => $fsfcms_get_images_all_data_row['filename'],
                                                      title               => $fsfcms_get_images_all_data_row['title'],
                                                      titleSlug           => $fsfcms_get_images_all_data_row['title_slug'],
                                                      caption             => $fsfcms_get_images_all_data_row['caption'],
                                                      postedDate          => $fsfcms_get_images_all_data_row['post'],
                                                      authorId            => $fsfcms_get_images_all_data_row['user_id'],
                                                      authorFirstName     => $fsfcms_get_images_all_data_row['name_first'],
                                                      authorMiddleName    => $fsfcms_get_images_all_data_row['name_middle'],
                                                      authorLastName      => $fsfcms_get_images_all_data_row['name_last'],
                                                      cameraManufacturer  => $fsfcms_get_images_all_data_row['camera_manufacturer'],
                                                      cameraName          => $fsfcms_get_images_all_data_row['camera_model'],
                                                      mediaManufacturer   => $fsfcms_get_images_all_data_row['media_manufacturer'],
                                                      mediaName           => $fsfcms_get_images_all_data_row['media_name'],
                                                      mediaSpeed          => $fsfcms_get_images_all_data_row['media_speed'],
                                                      mediaType           => $fsfcms_get_images_all_data_row['media_type'],
                                                      imageCategories     => $fsfcms_get_images_all_data_row['categories'],
                                                      imageKeywords       => $fsfcms_get_images_all_data_row['keywords'],
                                                      width               => $fsfcms_get_images_all_data_image_dimensions[0],
                                                      height              => $fsfcms_get_images_all_data_image_dimensions[1],
                                                      imageFileSize       => $fsfcms_get_images_all_data_image_filesize
                                                      );
        }
      } else  {
      // Empty set, print some sort of error.
      echo "<p>Empty set. </p>";
      } 
    } else  {
    // Epic database fail, print some sort of error.
    // echo "<p>Epic fail. </p><p>" . $fsfcms_get_images_all_data_query . "</p><p>" . mysql_error($fsfcms_get_images_all_data_result) . "</p>"; 
    }
  header('Content-Type: application/json');
  echo json_encode($fsfcms_get_images_all_data_output);
//  echo "<p><pre>";print_r($fsfcms_get_images_all_data_output);echo "</pre></p>";
  } else  {
  header("HTTP/1.0 403 Forbidden"); // Remember to update this to 401 Unauthorized, which is actually correct.
  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
  }
?>