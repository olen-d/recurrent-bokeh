<?php

require "../admin/cfg.php";
require "../admin/startDB.php";
require "../includes/fsf_cms_functions.php";

// Initialize Script Variables
$fsfcms_getImageShortLink_output  = array();
$fsfcms_getImageShortLink_none    = "FSFGISL-None-Found";
$fsfcms_short_server              = fsfcms_getSiteURLshortenerURL();

// Initialize Get Variables
$fsfcms_current_image_id       = $_GET['image_id'];

// Set Up the DB Queries

$fsfcms_getImageShortLink_query      = "SELECT key_prefix, short_key, title FROM " . $fsfcms_port_redirect_table . ", " . $fsfcms_images_table . " WHERE " . $fsfcms_port_redirect_table . ".image_id = " . $fsfcms_images_table . ".id AND " . $fsfcms_port_redirect_table . ".image_id = " . $fsfcms_current_image_id . " LIMIT 1";
//echo "<p>" . $fsfcms_getImageShortLink_query . "</p>";
$fsfcms_getImageShortLink_result     = mysql_query($fsfcms_getImageShortLink_query);
if(mysql_num_rows($fsfcms_getImageShortLink_result) > 0)
  {
  $fsfcms_getImageShortLink_row         = mysql_fetch_row($fsfcms_getImageShortLink_result);
  $fsfcms_getImageShortLink_URL         = $fsfcms_short_server . $fsfcms_getImageShortLink_row[0] . "/" . $fsfcms_getImageShortLink_row[1];
  $fsfcms_getImageShortLink_title       = $fsfcms_getImageShortLink_row[2];
  $fsfcms_getImageShortLink_output['imageShortLinkURL'] = $fsfcms_getImageShortLink_URL;
  $fsfcms_getImageShortLink_output['imageTitle']        = $fsfcms_getImageShortLink_title;
  $fsfcms_getImageShortLink_output['status']            = 200;
  } else  {
  $fsfcms_getImageShortLink_output['0'] = $fsfcms_getImageShortLink_none;
  $fsfcms_getImageShortLink_output['status']  = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getImageShortLink_output);
?>
