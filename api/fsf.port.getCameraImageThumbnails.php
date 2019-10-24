<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCameraImageThumbnails_output = array();
$fsfcms_getCameraImageThumbnails_none   = "FSFPGCIT-None-Found";

if (isset($_GET['cameraSlug']))
  {
  $fsfcms_getCameraImageThumbnails_camera_slug = $_GET['cameraSlug']; 
  }

// Get the URL for the Thumbnails & Width and Height From the Configuration Table 
$fsfcms_getCameraImageThumbnails_thumbs_URL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsURL' LIMIT 1";
$fsfcms_getCameraImageThumbnails_thumbs_URL_result = mysql_query($fsfcms_getCameraImageThumbnails_thumbs_URL_query);
$fsfcms_getCameraImageThumbnails_thumbs_URL_row = mysql_fetch_row($fsfcms_getCameraImageThumbnails_thumbs_URL_result);
$fsfcms_getCameraImageThumbnails_thumbs_URL = $fsfcms_getCameraImageThumbnails_thumbs_URL_row[0];

$fsfcms_getCameraImageThumbnails_thumbs_width_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsWidth' LIMIT 1";
$fsfcms_getCameraImageThumbnails_thumbs_width_result = mysql_query($fsfcms_getCameraImageThumbnails_thumbs_width_query);
$fsfcms_getCameraImageThumbnails_thumbs_width_row = mysql_fetch_row($fsfcms_getCameraImageThumbnails_thumbs_width_result);
$fsfcms_getCameraImageThumbnails_thumbs_width = $fsfcms_getCameraImageThumbnails_thumbs_width_row[0];

$fsfcms_getCameraImageThumbnails_thumbs_height_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsHeight' LIMIT 1";
$fsfcms_getCameraImageThumbnails_thumbs_height_result = mysql_query($fsfcms_getCameraImageThumbnails_thumbs_height_query);
$fsfcms_getCameraImageThumbnails_thumbs_height_row = mysql_fetch_row($fsfcms_getCameraImageThumbnails_thumbs_height_result);
$fsfcms_getCameraImageThumbnails_thumbs_height = $fsfcms_getCameraImageThumbnails_thumbs_height_row[0];

if (isset($_GET['items']))   
  {
  $fsfcms_getCameraImageThumbnails_items = $_GET['items']; 
  if (isset($_GET['page']))
    {
    $fsfcms_getCameraImageThumbnails_page = $_GET['page']; 
    } else  {
    $fsfcms_getCameraImageThumbnails_page = 1;
    }
  $fsfcms_getCameraImageThumbnails_offset = ($fsfcms_getCameraImageThumbnails_page - 1) * $fsfcms_getCameraImageThumbnails_items;
  $fsfcms_getCameraImageThumbnails_limit  = " LIMIT " . $fsfcms_getCameraImageThumbnails_items . " OFFSET " . $fsfcms_getCameraImageThumbnails_offset;
  } else  {
  $fsfcms_getCameraImageThumbnails_limit  = "";
  }

//  Get the list of authors
$fsfcms_getCameraImageThumbnails_authors_query  = "SELECT image_parent_id, " . $fsfcms_users_table. ".id as user_id, username, name_first, name_middle, name_last FROM " .
                                            $fsfcms_authors_table . " INNER JOIN " . $fsfcms_users_table . " ON " . $fsfcms_authors_table . ".user_id = " . 
                                            $fsfcms_users_table . ".id INNER JOIN " . $fsfcms_images_table . " ON " . $fsfcms_authors_table . ".image_parent_id = " . 
                                            $fsfcms_images_table . ".id ORDER BY post DESC, name_last ASC, name_first ASC, name_middle ASC";

$fsfcms_getCameraImageThumbnails_authors_result = mysql_query($fsfcms_getCameraImageThumbnails_authors_query);
if($fsfcms_getCameraImageThumbnails_authors_result)
  {
  if(mysql_num_rows($fsfcms_getCameraImageThumbnails_authors_result) > 0)
    {
    while($fsfcms_getCameraImageThumbnails_authors_row = mysql_fetch_assoc($fsfcms_getCameraImageThumbnails_authors_result))
      {
      $fsfcms_current_image_parent_id = $fsfcms_getCameraImageThumbnails_authors_row['image_parent_id'];
      $fsfcms_authors_user_id = $fsfcms_getCameraImageThumbnails_authors_row['user_id'];
      $fsfcms_authors_output[$fsfcms_current_image_parent_id][$fsfcms_authors_user_id] = array  (
                                                                                                authorId => $fsfcms_authors_user_id,
                                                                                                authorUsername => $fsfcms_getCameraImageThumbnails_authors_row['username'],
                                                                                                authorFirstName => $fsfcms_getCameraImageThumbnails_authors_row['name_first'],
                                                                                                authorMiddleName => $fsfcms_getCameraImageThumbnails_authors_row['name_middle'],
                                                                                                authorLastName => $fsfcms_getCameraImageThumbnails_authors_row['name_last'],
                                                                                                );
      }
    }
  }

// Get the list of images by camera
$fsfcms_getCameraImageThumbnails_query  = "SELECT " . $fsfcms_images_table . ".id, filename, title, caption, UNIX_TIMESTAMP(" . $fsfcms_images_table . ".post) AS postedUnixTimestamp, YEAR(" . $fsfcms_images_table . ".post) AS imageYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS imageMonth, title_slug FROM " . $fsfcms_images_table . ", " . $fsfcms_cameras_table . 
                                          " WHERE " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_cameras_table . ".slug = '" . $fsfcms_getCameraImageThumbnails_camera_slug . "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" . 
                                          " ORDER BY " . $fsfcms_images_table . ".post DESC" . 
                                          $fsfcms_getCameraImageThumbnails_limit;
//echo "<p>" . $fsfcms_getCameraImageThumbnails_query . "</p>"; 
$fsfcms_getCameraImageThumbnails_result = mysql_query($fsfcms_getCameraImageThumbnails_query);
   
if($fsfcms_getCameraImageThumbnails_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getCameraImageThumbnails_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getCameraImageThumbnails_row = mysql_fetch_assoc($fsfcms_getCameraImageThumbnails_result))
      {
      $fsfcms_getCameraImageThumbnails_image_id = $fsfcms_getCameraImageThumbnails_row['id'];
      $fsfcms_getCameraImageThumbnails_output[] = array(
                                              id => $fsfcms_getCameraImageThumbnails_image_id,
                                              title => $fsfcms_getCameraImageThumbnails_row['title'],
                                              caption => trim($fsfcms_getCameraImageThumbnails_row['caption']),
                                              authors => $fsfcms_authors_output[$fsfcms_getCameraImageThumbnails_image_id],
                                              postedDateUnixTimestamp => $fsfcms_getCameraImageThumbnails_row['postedUnixTimestamp'],
                                              imageLink => $fsfcms_getCameraImageThumbnails_row['imageYear'] . "/" . $fsfcms_getCameraImageThumbnails_row['imageMonth'] . "/" . $fsfcms_getCameraImageThumbnails_row['title_slug'],
                                              thumbnailURL => $fsfcms_getCameraImageThumbnails_thumbs_URL . "thumb_" . $fsfcms_getCameraImageThumbnails_row['filename'],
                                              thumbnailWidth => $fsfcms_getCameraImageThumbnails_thumbs_width,
                                              thumbnailHeight => $fsfcms_getCameraImageThumbnails_thumbs_height
                                              );  
      }
    $fsfcms_getCameraImageThumbnails_output[]['status'] = 200;
    } else  {
    $fsfcms_getCameraImageThumbnails_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getCameraImageThumbnails_output['status']        = 404;
    }  
  } else  {
  $fsfcms_getCameraImageThumbnails_output['errorMessage']  = "Request could not be completed because of a database error.";
  $fsfcms_getCameraImageThumbnails_output['status']        = 500;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getCameraImageThumbnails_output);
?>
