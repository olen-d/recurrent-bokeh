<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables


// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$fsfcms_getKeyStats_keywords        = array();
$fsfcms_getKeyStats_output          = array();
$fsfcms_getKeyStats_none            = "No keyword statistics were found. ";

$fsfcms_getKeyStats_default_number  = 1000;

if (isset($_GET['number']))
  {
  $fsfcms_getKeyStats_number = $_GET['number'];
  if ($fsfcms_getKeyStats_number == "" || !is_numeric($fsfcms_getKeyStats_number))
    {
    $fsfcms_getKeyStats_number = $fsfcms_getKeyStats_default_number;
    } 
  } else  {
  $fsfcms_getKeyStats_number = $fsfcms_getKeyStats_default_number;
  }

//  Prepare the statement and access the database

$fsfcms_getKeyStats_query = "SELECT keyword_slug, COUNT(keyword_slug) AS keyword_count, keyword FROM " . FSFCMS_KEYWORDS_TABLE . 
                            ", " . FSFCMS_KEYWORDS_MAP_TABLE . ", ". FSFCMS_IMAGES_TABLE . 
                            " WHERE " . FSFCMS_KEYWORDS_TABLE. ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id AND " . FSFCMS_IMAGES_TABLE . ".id = " . 
                            FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id AND " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY keyword_slug ORDER BY keyword_count DESC, keyword ASC LIMIT ?";

if($fsfcms_getKeyStats_statement  = $fsfcms_db_link->prepare($fsfcms_getKeyStats_query))
  {
  if($fsfcms_getKeyStats_statement->bind_param("si",$fsfcms_current_time_mysql_format,$fsfcms_getKeyStats_number))
    {
    if($fsfcms_getKeyStats_statement->execute())
      {
      if($fsfcms_getKeyStats_statement->bind_result($keyword_slug,$keyword_count,$keyword))
        { 
        while ($fsfcms_getKeyStats_statement->fetch())
          {
          $fsfcms_getKeyStats_keywords[$keyword_slug] = array (
                                                              keywordCount  =>  $keyword_count,
                                                              keyword       =>  $keyword
                                                              ); 
          }
        $fsfcms_getKeyStats_output  = $fsfcms_getKeyStats_keywords;
        $fsfcms_getKeyStats_statement->free_result();
        $fsfcms_getKeyStats_output['status']          = 200;
        } else  {
        $fsfcms_getKeyStats_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getKeyStats_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getKeyStats_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getKeyStats_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getKeyStats_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getKeyStats_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getKeyStats_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getKeyStats_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getKeyStats_output);
?>