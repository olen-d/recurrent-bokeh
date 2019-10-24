<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables


// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$cols       = array(0 => array("label" =>  "Category"),
                    1 => array("label" =>  "Images"),
                    2 => array("label" =>  "Slug")
                    );
$rows           = array();
$fsfcms_getCatStats_none            = "No category statistics were found. ";

$fsfcms_getCatStats_default_number  = 1000;

if (isset($_GET['number']))
  {
  $fsfcms_getCatStats_number = $_GET['number'];
  if ($fsfcms_getCatStats_number == "" || !is_numeric($fsfcms_getCatStats_number))
    {
    $fsfcms_getCatStats_number = $fsfcms_getCatStats_default_number;
    } 
  } else  {
  $fsfcms_getCatStats_number = $fsfcms_getCatStats_default_number;
  }

//  Prepare the statement and access the database
$fsfcms_getCatStats_query = "SELECT category_slug, COUNT(category_slug) AS category_count, category_name FROM " . FSFCMS_CATEGORY_NAMES_TABLE . 
                            ", " . FSFCMS_CATEGORIES_MAP_TABLE . ", ". FSFCMS_IMAGES_TABLE . 
                            " WHERE " . FSFCMS_CATEGORY_NAMES_TABLE. ".id = " . FSFCMS_CATEGORIES_MAP_TABLE . ".category_id AND " . FSFCMS_IMAGES_TABLE . ".id = " . 
                            FSFCMS_CATEGORIES_MAP_TABLE . ".parent_id AND " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY category_slug ORDER BY category_count DESC, category_name ASC LIMIT ?";
  
if($fsfcms_getCatStats_statement  = $fsfcms_db_link->prepare($fsfcms_getCatStats_query))
  {
  if($fsfcms_getCatStats_statement->bind_param("si",$fsfcms_current_time_mysql_format,$fsfcms_getCatStats_number))
    {
    if($fsfcms_getCatStats_statement->execute())
      {
      if($fsfcms_getCatStats_statement->bind_result($category_slug,$category_count,$category_name))
        { 
        while ($fsfcms_getCatStats_statement->fetch())
          {
          $rows[] =   array (
                              "l" => $category_name,
                              "v" => $category_count, 
                              "s" => $category_slug
                            );   
          }
        $fsfcms_getCatStats_output  = $rows;
        $fsfcms_getCatStats_statement->free_result();
        $fsfcms_getCatStats_output['status']          = 200;
        } else  {
        $fsfcms_getCatStats_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getCatStats_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getCatStats_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getCatStats_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getCatStats_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getCatStats_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getCatStats_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getCatStats_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getCatStats_output);
?>