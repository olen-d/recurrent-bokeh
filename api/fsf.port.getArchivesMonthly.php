<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables


// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$fsfcms_getArchivesMonthly_categories      = array();
$fsfcms_getArchivesMonthly_output          = array();
$fsfcms_getArchivesMonthly_none            = "No archives by month were found. ";

$fsfcms_getArchivesMonthly_default_number  = 1000;

if (isset($_GET['number']))
  {
  $fsfcms_getArchivesMonthly_number = $_GET['number'];
  if ($fsfcms_getArchivesMonthly_number == "" || !is_numeric($fsfcms_getArchivesMonthly_number))
    {
    $fsfcms_getArchivesMonthly_number = $fsfcms_getArchivesMonthly_default_number;
    } 
  } else  {
  $fsfcms_getArchivesMonthly_number = $fsfcms_getArchivesMonthly_default_number;
  }

if (isset($_GET['timeZoneOffset']))
  {
  $fsfcms_timezone_offset_mysql  = $_GET['timeZoneOffset'] . ":00";
  } else  {
  $fsfcms_timezone_offset_mysql  = "+00:00";
  }

//  Prepare the statement and access the database
$fsfcms_getArchivesMonthly_query = "SELECT EXTRACT(YEAR_MONTH FROM CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . " .post,'+00:00',?)) AS yearMonth, DATE_FORMAT(CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . 
                                ".post,'+00:00',?),'%Y/%m') AS slug, DATE_FORMAT(CONVERT_TZ(" . FSFCMS_IMAGES_TABLE . ".post,'+00:00',?),'%M %Y') AS archiveName, COUNT(" . FSFCMS_IMAGES_TABLE . 
                                ".id) AS imageCount FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY yearMonth ORDER BY yearMonth DESC LIMIT ?";

if($fsfcms_getArchivesMonthly_statement  = $fsfcms_db_link->prepare($fsfcms_getArchivesMonthly_query))
  {
  if($fsfcms_getArchivesMonthly_statement->bind_param("ssssi",$fsfcms_timezone_offset_mysql,$fsfcms_timezone_offset_mysql,$fsfcms_timezone_offset_mysql,$fsfcms_current_time_mysql_format,$fsfcms_getArchivesMonthly_number))
    {
    if($fsfcms_getArchivesMonthly_statement->execute())
      {
      if($fsfcms_getArchivesMonthly_statement->bind_result($year_month,$slug,$archive_name,$archive_count))
        { 
        while ($fsfcms_getArchivesMonthly_statement->fetch())
          {
          $fsfcms_getArchivesMonthly_categories[$slug]  = array (
                                                                  archiveName    =>  $archive_name,
                                                                  archiveCount   =>  $archive_count
                                                                  ); 
          }
        $fsfcms_getArchivesMonthly_output  = $fsfcms_getArchivesMonthly_categories;
        $fsfcms_getArchivesMonthly_statement->free_result();
        $fsfcms_getArchivesMonthly_output['status']          = 200;
        } else  {
        $fsfcms_getArchivesMonthly_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getArchivesMonthly_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getArchivesMonthly_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getArchivesMonthly_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getArchivesMonthly_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getArchivesMonthly_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getArchivesMonthly_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getArchivesMonthly_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getArchivesMonthly_output);
?>