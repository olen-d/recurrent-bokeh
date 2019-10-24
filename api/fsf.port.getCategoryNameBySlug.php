<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCategoryNameBySlug_output = array();

// Initialize Get Variables
$fsfcms_category_slug       = $_GET['categorySlug'];

// Set Up the DB Queries

$fsfcms_getCategoryNameBySlug_query      = "SELECT category_name FROM " . $fsfcms_category_names_table . " WHERE category_slug = '" . $fsfcms_category_slug . "' LIMIT 1";
//echo "<p>" . $fsfcms_getCategoryNameBySlug_query ."</p>";
$fsfcms_getCategoryNameBySlug_result     = mysql_query($fsfcms_getCategoryNameBySlug_query);

if(mysql_num_rows($fsfcms_getCategoryNameBySlug_result) > 0)
  {     
  $fsfcms_getCategoryNameBySlug_row       = mysql_fetch_row($fsfcms_getCategoryNameBySlug_result);
  $fsfcms_getCategoryNameBySlug_name      = $fsfcms_getCategoryNameBySlug_row[0];
  } else  {
  $fsfcms_getCategoryNameBySlug_name = null;
  }
  
$fsfcms_getCategoryNameBySlug_output['categoryName']     = $fsfcms_getCategoryNameBySlug_name;


header('Content-Type: application/json');
echo json_encode($fsfcms_getCategoryNameBySlug_output);
?>
