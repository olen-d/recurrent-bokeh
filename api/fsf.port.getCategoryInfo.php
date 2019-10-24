<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getCategoryInfo_output = array();

// Autodetect whether an ID or slug has been provided
if(isset($_GET['categoryId']))
  {
  $fsfcms_category_id         = $_GET['categoryId'];
  $fsfcms_getCategoryInfo_where_clause  = "id = " . $fsfcms_category_id; 
  } elseif(isset($_GET['categorySlug']))  {
  $fsfcms_category_slug       = $_GET['categorySlug'];
  $fsfcms_getCategoryInfo_where_clause  = "category_slug = '" . $fsfcms_category_slug . "'";
  }
  
// Set Up the DB Queries

$fsfcms_getCategoryInfo_query = "SELECT id, category_priority, category_name, category_slug, category_description, category_added FROM " . $fsfcms_category_names_table . 
                                " WHERE " . $fsfcms_getCategoryInfo_where_clause . " LIMIT 1";

$fsfcms_getCategoryInfo_result     = mysql_query($fsfcms_getCategoryInfo_query);

if(mysql_num_rows($fsfcms_getCategoryInfo_result) > 0)
  {     
  $fsfcms_getCategoryInfo_row       = mysql_fetch_row($fsfcms_getCategoryInfo_result);
  $fsfcms_getCategoryInfo_output['categoryId']          = $fsfcms_getCategoryInfo_row[0];
  $fsfcms_getCategoryInfo_output['categoryPriority']    = $fsfcms_getCategoryInfo_row[1];
  $fsfcms_getCategoryInfo_output['categoryName']        = $fsfcms_getCategoryInfo_row[2];
  $fsfcms_getCategoryInfo_output['categorySlug']        = $fsfcms_getCategoryInfo_row[3];
  $fsfcms_getCategoryInfo_output['categoryDescription'] = $fsfcms_getCategoryInfo_row[4];
  $fsfcms_getCategoryInfo_output['categoryAdded']       = $fsfcms_getCategoryInfo_row[5];
  } else  {
  $fsfcms_getCategoryInfo_output = null;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getCategoryInfo_output);
?>
