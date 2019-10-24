<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables

$fsfcms_getArchivePages_output                  = array();
$fsfcms_getArchivePages_none                    = "No archives by month were found. ";

$fsfcms_getArchivePages_thumbs_per_page_default = 9;

if (isset($_GET['thumbsPerPage']))
  { 
  $fsfcms_getArchivePages_thumbs_per_page = $_GET['thumbsPerPage'];
  if ($fsfcms_getArchivePages_thumbs_per_page == "" || !is_numeric($fsfcms_getArchivePages_thumbs_per_page))
    {      
    $fsfcms_getArchivePages_thumbs_per_page = $fsfcms_getArchivePages_thumbs_per_page_default;
    }
   }  else  {
   $fsfcms_getArchivePages_thumbs_per_page = $fsfcms_getArchivePages_thumbs_per_page_default;
   } 

if (isset($_GET['timeZoneOffset']))
  {
  $fsfcms_timezone_offset_mysql  = $_GET['timeZoneOffset'] . ":00";
  } else  {
  $fsfcms_timezone_offset_mysql  = "+00:00";
  }

if (isset($_GET['totalImages']))
  {
  $fsfcms_total_images  = $_GET['totalImages'];
  } else  {
  $fsfcms_total_images  = 0;
  }

//  Prepare the statement and access the database

$fsfcms_getArchivePages_query = "SELECT post, post_date FROM ( SELECT @rowNumber := @rowNumber +1 AS row_number, @rowNumberMostRecent := @rowNumberMostRecent +1 AS row_number_most_recent, post, DATE_FORMAT(CONVERT_TZ(post,'+00:00',?),'%M %e, %Y') AS post_date FROM ". FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".post < ? ORDER BY post DESC) AS doritos WHERE MOD(row_number,?) = 0 OR MOD(row_number_most_recent,?) = 0";

if($result = $fsfcms_db_link->query("SET @rowNumber :=0"))
  { 
  // REM Error Trap This
  }

if($result = $fsfcms_db_link->query("SET @rowNumberMostRecent :=-1"))
  { 
  // REM Error Trap This
  }

if($fsfcms_getArchivePages_statement  = $fsfcms_db_link->prepare($fsfcms_getArchivePages_query))
  {
  if($fsfcms_getArchivePages_statement->bind_param("ssii",$fsfcms_timezone_offset_mysql,$fsfcms_current_time_mysql_format,$fsfcms_getArchivePages_thumbs_per_page,$fsfcms_getArchivePages_thumbs_per_page))
    {
    if($fsfcms_getArchivePages_statement->execute())
      {
      if($fsfcms_getArchivePages_statement->bind_result($post,$post_date))
        {
        $i  = 1;
        $j  = 1;
        while ($row = $fsfcms_getArchivePages_statement->fetch())
          {
          if($i % 2 == 0)
            {   
            $fsfcms_getArchivePages_item = $post_date . " to " . $fsfcms_getArchivePages_item;
            $fsfcms_getArchivePages_output[$j] = $fsfcms_getArchivePages_item;
            $j++;
            $fsfcms_getArchivePages_item = ""; 
            } else  {
            $fsfcms_getArchivePages_item .= $post_date;
            }
          $i++; 
          }

        //  Check to see if the last page was divisible by the total number of images.
        if($fsfcms_total_images % $fsfcms_getArchivePages_thumbs_per_page != 0)
          {
          //echo "chickenpotpie: " . $j;

          $fsfcms_getArchivePages_odd_query = "SELECT post, DATE_FORMAT(CONVERT_TZ(post,'+00:00',?),'%M %e, %Y') AS post_date FROM ". FSFCMS_IMAGES_TABLE . 
                                              " WHERE " . FSFCMS_IMAGES_TABLE . ".post <= ? ORDER BY post DESC";
          
          if($fsfcms_getArchivePages_odd_statement = $fsfcms_db_link->prepare($fsfcms_getArchivePages_odd_query))
            {
            if($fsfcms_getArchivePages_odd_statement->bind_param("ss",$fsfcms_timezone_offset_mysql,$post))
              {
              if($fsfcms_getArchivePages_odd_statement->execute())
                {
                if($fsfcms_getArchivePages_odd_statement->bind_result($post_odd,$post_date_odd))
                  {
                  $fsfcms_getArchivePages_odd_statement->store_result();
                  $fsfcms_getArchivePages_odd_last_row_num  = $fsfcms_getArchivePages_odd_statement->num_rows -1; 
                  if($fsfcms_getArchivePages_odd_last_row_num == 0)
                    {
                    $fsfcms_getArchivePages_odd_statement->fetch();
                    $fsfcms_getArchivePages_output[$j]  = $post_date_odd;
                    } else  {
                    $fsfcms_getArchivePages_odd_statement->fetch();
                    $fsfcms_getArchivePages_item = $post_date_odd;
                    $fsfcms_getArchivePages_odd_statement->data_seek($fsfcms_getArchivePages_odd_last_row_num);
                    $fsfcms_getArchivePages_odd_statement->fetch();
                    $fsfcms_getArchivePages_output[$j]  = $post_date_odd . " to " . $fsfcms_getArchivePages_item;                    
                    }
                  } else  {
                  //  Bind failed
                  }
                } else  {
                //  Execute failed
                }
              } else  {
              //  Bind Failed
              }
            } else  {
            // Prepare failed
            }
   
          }
        $fsfcms_getArchivePages_statement->free_result();
        $fsfcms_getArchivePages_output['status']          = 200;
        } else  {
        $fsfcms_getArchivePages_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getArchivePages_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getArchivePages_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getArchivePages_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getArchivePages_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getArchivePages_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getArchivePages_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getArchivePages_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getArchivePages_output);
?>