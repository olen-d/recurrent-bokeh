<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getAuthorImageThumbnails_output = array();

if (isset($_GET['authorId']))
  {
  $fsfcms_getAuthorImageThumbnails_author_ID = $_GET['authorId']; 
  }

// Get the URL for the Thumbnails & Width and Height From the Configuration Table 
$fsfcms_getAuthorImageThumbnails_thumbs_URL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsURL' LIMIT 1";
$fsfcms_getAuthorImageThumbnails_thumbs_URL_result = mysql_query($fsfcms_getAuthorImageThumbnails_thumbs_URL_query);
$fsfcms_getAuthorImageThumbnails_thumbs_URL_row = mysql_fetch_row($fsfcms_getAuthorImageThumbnails_thumbs_URL_result);
$fsfcms_getAuthorImageThumbnails_thumbs_URL = $fsfcms_getAuthorImageThumbnails_thumbs_URL_row[0];

$fsfcms_getAuthorImageThumbnails_thumbs_width_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsWidth' LIMIT 1";
$fsfcms_getAuthorImageThumbnails_thumbs_width_result = mysql_query($fsfcms_getAuthorImageThumbnails_thumbs_width_query);
$fsfcms_getAuthorImageThumbnails_thumbs_width_row = mysql_fetch_row($fsfcms_getAuthorImageThumbnails_thumbs_width_result);
$fsfcms_getAuthorImageThumbnails_thumbs_width = $fsfcms_getAuthorImageThumbnails_thumbs_width_row[0];

$fsfcms_getAuthorImageThumbnails_thumbs_height_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsHeight' LIMIT 1";
$fsfcms_getAuthorImageThumbnails_thumbs_height_result = mysql_query($fsfcms_getAuthorImageThumbnails_thumbs_height_query);
$fsfcms_getAuthorImageThumbnails_thumbs_height_row = mysql_fetch_row($fsfcms_getAuthorImageThumbnails_thumbs_height_result);
$fsfcms_getAuthorImageThumbnails_thumbs_height = $fsfcms_getAuthorImageThumbnails_thumbs_height_row[0];

if (isset($_GET['items']))
  {
  $fsfcms_getAuthorImageThumbnails_items = $_GET['items']; 
  if (isset($_GET['page']))
    {
    $fsfcms_getAuthorImageThumbnails_page = $_GET['page']; 
    } else  {
    $fsfcms_getAuthorImageThumbnails_page = 1;
    }
  $fsfcms_getAuthorImageThumbnails_offset = ($fsfcms_getAuthorImageThumbnails_page - 1) * $fsfcms_getAuthorImageThumbnails_items;
  $fsfcms_getAuthorImageThumbnails_limit  = " LIMIT " . $fsfcms_getAuthorImageThumbnails_items . " OFFSET " . $fsfcms_getAuthorImageThumbnails_offset;
  } else  {
  $fsfcms_getAuthorImageThumbnails_limit  = "";
  }

//  Get the list of authors
$fsfcms_getAuthorImageThumbnails_authors_query  = "SELECT image_parent_id, " . $fsfcms_users_table. ".id as user_id, username, name_first, name_middle, name_last FROM " .
                                            $fsfcms_authors_table . " INNER JOIN " . $fsfcms_users_table . " ON " . $fsfcms_authors_table . ".user_id = " . 
                                            $fsfcms_users_table . ".id INNER JOIN " . $fsfcms_images_table . " ON " . $fsfcms_authors_table . ".image_parent_id = " . 
                                            $fsfcms_images_table . ".id ORDER BY post DESC, name_last ASC, name_first ASC, name_middle ASC";

$fsfcms_getAuthorImageThumbnails_authors_result = mysql_query($fsfcms_getAuthorImageThumbnails_authors_query);
if($fsfcms_getAuthorImageThumbnails_authors_result)
  {
  if(mysql_num_rows($fsfcms_getAuthorImageThumbnails_authors_result) > 0)
    {
    while($fsfcms_getAuthorImageThumbnails_authors_row = mysql_fetch_assoc($fsfcms_getAuthorImageThumbnails_authors_result))
      {
      $fsfcms_current_image_parent_id = $fsfcms_getAuthorImageThumbnails_authors_row['image_parent_id'];
      $fsfcms_authors_user_id = $fsfcms_getAuthorImageThumbnails_authors_row['user_id'];
      $fsfcms_authors_output[$fsfcms_current_image_parent_id][$fsfcms_authors_user_id] = array  (
                                                                                                authorId => $fsfcms_authors_user_id,
                                                                                                authorUsername => $fsfcms_getAuthorImageThumbnails_authors_row['username'],
                                                                                                authorFirstName => $fsfcms_getAuthorImageThumbnails_authors_row['name_first'],
                                                                                                authorMiddleName => $fsfcms_getAuthorImageThumbnails_authors_row['name_middle'],
                                                                                                authorLastName => $fsfcms_getAuthorImageThumbnails_authors_row['name_last'],
                                                                                                );
      }
    }
  }

// Get the list of images by the author
$fsfcms_getAuthorImageThumbnails_query  = "SELECT " . $fsfcms_images_table . ".id, filename, title, caption, UNIX_TIMESTAMP(" . $fsfcms_images_table . ".post) AS postedUnixTimestamp, YEAR(" . $fsfcms_images_table . ".post) AS imageYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS imageMonth, title_slug FROM " . $fsfcms_users_table . ", " . $fsfcms_authors_table . ", " . $fsfcms_images_table .  
                                          " WHERE " . $fsfcms_users_table . ".id = " . $fsfcms_authors_table . ".user_id AND " . $fsfcms_authors_table . ".image_parent_id = " . $fsfcms_images_table . ".id AND " . $fsfcms_users_table . ".id = " . $fsfcms_getAuthorImageThumbnails_author_ID . " AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" .  
                                          " ORDER BY " . $fsfcms_images_table . ".post DESC" .
                                          $fsfcms_getAuthorImageThumbnails_limit;
//echo $fsfcms_getAuthorImageThumbnails_query; 
$fsfcms_getAuthorImageThumbnails_result = mysql_query($fsfcms_getAuthorImageThumbnails_query);
   
if($fsfcms_getAuthorImageThumbnails_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getAuthorImageThumbnails_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getAuthorImageThumbnails_row = mysql_fetch_assoc($fsfcms_getAuthorImageThumbnails_result))
      {
      $fsfcms_getAuthorImageThumbnails_image_id = $fsfcms_getAuthorImageThumbnails_row['id'];
      $fsfcms_getAuthorImageThumbnails_output[] = array(
                                              id => $fsfcms_getAuthorImageThumbnails_image_id,
                                              title => $fsfcms_getAuthorImageThumbnails_row['title'],
                                              caption => trim($fsfcms_getAuthorImageThumbnails_row['caption']),
                                              authors => $fsfcms_authors_output[$fsfcms_getAuthorImageThumbnails_image_id],
                                              postedDateUnixTimestamp => $fsfcms_getAuthorImageThumbnails_row['postedUnixTimestamp'],
                                              imageLink => $fsfcms_getAuthorImageThumbnails_row['imageYear'] . "/" . $fsfcms_getAuthorImageThumbnails_row['imageMonth'] . "/" . $fsfcms_getAuthorImageThumbnails_row['title_slug'],
                                              thumbnailURL => $fsfcms_getAuthorImageThumbnails_thumbs_URL . "thumb_" . $fsfcms_getAuthorImageThumbnails_row['filename'],
                                              thumbnailWidth => $fsfcms_getAuthorImageThumbnails_thumbs_width,
                                              thumbnailHeight => $fsfcms_getAuthorImageThumbnails_thumbs_height
                                              );  
      }
      $fsfcms_getAuthorImageThumbnails_output[]['status'] = 200;
    } else  {
    $fsfcms_getAuthorImageThumbnails_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getAuthorImageThumbnails_output['status']        = 404;
    }  
  } else  {
  $fsfcms_getAuthorImageThumbnails_output['errorMessage']  = "Request could not be completed because of a database error.";
  $fsfcms_getAuthorImageThumbnails_output['status']        = 500;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getAuthorImageThumbnails_output);
?>
