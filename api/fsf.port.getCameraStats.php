<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables



$cols       = array(0 => array("label" =>  "Camera"),
                    1 => array("label" =>  "Images"),
                    2 => array("label" =>  "Slug")
                    );
$rows       = array();
$gcs_none   = "No camera statistics were found. ";

$fsfcms_getCameraStats_default_number  = 1000;

if (isset($_GET['number']))
  {
  $fsfcms_getCameraStats_number = $_GET['number'];
  if ($fsfcms_getCameraStats_number == "" || !is_numeric($fsfcms_getCameraStats_number))
    {
    $fsfcms_getCameraStats_number = $fsfcms_getCameraStats_default_number;
    } 
  } else  {
  $fsfcms_getCameraStats_number = $fsfcms_getCameraStats_default_number;
  }

//  Prepare the statement and access the database
$fsfcms_getCameraStats_query = "SELECT slug, COUNT(slug) AS camera_count, manufacturer, model FROM " . FSFCMS_CAMERAS_TABLE . 
                            ", " . FSFCMS_IMAGES_TABLE . 
                            " WHERE " . FSFCMS_CAMERAS_TABLE. ".id = " . FSFCMS_IMAGES_TABLE . ".camera_id " . 
                            " AND " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY slug ORDER BY camera_count DESC, manufacturer ASC, model ASC LIMIT ?";
  
if($fsfcms_getCameraStats_statement  = $fsfcms_db_link->prepare($fsfcms_getCameraStats_query))
  {
  if($fsfcms_getCameraStats_statement->bind_param("si",$fsfcms_current_time_mysql_format,$fsfcms_getCameraStats_number))
    {
    if($fsfcms_getCameraStats_statement->execute())
      {
      if($fsfcms_getCameraStats_statement->bind_result($slug,$camera_count,$manufacturer,$model)) 
        {
        $i  = 0;      
        while ($fsfcms_getCameraStats_statement->fetch())
          {
          $rows[] =   array (
                              "l" => $manufacturer . " " . $model,
                              "v" => $camera_count, 
                              "s" => $slug
                            );                                                  //  ); 
          }
        $fsfcms_getCameraStats_output  = $rows; //$fsfcms_getCameraStats_categories;
        $fsfcms_getCameraStats_statement->free_result();
        $fsfcms_getCameraStats_output['status']          = 200;
        } else  {
        $fsfcms_getCameraStats_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getCameraStats_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getCameraStats_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getCameraStats_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getCameraStats_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getCameraStats_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getCameraStats_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getCameraStats_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getCameraStats_output);
?>