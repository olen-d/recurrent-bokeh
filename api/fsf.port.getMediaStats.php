<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables

$fsfcms_getMediaStats_media           = array();
$fsfcms_getMediaStats_output          = array();
$fsfcms_getMediaStats_none            = "No media statistics were found. ";

$fsfcms_getMediaStats_default_number  = 1000;

if (isset($_GET['number']))
  {
  $fsfcms_getMediaStats_number = $_GET['number'];
  if ($fsfcms_getMediaStats_number == "" || !is_numeric($fsfcms_getMediaStats_number))
    {
    $fsfcms_getMediaStats_number = $fsfcms_getMediaStats_default_number;
    } 
  } else  {
  $fsfcms_getMediaStats_number = $fsfcms_getMediaStats_default_number;
  }

if  (isset($_GET['outputType']))
  {
  $output_type = $_GET['outputType'];
  }
//  Prepare the statement and access the database
$fsfcms_getMediaStats_query = "SELECT slug, COUNT(slug) AS media_count, manufacturer, name, speed FROM " . FSFCMS_MEDIA_TABLE . 
                            ", " . FSFCMS_IMAGES_TABLE . 
                            " WHERE " . FSFCMS_MEDIA_TABLE. ".id = " . FSFCMS_IMAGES_TABLE . ".media_id " . 
                            " AND " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY slug ORDER BY media_count DESC, manufacturer ASC, name ASC, speed ASC LIMIT ?";
  
if($fsfcms_getMediaStats_statement  = $fsfcms_db_link->prepare($fsfcms_getMediaStats_query))
  {
  if($fsfcms_getMediaStats_statement->bind_param("si",$fsfcms_current_time_mysql_format,$fsfcms_getMediaStats_number))
    {
    if($fsfcms_getMediaStats_statement->execute())
      {
      if($fsfcms_getMediaStats_statement->bind_result($slug,$media_count,$manufacturer,$name,$speed))
        {
        if($output_type == "nameOnly")
          {
          while ($fsfcms_getMediaStats_statement->fetch())
            {
            $fsfcms_getMediaStats_media[] = array (
                                                    "l" =>  $name,
                                                    "v" =>  $media_count,
                                                    "s" =>  $slug
                                                  );           
            }
          } else  { 
          while ($fsfcms_getMediaStats_statement->fetch())
            {
            $fsfcms_getMediaStats_media[] = array (
                                                    "l" =>  $manufacturer . " " . $name . " " . $speed,
                                                    "v" =>  $media_count,
                                                    "s" =>  $slug
                                                  );
            }
          }
        $fsfcms_getMediaStats_output  = $fsfcms_getMediaStats_media;
        $fsfcms_getMediaStats_statement->free_result();
        $fsfcms_getMediaStats_output['status']          = 200;
        } else  {
        $fsfcms_getMediaStats_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getMediaStats_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getMediaStats_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getMediaStats_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getMediaStats_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getMediaStats_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getMediaStats_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getMediaStats_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getMediaStats_output);
?>