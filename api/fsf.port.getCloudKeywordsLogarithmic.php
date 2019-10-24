<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_getCloudKeys_num_buckets  = 4;  // TODO: Remember to put these in the configuration.

// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$fsfcms_getCloudKeys_keywords       = array();
$fsfcms_getCloudKeys_output         = array();
$fsfcms_getCloudKeys_none           = "No keywords were found. ";

$fsfcms_getCloudKeys_default_number = 40;

if (isset($_GET['number']))
  {
  $fsfcms_getCloudKeys_number = $_GET['number'];
  if ($fsfcms_getCloudKeys_number == "" || !is_numeric($fsfcms_getCloudKeys_number))
    {
    $fsfcms_getCloudKeys_number = $fsfcms_getCloudKeys_default_number;
    } 
  } else  {
  $fsfcms_getCloudKeys_number = $fsfcms_getCloudKeys_default_number;
  }

//  Prepare the statement and access the database

if($fsfcms_getCloudKeys_statement  = $fsfcms_db_link->prepare("SELECT keyword, COUNT(keyword) AS keyword_count, keyword_slug FROM " . FSFCMS_KEYWORDS_TABLE . ", " . FSFCMS_KEYWORDS_MAP_TABLE . ", ". FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_KEYWORDS_TABLE. ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id AND " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id AND " . FSFCMS_IMAGES_TABLE . ".post < ? GROUP BY keyword ORDER BY keyword_count DESC, keyword ASC LIMIT ?"))
  {
  if($fsfcms_getCloudKeys_statement->bind_param("si",$fsfcms_current_time_mysql_format,$fsfcms_getCloudKeys_number))
    {
    if($fsfcms_getCloudKeys_statement->execute())
      {
      if($fsfcms_getCloudKeys_statement->bind_result($keyword,$keyword_count,$keyword_slug))
        { 
        while ($fsfcms_getCloudKeys_statement->fetch())
          {
          $fsfcms_getCloudKeys_keywords[$keyword] = array (
                                                          keywordCount  =>  $keyword_count,
                                                          keywordSlug   =>  $keyword_slug
                                                          ); 
          }
          if(count($fsfcms_getCloudKeys_keywords) <= 0)
            {
            $fsfcms_getCloudKeys_output[] = $fsfcms_getCloudKeys_none;
            }   else  {
            $fsfcms_gck_keyword_counts          = array();

            foreach ($fsfcms_getCloudKeys_keywords as $fsfcms_gck_keyword => $fsfcms_gck_keyword_attributes)
              {
              $fsfcms_gck_keyword_counts[$fsfcms_gck_keyword] = $fsfcms_gck_keyword_attributes['keywordCount'];             
              }
            $fsfcms_getCloudKeys_min            = min(array_values($fsfcms_gck_keyword_counts));
            $fsfcms_getCloudKeys_max            = max(array_values($fsfcms_gck_keyword_counts));
            $fsfcms_getCloudKeys_range          = $fsfcms_getCloudKeys_max - $fsfcms_getCloudKeys_min;

            // Loop through the tag array and assign to buckets
            $fsfcms_getCloudKeys_total_keywords  = count($fsfcms_getCloudKeys_keywords);
            if ($fsfcms_getCloudKeys_num_buckets < 1)
              {
              $fsfcms_getCloudKeys_num_buckets = 1;
              }
            $fsfcms_getCloudKeys_bucket          = $fsfcms_getCloudKeys_num_buckets;
            $fsfcms_getCloudKeys_prev_count      = $fsfcms_getCloudKeys_max;
            $fsfcms_getCloudKeys_i              = 1;

// End non-log specific
// Begin log specific code
  
            $fsfcms_getCloudKeys_log_max = log($fsfcms_getCloudKeys_max);
            $fsfcms_getCloudKeys_log_range = $fsfcms_getCloudKeys_log_max - log($fsfcms_getCloudKeys_min);
            $fsfcms_getCloudKeys_log_gap = $fsfcms_getCloudKeys_log_range / $fsfcms_getCloudKeys_num_buckets;
            $fsfcms_getCloudKeys_log_bucket_threshold = $fsfcms_getCloudKeys_log_max - $fsfcms_getCloudKeys_log_gap;
            foreach ($fsfcms_getCloudKeys_keywords as $fsfcms_getCloudKeys_keyword => $fsfcms_gck_keyword_attributes)
              {
              if (log($fsfcms_gck_keyword_attributes['keywordCount']) < $fsfcms_getCloudKeys_log_bucket_threshold)
                {				
                $fsfcms_getCloudKeys_bucket--;
                $fsfcms_getCloudKeys_log_bucket_threshold = $fsfcms_getCloudKeys_log_bucket_threshold - $fsfcms_getCloudKeys_log_gap;
                }	
              
              $fsfcms_getCloudKeys_keywords[$fsfcms_getCloudKeys_keyword]['keywordClassName']  = "size" . $fsfcms_getCloudKeys_bucket; 
              next($fsfcms_getCloudKeys_keywords);	// Theoretically, the foreach should increment this, I have no idea why it doesn't, hence the hack
              }            
              uksort($fsfcms_getCloudKeys_keywords,strnatcasecmp);
              $fsfcms_getCloudKeys_output = $fsfcms_getCloudKeys_keywords;
            }
        $fsfcms_getCloudKeys_statement->free_result();
        $fsfcms_getCloudKeys_output['status']          = 200;
        } else  {
        $fsfcms_getCloudKeys_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getCloudKeys_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getCloudKeys_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getCloudKeys_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getCloudKeys_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getCloudKeys_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getCloudKeys_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getCloudKeys_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getCloudKeys_output);
?>