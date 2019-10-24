<?php

// Record the start time of file execution for trouble shooting
$fsfcms_imageURL_ts_time_start = microtime(true);

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";
require "../includes/fsf_cms_functions.php";
require "fsf_api_functions.php";

// Initialize Script Variables
$fsfcms_getImage_output      = array();

//  Initialize Get Variables
$fsfcms_is_image_id   = false;
$fsfcms_is_image_url  = false;

$fsfcms_is_image_id   = isset($_GET['imageId']);
$fsfcms_is_image_url  = isset($_GET['yearMonth']) && isset($_GET['slug']);

if($fsfcms_is_image_id)
  {
  $fsfcms_image_id                  = $_GET['imageId'];

  //  Set up the DB queries
    $fsfcms_getImage_query = "SELECT " . $fsfcms_images_table . ".id, filename, title, title_slug, caption, post, " . $fsfcms_cameras_table . ".manufacturer AS camera_manufacturer, " . $fsfcms_cameras_table . ".model AS camera_model, " . $fsfcms_cameras_table . ".slug AS camera_slug, " . $fsfcms_media_table . ".manufacturer AS media_manufacturer, name, speed, type, " . $fsfcms_media_table . ".slug AS media_slug FROM " 
                      . $fsfcms_images_table . ", " . $fsfcms_cameras_table . ", " . $fsfcms_media_table . " WHERE " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "' AND " . $fsfcms_images_table . ".id = " . $fsfcms_image_id . " LIMIT 1";
  } elseif($fsfcms_is_image_url)  {
  $fsfcms_image_request_year_month  = $_GET['yearMonth'];
  $fsfcms_image_request_title_slug  = $_GET['slug'];

  //  Set up the DB queries
  $fsfcms_getImage_query = "SELECT " . $fsfcms_images_table . ".id, filename, title, title_slug, caption, post, " . $fsfcms_cameras_table . ".manufacturer AS camera_manufacturer, " . $fsfcms_cameras_table . ".model AS camera_model, " . $fsfcms_cameras_table . ".slug AS camera_slug, " . $fsfcms_media_table . ".manufacturer AS media_manufacturer, name, speed, type, " . $fsfcms_media_table . ".slug AS media_slug FROM " 
                      . $fsfcms_images_table . ", " . $fsfcms_cameras_table . ", " . $fsfcms_media_table . 
                      " WHERE" . 
                      " EXTRACT(YEAR_MONTH FROM " . $fsfcms_images_table . ".post) = " . $fsfcms_image_request_year_month . 
                      " AND " . $fsfcms_images_table . ".title_slug = '" . $fsfcms_image_request_title_slug . "' AND " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "' LIMIT 1";
  } else  {

  //  No image id or url supplied, so default to the most recently posted image
    $fsfcms_getImage_query = "SELECT " . $fsfcms_images_table . ".id, filename, title, title_slug, caption, post, " . $fsfcms_cameras_table . ".manufacturer AS camera_manufacturer, " . $fsfcms_cameras_table . ".model AS camera_model, " . $fsfcms_cameras_table . ".slug AS camera_slug, " . $fsfcms_media_table . ".manufacturer AS media_manufacturer, name, speed, type, " . $fsfcms_media_table . ".slug AS media_slug FROM " 
                      . $fsfcms_images_table . ", " . $fsfcms_cameras_table . ", " . $fsfcms_media_table . " WHERE " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'ORDER BY post DESC LIMIT 1";

 /* $fsfcms_getImage_query = "SELECT " . $fsfcms_images_table . ".id, filename, title, title_slug, caption, post, " . $fsfcms_users_table . " .id AS author_id, name_first, name_middle, name_last, author_slug, " .$fsfcms_cameras_table . ".manufacturer AS camera_manufacturer, " . $fsfcms_cameras_table . ".model AS camera_model, " . $fsfcms_cameras_table . ".slug AS camera_slug, " . $fsfcms_media_table . ".manufacturer AS media_manufacturer, name, speed, type, " . $fsfcms_media_table . ".slug AS media_slug FROM " 
                      . $fsfcms_images_table . ", " . $fsfcms_authors_table . ", " . $fsfcms_users_table . ", " . $fsfcms_cameras_table . ", " . $fsfcms_media_table . " WHERE " . $fsfcms_images_table . ".id = " . $fsfcms_authors_table . ".image_parent_id AND " . $fsfcms_authors_table . ".user_id = " . $fsfcms_users_table . ".id AND " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "' ORDER BY post DESC LIMIT 1";
*/  }

$fsfcms_imageURL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portImageURL' LIMIT 1";
$fsfcms_imageURL_result = mysql_query($fsfcms_imageURL_query);
$fsfcms_imageURL_row    = mysql_fetch_row($fsfcms_imageURL_result);
$fsfcms_getImage_output['URL'] = $fsfcms_imageURL_row[0];

$fsfcms_getImage_result = mysql_query( $fsfcms_getImage_query);
$fsfcms_getImage_row    = mysql_fetch_row($fsfcms_getImage_result);
if(strlen($fsfcms_getImage_row[1]) > 0)
  {
  $fsfcms_getImage_output['id'] = $fsfcms_getImage_row[0];
  $fsfcms_getImage_output['filename'] = $fsfcms_getImage_row[1];
  $fsfcms_getImage_output['title'] = $fsfcms_getImage_row[2];
  $fsfcms_getImage_output['titleSlug'] = $fsfcms_getImage_row[3];
  $fsfcms_getImage_output['caption'] = trim($fsfcms_getImage_row[4]);
  $fsfcms_api_image_posted_date = $fsfcms_getImage_row[5]; 

  //  Convert the date to a Unix timestamp
  //  TODO: Convert the DB field to a Unix timestamp

  $fsfcms_getImage_output['postedDateUnixTimestamp'] = strtotime($fsfcms_api_image_posted_date);
  
  // Done with date and time, get the author information
  $fsfcms_getImage_authors  = fsf_port_getImageAuthors();
  $fsfcms_getImage_authors_status = array_pop($fsfcms_getImage_authors);  

  if($fsfcms_getImage_authors_status['status'] == 200)
    {
    $fsfcms_getImage_output['authors'] = $fsfcms_getImage_authors[$fsfcms_getImage_output['id']];
    } else  {
    $fsfcms_getImage_output['authors']  =  "404";
    }
  // Output the camera information, but first concatenate everything;
  $fsfcms_getImage_camera_manufacturer  = $fsfcms_getImage_row[6];
  $fsfcms_getImage_camera_model         = $fsfcms_getImage_row[7];
  $fsfcms_getImage_output['cameraManufacturer'] = $fsfcms_getImage_camera_manufacturer;
  $fsfcms_getImage_output['cameraName']         = $fsfcms_getImage_camera_model;
  $fsfcms_getImage_output['cameraFullName']     = $fsfcms_getImage_camera_manufacturer . " " . $fsfcms_getImage_camera_model; 
  $fsfcms_getImage_output['cameraSlug']         = $fsfcms_getImage_row[8];

  // Output the media information, but first concatenate everything
  $fsfcms_getImage_media_manufacturer = $fsfcms_getImage_row[9];
  $fsfcms_getImage_media_name         = $fsfcms_getImage_row[10];
  $fsfcms_getImage_media_speed        = $fsfcms_getImage_row[11];
  $fsfcms_getImage_output['mediaManufacturer']  = $fsfcms_getImage_media_manufacturer;
  $fsfcms_getImage_output['mediaName']          = $fsfcms_getImage_media_name;
  $fsfcms_getImage_output['mediaSpeed']         = $fsfcms_getImage_media_speed;
  $fsfcms_getImage_output['mediaFullName']      = $fsfcms_getImage_media_manufacturer . " " . $fsfcms_getImage_media_name . " " . $fsfcms_getImage_media_speed; 
  $fsfcms_getImage_output['mediaType']          = $fsfcms_getImage_row[12];
  $fsfcms_getImage_output['mediaSlug']          = $fsfcms_getImage_row[13];

  //  Done with the database, now get the image width & height
  $fsfcms_getImage_size = getimagesize($fsfcms_getImage_output['URL'].$fsfcms_getImage_output['filename']);
  $fsfcms_getImage_output['width'] = $fsfcms_getImage_size[0];
  $fsfcms_getImage_output['height'] = $fsfcms_getImage_size[1];

  //  Output the status
  $fsfcms_getImage_output['status'] = 200;
  } else  {
  $fsfcms_getImage_output['status'] = 404;
  }
  
header('Content-Type: application/json');
echo json_encode($fsfcms_getImage_output);

// Figure out the total script execution time and put it in the database for troubleshooting
$fsfcms_imageURL_ts_time_end = microtime(true);
$fsfcms_imageURL_ts_time_gen = $fsfcms_imageURL_ts_time_end - $fsfcms_imageURL_ts_time_start;
if($fsfcms_imageURL_ts_time_gen > 1)
  {
  mysql_query("INSERT INTO fsf_cms_api_times (id, api_file, execution_time, date) VALUES ('', 'fsf.port.getImage.php', " . $fsfcms_imageURL_ts_time_gen . ", NOW())");
  }
?>