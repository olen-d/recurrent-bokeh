<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables


// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$fsfcms_getArchiveStats_categories      = array();
$fsfcms_getArchiveStats_output          = array();
$fsfcms_getArchiveStats_none            = "No archive statistics were found. ";

$fsfcms_getArchiveStats_default_number  = 1000;

if (isset($_GET['number']))
  {
  $fsfcms_getArchiveStats_number = $_GET['number'];
  if ($fsfcms_getArchiveStats_number == "" || !is_numeric($fsfcms_getArchiveStats_number))
    {
    $fsfcms_getArchiveStats_number = $fsfcms_getArchiveStats_default_number;
    } 
  } else  {
  $fsfcms_getArchiveStats_number = $fsfcms_getArchiveStats_default_number;
  }

if (isset($_GET['timeZoneOffset']))
  {
  $fsfcms_timezone_offset_mysql  = $_GET['timeZoneOffset'] . ":00";
  } else  {
  $fsfcms_timezone_offset_mysql  = "+00:00";
  }

//  Prepare the statement and access the database
$fsfcms_getArchiveStats_query = "SELECT EXTRACT(YEAR_MONTH FROM CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . " .post,'+00:00',?)) AS yearMonth, DATE_FORMAT(CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . 
                                ".post,'+00:00',?),'%Y/%m') AS slug, DATE_FORMAT(CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . ".post,'+00:00',?),'%M %Y') AS archiveName, COUNT(" . FSFCMS_IMAGES_TABLE . 
                                ".id) AS imageCount FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY yearMonth ORDER BY imageCount DESC, yearMonth DESC LIMIT ?";

if($fsfcms_getArchiveStats_statement  = $fsfcms_db_link->prepare($fsfcms_getArchiveStats_query))
  {
  if($fsfcms_getArchiveStats_statement->bind_param("ssssi",$fsfcms_timezone_offset_mysql,$fsfcms_timezone_offset_mysql,$fsfcms_timezone_offset_mysql,$fsfcms_current_time_mysql_format,$fsfcms_getArchiveStats_number))
    {
    if($fsfcms_getArchiveStats_statement->execute())
      {
      if($fsfcms_getArchiveStats_statement->bind_result($year_month,$slug,$archive_name,$archive_count))
        { 
        while ($fsfcms_getArchiveStats_statement->fetch())
          {
          $fsfcms_getArchiveStats_categories[$slug]  = array (
                                                                  archiveCount   =>  $archive_count,
                                                                  archiveName    =>  $archive_name
                                                                  ); 
          }
        $fsfcms_getArchiveStats_output  = $fsfcms_getArchiveStats_categories;
        $fsfcms_getArchiveStats_statement->free_result();
        $fsfcms_getArchiveStats_output['status']          = 200;
        } else  {
        $fsfcms_getArchiveStats_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getArchiveStats_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getArchiveStats_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getArchiveStats_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getArchiveStats_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getArchiveStats_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getArchiveStats_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getArchiveStats_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getArchiveStats_output);
?>