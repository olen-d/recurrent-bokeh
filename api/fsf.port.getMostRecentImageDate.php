<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables


// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$fsfcms_getMostRecentImageDate_output       = array();
$fsfcms_getMostRecentImageDate_none         = "No image date was found. ";

if (isset($_GET['timeZoneOffset']))
  {
  $fsfcms_timezone_offset_mysql  = $_GET['timeZoneOffset'] . ":00";
  } else  {
  $fsfcms_timezone_offset_mysql  = "+00:00";
  }

//  Prepare the statement and access the database
$fsfcms_getMostRecentImageDate_query  = "SELECT EXTRACT(YEAR FROM CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . " .post,'+00:00',?)) AS imageYear, EXTRACT(MONTH FROM CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . 
                                        ".post,'+00:00',?)) AS imageMonth, EXTRACT(WEEK FROM CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . ".post,'+00:00',?)) AS imageWeek , UNIX_TIMESTAMP(" . FSFCMS_IMAGES_TABLE . 
                                        ".post) AS postedDateUnixTimestamp FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".post < ? ORDER BY " . FSFCMS_IMAGES_TABLE . ".post DESC LIMIT 1";

if($fsfcms_getMostRecentImageDate_statement  = $fsfcms_db_link->prepare($fsfcms_getMostRecentImageDate_query))
  {
  if($fsfcms_getMostRecentImageDate_statement->bind_param("ssss",$fsfcms_timezone_offset_mysql,$fsfcms_timezone_offset_mysql,$fsfcms_timezone_offset_mysql,$fsfcms_current_time_mysql_format))
    {
    if($fsfcms_getMostRecentImageDate_statement->execute())
      {
      if($fsfcms_getMostRecentImageDate_statement->bind_result($image_year,$image_month,$image_week,$posted_date_unix_timestamp))
        { 
        while ($fsfcms_getMostRecentImageDate_statement->fetch())
          {
          $fsfcms_getMostRecentImageDate_output['imageYear']                = $image_year;
          $fsfcms_getMostRecentImageDate_output['imageMonth']               = $image_month;
          $fsfcms_getMostRecentImageDate_output['imageWeek']                = $image_week;
          $fsfcms_getMostRecentImageDate_output['postedDateUnixTimestamp']  = $posted_date_unix_timestamp; 
          }
        $fsfcms_getMostRecentImageDate_statement->free_result();
        $fsfcms_getMostRecentImageDate_output['status']          = 200;
        } else  {
        $fsfcms_getMostRecentImageDate_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getMostRecentImageDate_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getMostRecentImageDate_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getMostRecentImageDate_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getMostRecentImageDate_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getMostRecentImageDate_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getMostRecentImageDate_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getMostRecentImageDate_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getMostRecentImageDate_output);
?>