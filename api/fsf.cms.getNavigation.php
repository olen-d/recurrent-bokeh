<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getNavigation_output            = array();
$fsfcms_getNavigation_sort_column       = $_GET['sortColumn'];

switch($fsfcms_getNavigation_sort_column)
  {
  case "dateCreated":
    $fsfcms_getNavigation_sort_column_name  = "date_created";
    break;    
  case "priority":
    $fsfcms_getNavigation_sort_column_name  = "sort_priority";
    break;    
  case "navigationAlias":
  default:
    $fsfcms_getNavigation_sort_column_name  = "navigation_alias";  
    break;
  }
  
$fsfcms_getNavigation_sort_order        = $_GET['sortOrder'];

if ($fsfcms_getNavigation_sort_order != "DESC")
  {
  $fsfcms_getNavigation_sort_order = "ASC";
  }

$fsfcms_getNavigation_sort              = $fsfcms_getNavigation_sort_column_name . " " . $fsfcms_getNavigation_sort_order;

// Set up the DB queries
$fsfcms_getNavigation_query   = "SELECT page_slug, navigation_alias FROM " 
                                . $fsfcms_pages_table . " WHERE navigation = 'visible' ORDER BY " . $fsfcms_getNavigation_sort;
$fsfcms_getNavigation_result  = mysql_query($fsfcms_getNavigation_query);

if($fsfcms_getNavigation_result)
  {
  if(mysql_num_rows($fsfcms_getNavigation_result) > 0)
    {
    while($fsfcms_getNavigation_row = mysql_fetch_row($fsfcms_getNavigation_result))
      {
      $fsfcms_getNavigation_output[]  = array(
                                              pageSlug        => $fsfcms_getNavigation_row[0],
                                              navigationAlias => $fsfcms_getNavigation_row[1]
                                              );
      }
    } else  {
    // fail (No Pages)
    }
  } else  {
  //fail (Database Error)
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getNavigation_output);
?>