<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTotalImagesByAuthor_output = array();
$fsfcms_getTotalImagesByAuthor_none   = "FSFPGTotal-Images-By-Author-None-Found";

if (isset($_GET['authorID']))
  {
  $fsfcms_getTotalImagesByAuthor_author_id = $_GET['authorID']; 
  }

// Get the total number of images
$fsfcms_getTotalImagesByAuthor_query  = "SELECT COUNT(" . $fsfcms_images_table . ".id) AS total_images_by_author FROM " . $fsfcms_images_table .  
                                        " INNER JOIN " . $fsfcms_authors_table . " ON " . $fsfcms_images_table . ".id = " . $fsfcms_authors_table . ".image_parent_id" .
                                        " WHERE " . $fsfcms_authors_table . ".user_id = '" . $fsfcms_getTotalImagesByAuthor_author_id . "' AND " . $fsfcms_images_table . ".post < '" . $fsfcms_current_time_mysql_format . "'";

$fsfcms_getTotalImagesByAuthor_result = mysql_query($fsfcms_getTotalImagesByAuthor_query);
   
if($fsfcms_getTotalImagesByAuthor_result)
  {
  $fsfcms_getTotalImagesByAuthor_row      = mysql_fetch_row($fsfcms_getTotalImagesByAuthor_result);
  $fsfcms_getTotalImagesByAuthor_output['totalImagesByAuthor'] = number_format($fsfcms_getTotalImagesByAuthor_row[0]);  
  } else  {
  $fsfcms_getTotalImagesByAuthor_output['0'] = $fsfcms_getTotalImagesByAuthor_none;
  }  

header('Content-Type: application/json');
echo json_encode($fsfcms_getTotalImagesByAuthor_output);
?>
