<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

//  Initialize Script Variables
$fsfcms_getImage_output       = array();
$fsfcms_getImage_where_clause = "";

//  Initialize Get Variables
$fsfcms_getImage_sort       = $_GET['image_sort'];
$fsfcms_getImage_limit      = $_GET['image_limit'];

//  Set up the DB queries
$fsfcms_imageURL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portImageURL' LIMIT 1";

$fsfcms_getImage_where_clause = " WHERE " . $fsfcms_images_table . ".id = " . $fsfcms_authors_table . ".image_parent_id AND " . $fsfcms_authors_table . ".user_id = " . $fsfcms_users_table . ".id AND " . $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id AND " . $fsfcms_images_table . ".media_id = " . $fsfcms_media_table . ".id AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'";
$fsfcms_image_query = "SELECT " . $fsfcms_images_table . ".id, filename, title, title_slug, caption, post, " . $fsfcms_users_table . ".id AS author_id, name_first, name_middle, name_last, " .$fsfcms_cameras_table . ".manufacturer AS camera_manufacturer, " . $fsfcms_cameras_table . ".model AS camera_model, " . $fsfcms_cameras_table . ".slug AS camera_slug, " . $fsfcms_media_table . ".manufacturer AS media_manufacturer, name, speed, type, " . $fsfcms_media_table . ".slug AS media_slug FROM " 
                      . $fsfcms_images_table . ", " . $fsfcms_authors_table . ", " . $fsfcms_users_table . ", " . $fsfcms_cameras_table . ", " . $fsfcms_media_table . $fsfcms_getImage_where_clause . " ORDER BY post " . $fsfcms_getImage_sort . " LIMIT " . $fsfcms_getImage_limit;

$fsfcms_imageURL_result = mysql_query($fsfcms_imageURL_query);

$fsfcms_imageURL_row    = mysql_fetch_assoc($fsfcms_imageURL_result);

$fsfcms_getImage_images_URL = $fsfcms_imageURL_row['value'];

$fsfcms_image_result = mysql_query($fsfcms_image_query);

if(mysql_num_rows($fsfcms_image_result) > 0)
  {
  while ($fsfcms_image_row = mysql_fetch_row($fsfcms_image_result))
  {
    $fsfcms_getImage_size = getimagesize($fsfcms_getImage_images_URL.$fsfcms_image_row[1]);//print_r($fsfcms_getImage_size);exit; 
    
    // Preformat the times, consider making this a function
      $fsfcms_api_image_posted_date = $fsfcms_image_row[5];
      require "includes/fsf_api_datetime.php";
    $fsfcms_getImage_output[] = array (
                                      URL => $fsfcms_getImage_images_URL,
                                      id => $fsfcms_image_row[0],
                                      filename => $fsfcms_image_row[1],
                                      title => $fsfcms_image_row[2],
                                      titleSlug => $fsfcms_image_row[3],
                                      caption => trim($fsfcms_image_row[4]),
                                      postedDate => $fsfcms_api_image_posted_date,

                                      postedDateUnixTimestamp => $fsfcms_apidt_image_postedDateUnixTimestamp,
                                      postedDateMMDDYYYY => $fsfcms_apidt_image_postedDateMMDDYYYY,
                                      postedDateDots => $fsfcms_apidt_image_postedDateDots,
                                      postedDateLong => $fsfcms_apidt_image_postedDateLong,
                                      postedTime12Hour => $fsfcms_apidt_image_postedTime12Hour,
                                      postedTime24Hour => $fsfcms_apidt_image_postedTime24Hour, 
                                      postedTimeFuzzy => $fsfcms_apidt_image_postedTimeFuzzy,

                                      authorId => $fsfcms_image_row[6],
                                      authorFirstName => $fsfcms_image_row[7],
                                      authorMiddleName => $fsfcms_image_row[8],
                                      authorLastName => $fsfcms_image_row[9],
                                      cameraManufacturer => $fsfcms_image_row[10],
                                      cameraName => $fsfcms_image_row[11],
                                      cameraFullName => $fsfcms_image_row[10] . " " . $fsfcms_image_row[11], 
                                      cameraSlug => $fsfcms_image_row[12],
                                      mediaManufacturer => $fsfcms_image_row[13],
                                      mediaName => $fsfcms_image_row[14],
                                      mediaSpeed => $fsfcms_image_row[15],
                                      mediaFullName => $fsfcms_image_row[13] . " " . $fsfcms_image_row[14] . " " . $fsfcms_image_row[15], 
                                      mediaType => $fsfcms_image_row[16],
                                      mediaSlug => $fsfcms_image_row[17],


                                      width => $fsfcms_getImage_size[0],
                                      height => $fsfcms_getImage_size[1],
                                      type => $fsfcms_getImage_size['mime']
                                      );
  }
  //  Output the status
  $fsfcms_getImage_output['status'] = 200;
  } else  {
  $fsfcms_getImage_output['status'] = 404;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getImage_output);

?>