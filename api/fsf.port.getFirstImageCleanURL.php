<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getfirstImageCleanURL_output = array();

$fsfcms_first_image_clean_URL_query      = "SELECT title, YEAR(" . $fsfcms_images_table . ".post) AS firstYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS firstMonth, title_slug FROM " . $fsfcms_images_table . " WHERE " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "' ORDER BY post ASC LIMIT 1";
$fsfcms_first_image_clean_URL_result     = mysql_query($fsfcms_first_image_clean_URL_query);
if(mysql_num_rows($fsfcms_first_image_clean_URL_result) > 0)
  {
  $fsfcms_first_image_clean_URL_row        = mysql_fetch_row($fsfcms_first_image_clean_URL_result);
  $fsfcms_first_image_title                = $fsfcms_first_image_clean_URL_row[0];
  $fsfcms_first_image_clean_URL            = "/" . $fsfcms_first_image_clean_URL_row[1] . "/" . $fsfcms_first_image_clean_URL_row[2] . "/" . $fsfcms_first_image_clean_URL_row[3];
  } else  {
  $fsfcms_first_image_title = null;
  $fsfcms_first_image_clean_URL = null;
  }
  
$fsfcms_getfirstImageCleanURL_output['firstCleanURL']     = $fsfcms_first_image_clean_URL;
$fsfcms_getfirstImageCleanURL_output['firstTitle']        = $fsfcms_first_image_title;

header('Content-Type: application/json');
echo json_encode($fsfcms_getfirstImageCleanURL_output);

?>
