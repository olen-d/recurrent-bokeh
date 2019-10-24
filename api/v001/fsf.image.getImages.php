<?php

// Record the start time of file execution for trouble shooting
$fsfcms_imageURL_ts_time_start = microtime(true);



require "../../admin/ac.php";
require "../../admin/cfg.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";
require "../../includes/fsf_cms_functions.php";
require "../fsf_api_functions.php";

// Initialize Script Variables
$fsfcms_getImage_output      = array();

//  Initialize Get Variables
$fsfcms_is_image_id   = false;
$fsfcms_is_image_url  = false;

$fsfcms_is_image_id   = isset($_GET['imageId']);
$fsfcms_is_image_url  = isset($_GET['yearMonth']) && isset($_GET['slug']);

//  Set up the DB queries
if($fsfcms_is_image_id)
  {
  $fsfcms_image_id                  = $_GET['imageId'];

  //  Get an image by ID
  //  TODO: FIX THIS TO MATCH DEFAULT BELOW
  //  TODO, SEC:  Check to see if this is a valid number, throw an error if not. Probably malformed request.
  $fsfcms_getImage_query  = "SELECT " . 
                                        FSFCMS_IMAGES_TABLE . ".id, filename, title, title_slug, caption, post, " . 
                                        FSFCMS_CAMERAS_TABLE . ".manufacturer AS camera_manufacturer, " . 
                                        FSFCMS_CAMERAS_TABLE . ".model AS camera_model, " . 
                                        FSFCMS_CAMERAS_TABLE . ".slug AS camera_slug, " . 
                                        FSFCMS_MEDIA_TABLE . ".manufacturer AS media_manufacturer, name, speed, type, " . 
                                        FSFCMS_MEDIA_TABLE . ".slug AS media_slug FROM " .
                                        FSFCMS_IMAGES_TABLE . ", " . 
                                        FSFCMS_CAMERAS_TABLE . ", " . 
                                        FSFCMS_MEDIA_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".camera_id = " . FSFCMS_CAMERAS_TABLE . ".id AND " . 
                                        FSFCMS_IMAGES_TABLE . ".media_id = " . FSFCMS_MEDIA_TABLE . ".id AND " . FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . ".id = ? LIMIT 1";

  $fsfcms_getImage_stmt   = $fsfcms_db_link->prepare($fsfcms_getImage_query);
  $fsfcms_getImage_stmt->execute(array($fsfcms_current_time_mysql_format,$fsfcms_image_id));

  } elseif($fsfcms_is_image_url)  {

  $fsfcms_image_request_year_month  = $_GET['yearMonth'];
  $fsfcms_image_request_title_slug  = $_GET['slug'];

  //  Get an image by URL
  //  TODO: FIX THIS TO MATCH DEFAULT BELOW
  $fsfcms_getImage_query  = "SELECT " . 
                                        FSFCMS_IMAGES_TABLE . ".id, filename, title, title_slug, caption, post, " . 
                                        FSFCMS_CAMERAS_TABLE . ".manufacturer AS camera_manufacturer, " . 
                                        FSFCMS_CAMERAS_TABLE . ".model AS camera_model, " . 
                                        FSFCMS_CAMERAS_TABLE . ".slug AS camera_slug, " . 
                                        FSFCMS_MEDIA_TABLE . ".manufacturer AS media_manufacturer, name, speed, type, " . 
                                        FSFCMS_MEDIA_TABLE . ".slug AS media_slug FROM " . 
                                        FSFCMS_IMAGES_TABLE . ", " . FSFCMS_CAMERAS_TABLE . ", " . FSFCMS_MEDIA_TABLE . " WHERE EXTRACT(YEAR_MONTH FROM " . 
                                        FSFCMS_IMAGES_TABLE . ".post) = ? AND " . 
                                        FSFCMS_IMAGES_TABLE . ".title_slug = ? AND " . 
                                        FSFCMS_IMAGES_TABLE . ".camera_id = " . FSFCMS_CAMERAS_TABLE . ".id AND " . 
                                        FSFCMS_IMAGES_TABLE . ".media_id = " . FSFCMS_MEDIA_TABLE . ".id AND " . 
                                        FSFCMS_IMAGES_TABLE . ".post < ? LIMIT 1";

  $fsfcms_getImage_stmt   = $fsfcms_db_link->prepare($fsfcms_getImage_query);
  $fsfcms_getImage_stmt->execute(array($fsfcms_image_request_year_month,$fsfcms_image_request_title_slug,$fsfcms_current_time_mysql_format));

  } else  {

    //  No image id or url supplied, so default to the most recently posted image
    $query  = "SELECT " . FSFCMS_IMAGES_TABLE . ".id, filename, title, title_slug, caption, post, comment_status FROM " .  
                          FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".post < ? ORDER BY post DESC LIMIT 1";

    $stmt   = $fsfcms_db_link->prepare($query);
    $stmt->execute(array($fsfcms_current_time_mysql_format));                                         
  }

//  Fetch the image query results

try {
  while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $images[] = array ( 
      id =>               $row[0],
      filename =>         $row[1],
      title =>            $row[2],
      titleSlug =>        $row[3],
      caption =>          trim($row[4]),
      publishDate =>      date(DATE_ISO8601, strtotime($row[5])),
      publishDateUnix =>  strtotime($row[5]),
      commentStatus =>    $row[6]
    );
  }

  //  Output the status
  $output['images'] = $images;
  $output['status'] = 200;
  } catch(PDOException $exception) {
  $output['status'] = 500;
  }
  
header('Content-Type: application/json');
echo json_encode($output);

// Figure out the total script execution time and put it in the database for troubleshooting
$fsfcms_imageURL_ts_time_end = microtime(true);
$fsfcms_imageURL_ts_time_gen = $fsfcms_imageURL_ts_time_end - $fsfcms_imageURL_ts_time_start;
if($fsfcms_imageURL_ts_time_gen > 1)
  {
  //mysql_query("INSERT INTO fsf_cms_api_times (id, api_file, execution_time, date) VALUES ('', 'fsf.port.getImage.php', " . $fsfcms_imageURL_ts_time_gen . ", NOW())");
  }
?>