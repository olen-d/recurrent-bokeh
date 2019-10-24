<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getnextImageID_output = array();

// Initialize Get Variables
$fsfcms_current_image_id       = $_GET['image_id'];

// Set up the DB queries
$fsfcms_current_image_query       = "SELECT post FROM " . $fsfcms_images_table . " WHERE " . $fsfcms_images_table . ".id  = " . $fsfcms_current_image_id . " AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "' LIMIT 1";
$fsfcms_current_image_post_result = mysql_query($fsfcms_current_image_query);
$fsfcms_current_image_post_row    = mysql_fetch_row($fsfcms_current_image_post_result);
$fsfcms_current_image_post        = $fsfcms_current_image_post_row[0];

$fsfcms_next_image_query      = "SELECT id, title FROM " . $fsfcms_images_table . " WHERE " . $fsfcms_images_table . ".post > '" . $fsfcms_current_image_post. "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "' ORDER BY post ASC LIMIT 0,1";
$fsfcms_next_image_id_result  = mysql_query($fsfcms_next_image_query);
$fsfcms_next_image_id_row     = mysql_fetch_row($fsfcms_next_image_id_result);
$fsfcms_next_image_id         = $fsfcms_next_image_id_row[0];
$fsfcms_next_image_title      = $fsfcms_next_image_id_row[1];

$fsfcms_getnextImageID_output['nextId']     = $fsfcms_next_image_id;
$fsfcms_getnextImageID_output['nextTitle']  = $fsfcms_next_image_title;

header('Content-Type: application/json');
echo json_encode($fsfcms_getnextImageID_output);

?>
