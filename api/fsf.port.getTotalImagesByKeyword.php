<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTotalImagesByKeyword_output = array();
$fsfcms_getTotalImagesByKeyword_none   = "FSFPGTotal-Images-By-Category-None-Found";

if (isset($_GET['keywordSlug']))
  {
  $fsfcms_getTotalImagesByKeyword_keyword_slug = $_GET['keywordSlug']; 
  }

// Get the total number of images
$fsfcms_getTotalImagesByKeyword_query  = "SELECT COUNT(" . FSFCMS_IMAGES_TABLE . ".id) AS total_images_by_keyword FROM " . FSFCMS_IMAGES_TABLE .  
                                        " INNER JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id" .
                                        " INNER JOIN " . FSFCMS_KEYWORDS_TABLE . " ON " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " . FSFCMS_KEYWORDS_TABLE . ".id " .
                                        " WHERE " . FSFCMS_KEYWORDS_TABLE . ".keyword_slug = '" . $fsfcms_getTotalImagesByKeyword_keyword_slug . "' AND " . FSFCMS_IMAGES_TABLE . ".post < '" . $fsfcms_current_time_mysql_format . "'";

$fsfcms_getTotalImagesByKeyword_result = mysql_query($fsfcms_getTotalImagesByKeyword_query);

if($fsfcms_getTotalImagesByKeyword_result)
  {
  $fsfcms_getTotalImagesByKeyword_row      = mysql_fetch_row($fsfcms_getTotalImagesByKeyword_result);
  $fsfcms_getTotalImagesByKeyword_output['totalImagesByKeyword'] = number_format($fsfcms_getTotalImagesByKeyword_row[0]);  
  } else  {
  $fsfcms_getTotalImagesByKeyword_output['0'] = $fsfcms_getTotalImagesByKeyword_none;
  }  

header('Content-Type: application/json');
echo json_encode($fsfcms_getTotalImagesByKeyword_output);
?>