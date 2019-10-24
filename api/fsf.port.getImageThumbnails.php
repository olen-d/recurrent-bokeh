<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getImageThumbnails_output = array();

$fsfcms_getImageThumbnails_where_clause = "";

if (isset($_GET['items']))
  {
  $fsfcms_getImageThumbnails_items = $_GET['items']; 
  if (isset($_GET['page']))
    {
    $fsfcms_getImageThumbnails_page = $_GET['page']; 
    } else  {
    $fsfcms_getImageThumbnails_page = 1;
    }
  $fsfcms_getImageThumbnails_offset = ($fsfcms_getImageThumbnails_page - 1) * $fsfcms_getImageThumbnails_items;
  $fsfcms_getImageThumbnails_limit  = " LIMIT " . $fsfcms_getImageThumbnails_items . " OFFSET " . $fsfcms_getImageThumbnails_offset;
  } else  {
  $fsfcms_getImageThumbnails_limit  = "";
  }

if (isset($_GET['yearMonth']))
  {
  if (isset($_GET['timeZoneOffset']))
    {
    $fsfcms_timezone_offset = $_GET['timeZoneOffset'];
    } else  {
    $fsfcms_timezone_offset = "+00";
    }
  $fsfcms_getImageThumbnails_year_month   = $_GET['yearMonth'];
  $fsfcms_getImageThumbnails_where_clause = " AND EXTRACT(YEAR_MONTH FROM CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . ".post,'+00:00','" . $fsfcms_timezone_offset . ":00')) = " . $fsfcms_getImageThumbnails_year_month;  
  $fsfcms_getImageThumbnails_limit        = "";
  }

// Get the URL for the Thumbnails & Width and Height From the Configuration Table 
$fsfcms_getImageThumbnails_thumbs_URL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsURL' LIMIT 1";
$fsfcms_getImageThumbnails_thumbs_URL_result = mysql_query($fsfcms_getImageThumbnails_thumbs_URL_query);
$fsfcms_getImageThumbnails_thumbs_URL_row = mysql_fetch_row($fsfcms_getImageThumbnails_thumbs_URL_result);
$fsfcms_getImageThumbnails_thumbs_URL = $fsfcms_getImageThumbnails_thumbs_URL_row[0];

$fsfcms_getImageThumbnails_thumbs_width_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsWidth' LIMIT 1";
$fsfcms_getImageThumbnails_thumbs_width_result = mysql_query($fsfcms_getImageThumbnails_thumbs_width_query);
$fsfcms_getImageThumbnails_thumbs_width_row = mysql_fetch_row($fsfcms_getImageThumbnails_thumbs_width_result);
$fsfcms_getImageThumbnails_thumbs_width = $fsfcms_getImageThumbnails_thumbs_width_row[0];

$fsfcms_getImageThumbnails_thumbs_height_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsHeight' LIMIT 1";
$fsfcms_getImageThumbnails_thumbs_height_result = mysql_query($fsfcms_getImageThumbnails_thumbs_height_query);
$fsfcms_getImageThumbnails_thumbs_height_row = mysql_fetch_row($fsfcms_getImageThumbnails_thumbs_height_result);
$fsfcms_getImageThumbnails_thumbs_height = $fsfcms_getImageThumbnails_thumbs_height_row[0];

//  Get the list of authors
$fsfcms_getImageThumbnails_authors_query  = "SELECT image_parent_id, " . $fsfcms_users_table. ".id as user_id, username, name_first, name_middle, name_last FROM " .
                                            $fsfcms_authors_table . " INNER JOIN " . $fsfcms_users_table . " ON " . $fsfcms_authors_table . ".user_id = " . 
                                            $fsfcms_users_table . ".id INNER JOIN " . $fsfcms_images_table . " ON " . $fsfcms_authors_table . ".image_parent_id = " . 
                                            $fsfcms_images_table . ".id ORDER BY post DESC, name_last ASC, name_first ASC, name_middle ASC";

$fsfcms_getImageThumbnails_authors_result = mysql_query($fsfcms_getImageThumbnails_authors_query);
if($fsfcms_getImageThumbnails_authors_result)
  {
  if(mysql_num_rows($fsfcms_getImageThumbnails_authors_result) > 0)
    {
    while($fsfcms_getImageThumbnails_authors_row = mysql_fetch_assoc($fsfcms_getImageThumbnails_authors_result))
      {
      $fsfcms_current_image_parent_id = $fsfcms_getImageThumbnails_authors_row['image_parent_id'];
      $fsfcms_authors_user_id = $fsfcms_getImageThumbnails_authors_row['user_id'];
      $fsfcms_authors_output[$fsfcms_current_image_parent_id][$fsfcms_authors_user_id] = array  (
                                                                                                authorId => $fsfcms_authors_user_id,
                                                                                                authorUsername => $fsfcms_getImageThumbnails_authors_row['username'],
                                                                                                authorFirstName => $fsfcms_getImageThumbnails_authors_row['name_first'],
                                                                                                authorMiddleName => $fsfcms_getImageThumbnails_authors_row['name_middle'],
                                                                                                authorLastName => $fsfcms_getImageThumbnails_authors_row['name_last'],
                                                                                                );
      }
    }
  }
 
//  Get the list of images 
$fsfcms_getImageThumbnails_query  = "SELECT " . $fsfcms_images_table . ".id, filename, title, caption, UNIX_TIMESTAMP(" . $fsfcms_images_table . ".post) AS postedUnixTimestamp, YEAR(" . $fsfcms_images_table . ".post) AS imageYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS imageMonth, title_slug FROM " . $fsfcms_images_table .  
                                    " INNER JOIN ( SELECT " . $fsfcms_images_table . ".id FROM " . $fsfcms_images_table . 
                                    " WHERE " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'" . $fsfcms_getImageThumbnails_where_clause . " ORDER BY " . $fsfcms_images_table . ".post DESC" . 
                                    $fsfcms_getImageThumbnails_limit . 
                                    ") AS fsf_cms_images_small USING (id)";

$fsfcms_getImageThumbnails_result = mysql_query($fsfcms_getImageThumbnails_query);
   
if($fsfcms_getImageThumbnails_result)
  {
  $fsfcms_total_images = mysql_num_rows($fsfcms_getImageThumbnails_result);
  if($fsfcms_total_images > 0)
    { 
    while($fsfcms_getImageThumbnails_row = mysql_fetch_assoc($fsfcms_getImageThumbnails_result))
      {
      $fsfcms_getImageThumbnails_image_id = $fsfcms_getImageThumbnails_row['id'];
      $fsfcms_getImageThumbnails_output[] = array(
                                              id => $fsfcms_getImageThumbnails_image_id,
                                              title => $fsfcms_getImageThumbnails_row['title'],
                                              caption => trim($fsfcms_getImageThumbnails_row['caption']),
                                              authors => $fsfcms_authors_output[$fsfcms_getImageThumbnails_image_id],
                                              postedDateUnixTimestamp => $fsfcms_getImageThumbnails_row['postedUnixTimestamp'],
                                              imageLink => $fsfcms_getImageThumbnails_row['imageYear'] . "/" . $fsfcms_getImageThumbnails_row['imageMonth'] . "/" . $fsfcms_getImageThumbnails_row['title_slug'],
                                              thumbnailURL => $fsfcms_getImageThumbnails_thumbs_URL . "thumb_" . $fsfcms_getImageThumbnails_row['filename'],
                                              thumbnailWidth => $fsfcms_getImageThumbnails_thumbs_width,
                                              thumbnailHeight => $fsfcms_getImageThumbnails_thumbs_height
                                              );  
      }
      $fsfcms_getImageThumbnails_output[]['status'] = 200;
    } else  {
    $fsfcms_getImageThumbnails_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getImageThumbnails_output['status']        = 404;
    }  
  } else  {
  $fsfcms_getImageThumbnails_output['errorMessage']  = "Request could not be completed because of a database error.";
  $fsfcms_getImageThumbnails_output['status']        = 500;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getImageThumbnails_output);
?>