<?php

//
//
//  Function to call the API and return the data via JSON
//
//
//  TODO: Update the function to take an argument for
//        JSON or XML. If nothing is specified, JSON
//        is returned by default.
//
//  Notes:  Arguments are passed to the function as an
//          array as a key=value pair, with the key being
//          the option. The filename of the API file is
//          always the last item in the array. While it
//          does not currently matter, for future
//          compatibility reasons, the key for the API
//          file should be "apiFile"

function fsf_cms_accessAPI($fsfcms_api_options)
  {
  $fsfcms_api_file  = array_pop($fsfcms_api_options);
  $fsfcms_api_query_string  = "?";
  foreach($fsfcms_api_options as $fsfcms_api_option => $fsfcms_api_value)
    {
    $fsfcms_api_query_string  .=  $fsfcms_api_option . "=" . $fsfcms_api_value . "&";
    }
  $fsfcms_api_query_string  = rtrim($fsfcms_api_query_string,"&");
  $fsfcms_api_query_string  = rtrim($fsfcms_api_query_string,"?");
  $fsfcms_cookies = "";

  // Get the cookies and dump them into a string that CURL can understand 
  foreach($_COOKIE as $cookie_name => $cookie_value)
    {
    $fsfcms_cookies .= $cookie_name . "=" . $cookie_value . ";"; 
    }

  $fsfcms_curl_connect_timeout  = 9000;     // milliseconds
  $fsfcms_curl_request_timeout  = 9000;     // milliseconds
  session_write_close();                          // necessary so that CURL can access the cookies.
  $fsfcms_curl_handle = curl_init();
  
  curl_setopt($fsfcms_curl_handle,CURLOPT_COOKIE,$fsfcms_cookies);  
  curl_setopt($fsfcms_curl_handle,CURLOPT_URL,FSFCMS_API_URL . $fsfcms_api_file . $fsfcms_api_query_string);
  curl_setopt($fsfcms_curl_handle,CURLOPT_RETURNTRANSFER,TRUE);
  curl_setopt($fsfcms_curl_handle,CURLOPT_CONNECTTIMEOUT_MS,$fsfcms_curl_connect_timeout);
  curl_setopt($fsfcms_curl_handle,CURLOPT_TIMEOUT_MS,$fsfcms_curl_request_timeout);
  $fsfcms_curl_content = curl_exec($fsfcms_curl_handle);
  curl_close($fsfcms_curl_handle);

  return $fsfcms_curl_content;  
  }

//
//
//  Archives Functions
//
//

//  Build the monthly archives dropdown

function fsf_cms_getArchivesMonthly_dropDown($fsfcms_archives_monthly_options)
  {
  $fsfcms_api_options                       = array();
  $fsfcms_api_options['timeZoneOffset']     = $fsfcms_archives_monthly_options['timeZoneOffset'];
  $fsfcms_api_options['apiFile']            = "fsf.port.getArchivesMonthly.php";

  $fsfcms_archives_monthly_json                  = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_archives_monthly                       = json_decode($fsfcms_archives_monthly_json,TRUE);

  $fsfcms_archives_current_month         = $fsfcms_archives_monthly_options['currentMonth'];
  
  if(array_pop($fsfcms_archives_monthly) ==  200)
    {
    foreach($fsfcms_archives_monthly as $fsfcms_archives_slug => $fsfcms_archives_attributes)
      {
      $fsfcms_archives_monthly_dropdown_output .=  "<li><a href=\"/archives/" . $fsfcms_archives_slug . "\">" . $fsfcms_archives_attributes['archiveName'] . "</a></li>";     
      }
    $fsfcms_archives_monthly_current_month    =  $fsfcms_archives_monthly[$fsfcms_archives_current_month]['archiveName'];
    $fsfcms_archives_monthly_dropdown_output  =  "<div id=\"archives-monthly-dropdown\"><div id=\"archives-monthly-current\"><span id=\"archives-monthly-current-text\">" . $fsfcms_archives_monthly_current_month . "&nbsp;<span id=\"drop-carat\">&#9660;</span></span></div><div id=\"archives-monthly-list\"><ol>" . $fsfcms_archives_monthly_dropdown_output . "</ol></div></div>";    


    $fsfcms_archives_month_slugs    = array_keys($fsfcms_archives_monthly);
    $fsfcms_archives_previous_month = $fsfcms_archives_month_slugs[array_search($fsfcms_archives_current_month,$fsfcms_archives_month_slugs)+1];
    $fsfcms_archives_next_month     = $fsfcms_archives_month_slugs[array_search($fsfcms_archives_current_month,$fsfcms_archives_month_slugs)-1];
    $fsfcms_archives_first_month    = array_shift($fsfcms_archives_month_slugs);
    $fsfcms_archives_last_month     = array_pop($fsfcms_archives_month_slugs);   

    $fsfcms_archives_monthly_dropdown_result            = array();
    $fsfcms_archives_monthly_dropdown_result['output']        = $fsfcms_archives_monthly_dropdown_output;
    $fsfcms_archives_monthly_dropdown_result['previousMonth'] = $fsfcms_archives_previous_month;
    $fsfcms_archives_monthly_dropdown_result['nextMonth']     = $fsfcms_archives_next_month;
    $fsfcms_archives_monthly_dropdown_result['firstMonth']    = $fsfcms_archives_first_month;
    $fsfcms_archives_monthly_dropdown_result['lastMonth']     = $fsfcms_archives_last_month;      
    }
  return $fsfcms_archives_monthly_dropdown_result;  
  }

//  Build the paged archives dropdown

function fsf_cms_getArchivesPaged_dropDown($fsfcms_archives_paged_options)
  {
  $fsfcms_api_options                   = array();
  $fsfcms_api_options['thumbsPerPage']  = $fsfcms_archives_paged_options['thumbsPerPage'];
  $fsfcms_api_options['timeZoneOffset'] = $fsfcms_archives_paged_options['timeZoneOffset'];
  $fsfcms_api_options['totalImages']    = $fsfcms_archives_paged_options['totalImages'];

  $fsfcms_api_options['apiFile']        = "fsf.port.getArchivesPaged.php";

  $fsfcms_archives_paged_json           = fsf_cms_accessAPI($fsfcms_api_options);  
  $fsfcms_archives_paged                = json_decode($fsfcms_archives_paged_json,TRUE);

  $fsfcms_archives_current_page         = $fsfcms_archives_paged_options['currentPage'];   
  if(array_pop($fsfcms_archives_paged) ==  200)
    {
    foreach($fsfcms_archives_paged as $fsfcms_archive_slug => $fsfcms_archive_dates)
      {
      $fsfcms_archives_paged_dropdown_output .=  "<li><a href=\"/archives/page/" . $fsfcms_archive_slug . "\">" . $fsfcms_archive_dates . "</a></li>";      
      }
    $fsfcms_archives_paged_current_dates    =  $fsfcms_archives_paged[$fsfcms_archives_current_page];
    $fsfcms_archives_paged_dropdown_output  =  "<div id=\"archives-paged-dropdown\"><div id=\"archives-paged-current\"><span id=\"archives-paged-current-text\">" . $fsfcms_archives_paged_current_dates . "&nbsp;<span id=\"drop-carat\">&#9660;</span></span></div><div id=\"archives-paged-list\"><ol>" . $fsfcms_archives_paged_dropdown_output . "</ol></div></div>";    
    }
  return $fsfcms_archives_paged_dropdown_output;
  }

//  Get the current image date for use in setting up the link to archives organized by time periods

function fsf_cms_getMostRecentImageDate($fsfcms_get_most_recent_image_date_options)
  {
  $fsfcms_api_options                   = array();
  $fsfcms_api_options['timeZoneOffset'] = $fsfcms_get_most_recent_image_date_options['timeZoneOffset'];
  $fsfcms_api_options['apiFile']        = "fsf.port.getMostRecentImageDate.php";
          
  $fsfcms_recent_image_date_json        = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_recent_image_date             = json_decode($fsfcms_recent_image_date_json,TRUE);

  return $fsfcms_recent_image_date;  
  }

function fsf_cms_MostRecentArchiveMonthlySlug($fsfcms_time_zone_offset)
  {
  $fsfcms_get_most_recent_image_date_options                    = array();
  $fsfcms_get_most_recent_image_date_options['timeZoneOffset']  = $fsfcms_time_zone_offset;

  $fsfcms_recent_image_date                 = fsf_cms_getMostRecentImageDate($fsfcms_get_most_recent_image_date_options);
  $fsfcms_most_recent_archive_monthly_slug  = $fsfcms_recent_image_date['imageYear'] . "/" . str_pad($fsfcms_recent_image_date['imageMonth'],2,"0",STR_PAD_LEFT);
  
  return $fsfcms_most_recent_archive_monthly_slug;  
  }

//
//
//  Statistics Functions
//
//

//  Build the dropdown to select a page to get statistics from

function fsf_cms_getStatsPages_dropDown()
  {
  $fsfcms_api_options                       = array();
  $fsfcms_api_options['apiFile']            = "fsf.cms.getStatsPages.php";

  $fsfcms_stats_pages_json                  = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_stats_pages                       = json_decode($fsfcms_stats_pages_json,TRUE);
  
  if(array_pop($fsfcms_stats_pages) ==  200)
    {
    $fsfcms_statsPages_select .=  "<select name=\"statsPages\" onchange=\"self.location.href=this.options[this.selectedIndex].value;\">";
    $fsfcms_statsPages_select .=  "<option value=\"\">SELECT AN OPTION...</option>";

    foreach($fsfcms_stats_pages as $fsfcms_page_name => $fsfcms_page_slug)
      {
      $fsfcms_statsPages_select .=  "<option value=\"/statistics/" . $fsfcms_page_slug . "\">" . strtoupper($fsfcms_page_name) . "</option>";     
      }
    $fsfcms_statsPages_select   .=  "</select>";
    }
    
  return $fsfcms_statsPages_select;  
  }

function fsf_cms_getArchiveStats($fsfcms_archive_stats_options)
  {
  $fsfcms_archive_stats_output              = "";
  $fsfcms_current_count                     = -99;
  $fsfcms_not_first_flag                    = FALSE;

  $fsfcms_api_options                       = array();
  $fsfcms_api_options['timeZoneOffset']     = $fsfcms_archive_stats_options;
  $fsfcms_api_options['apiFile']            = "fsf.port.getArchiveStats.php";

  $fsfcms_archive_stats_json                = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_archive_stats                     = json_decode($fsfcms_archive_stats_json,TRUE);
               
  if(array_pop($fsfcms_archive_stats) == 200)
    {        
    foreach($fsfcms_archive_stats as $fsfcms_archive_slug => $fsfcms_archive_attributes)
      {    
      if  ($fsfcms_archive_attributes['archiveCount'] != $fsfcms_current_count)
        {
        if($fsfcms_not_first_flag)
          {
          $fsfcms_archive_stats_output .= "</div></div>";
          }
        $fsfcms_archive_stats_output .= "<div class =\"stats-row\"><div class=\"stats-count\">" . 
                                      $fsfcms_archive_attributes['archiveCount'] . 
                                      "</div><div class=\"stats-item-name\"><a href=\"/archives/" . 
                                      $fsfcms_archive_slug . "\">" . $fsfcms_archive_attributes['archiveName']  . 
                                      "</a>";        
        } else  {
        $fsfcms_archive_stats_output .= ", <a href=\"/archives/" . $fsfcms_archive_slug . "\">" . $fsfcms_archive_attributes['archiveName'] . "</a>";        
        
        }
        $fsfcms_current_count = $fsfcms_archive_attributes['archiveCount'];
      $fsfcms_not_first_flag = TRUE;
      }
    }
  $fsfcms_archive_stats_output .=  "</div></div>"; 
  return $fsfcms_archive_stats_output;
  }

function fsf_cms_getCameraStats($fsfcms_camera_stats_options)
  {
  $fsfcms_camera_stats_output             = "";
  $fsfcms_current_count                     = -99;
  $fsfcms_not_first_flag                    = FALSE;

  $fsfcms_api_options                       = array();
  $fsfcms_api_options['apiFile']            = "fsf.port.getCameraStats.php";

  $fsfcms_camera_stats_json                = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_camera_stats                     = json_decode($fsfcms_camera_stats_json,TRUE);
               
  return $fsfcms_camera_stats;
  }
  /*
  if(array_pop($fsfcms_camera_stats) == 200)
    {        
    foreach($fsfcms_camera_stats as $fsfcms_camera_slug => $fsfcms_camera_attributes)
      {    
      if  ($fsfcms_camera_attributes['cameraCount'] != $fsfcms_current_count)
        {
        if($fsfcms_not_first_flag)
          {
          $fsfcms_camera_stats_output .= "</div></div>";
          }
        $fsfcms_camera_stats_output .= "<div class =\"stats-row\"><div class=\"stats-count\">" . 
                                      $fsfcms_camera_attributes['cameraCount'] . 
                                      "</div><div class=\"stats-item-name\"><a href=\"/cameras/" . 
                                      $fsfcms_camera_slug . "\">" . $fsfcms_camera_attributes['cameraName']  . 
                                      "</a>";        
        } else  {
        $fsfcms_camera_stats_output .= ", <a href=\"/cameras/" . $fsfcms_camera_slug . "\">" . $fsfcms_camera_attributes['cameraName'] . "</a>";        
        
        }
        $fsfcms_current_count = $fsfcms_camera_attributes['cameraCount'];
      $fsfcms_not_first_flag = TRUE;
      }
    }
  $fsfcms_camera_stats_output .=  "</div></div>"; 
  */
function fsf_cms_getCategoryStats($fsfcms_category_stats_options)
  {
  $fsfcms_category_stats_output             = "";
  $fsfcms_current_count                     = -99;
  $fsfcms_not_first_flag                    = FALSE;

  $fsfcms_api_options                       = array();
  $fsfcms_api_options['apiFile']            = "fsf.port.getCategoryStats.php";

  $fsfcms_category_stats_json                = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_category_stats                     = json_decode($fsfcms_category_stats_json,TRUE);
  return $fsfcms_category_stats;
  }
  
function fsf_cms_processCategoryStats($fsfcms_category_stats)
  {
  if(array_pop($fsfcms_category_stats) == 200)
    {
    foreach($fsfcms_category_stats as $fsfcms_category_slug => $fsfcms_category_attributes)
      {      
      if  ($fsfcms_category_attributes['categoryCount'] != $fsfcms_current_count)
        {
        if($fsfcms_not_first_flag)
          {
          $fsfcms_category_stats_output .= "</div></div>";
          }
        $fsfcms_category_stats_output .= "<div class =\"stats-row\"><div class=\"stats-count\">" . 
                                      $fsfcms_category_attributes['categoryCount'] . 
                                      "</div><div class=\"stats-item-name\"><a href=\"/categories/" . 
                                      $fsfcms_category_slug . "\">" . $fsfcms_category_attributes['category']  . 
                                      "</a>";        
        } else  {
        $fsfcms_category_stats_output .= ", <a href=\"/categories/" . $fsfcms_category_slug . "\">" . $fsfcms_category_attributes['category'] . "</a>";        
        
        }
        $fsfcms_current_count = $fsfcms_category_attributes['categoryCount'];
      $fsfcms_not_first_flag = TRUE;
      }
    }

  $fsfcms_category_stats_output .=  "</div></div>";  

  return  $fsfcms_category_stats_output;
  }

function fsf_cms_getKeywordStats($fsfcms_keyword_stats_options)
  {
  $fsfcms_keyword_stats_output              = "";
  $fsfcms_current_count                     = -99;
  $fsfcms_not_first_flag                    = FALSE;

  $fsfcms_api_options                       = array();
  $fsfcms_api_options['apiFile']            = "fsf.port.getKeywordStats.php";

  $fsfcms_keyword_stats_json                = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_keyword_stats                     = json_decode($fsfcms_keyword_stats_json,TRUE);
  
  if(array_pop($fsfcms_keyword_stats) == 200)
    {
    foreach($fsfcms_keyword_stats as $fsfcms_keyword_slug => $fsfcms_keyword_attributes)
      {
      if  ($fsfcms_keyword_attributes['keywordCount'] != $fsfcms_current_count)
        {
        if($fsfcms_not_first_flag)
          {
          $fsfcms_keyword_stats_output .= "</div></div>";
          }
        $fsfcms_keyword_stats_output .= "<div class =\"stats-row\"><div class=\"stats-count\">" . 
                                      $fsfcms_keyword_attributes['keywordCount'] . 
                                      "</div><div class=\"stats-item-name\"><a href=\"/keywords/" . 
                                      $fsfcms_keyword_slug . "\">" . $fsfcms_keyword_attributes['keyword']  . 
                                      "</a>";        
        } else  {
        $fsfcms_keyword_stats_output .= ", <a href=\"/keywords/" . $fsfcms_keyword_slug . "\">" . $fsfcms_keyword_attributes['keyword'] . "</a>";        
        
        }
        $fsfcms_current_count = $fsfcms_keyword_attributes['keywordCount'];
      $fsfcms_not_first_flag = TRUE;
      }
    }

  $fsfcms_keyword_stats_output .=  "</div></div>";     
  return  $fsfcms_keyword_stats_output;
  }

function fsf_cms_getMediaStats($fsfcms_media_stats_options)
  {
  $fsfcms_media_stats_output                = "";
  $fsfcms_current_count                     = -99;
  $fsfcms_not_first_flag                    = FALSE;

  $fsfcms_api_options                       = array();
  if(count($fsfcms_media_stats_options) > 0 && $fsfcms_media_stats_options != "")
    {
    foreach($fsfcms_media_stats_options as $option => $value)
      {
      $fsfcms_api_options[$option] = $value;
      }
    }
  $fsfcms_api_options['apiFile']            = "fsf.port.getMediaStats.php";

  $fsfcms_media_stats_json                = fsf_cms_accessAPI($fsfcms_api_options);
  $fsfcms_media_stats                     = json_decode($fsfcms_media_stats_json,TRUE);
  return $fsfcms_media_stats;
  }

function fsf_cms_processMediaStats($fsfcms_media_stats)
  {             
  if(array_pop($fsfcms_media_stats) == 200)
    {        
    foreach($fsfcms_media_stats as $fsfcms_media_slug => $fsfcms_media_attributes)
      {
       if  ($fsfcms_media_attributes['mediaCount'] != $fsfcms_current_count)
        {
        if($fsfcms_not_first_flag)
          {
          $fsfcms_media_stats_output .= "</div></div>";
          }
        $fsfcms_media_stats_output .= "<div class =\"stats-row\"><div class=\"stats-count\">" . 
                                      $fsfcms_media_attributes['mediaCount'] . 
                                      "</div><div class=\"stats-item-name\"><a href=\"/media/" . 
                                      $fsfcms_media_slug . "\">" . $fsfcms_media_attributes['mediaName']  . 
                                      "</a>";        
        } else  {
        $fsfcms_media_stats_output .= ", <a href=\"/media/" . $fsfcms_media_slug . "\">" . $fsfcms_media_attributes['cameraName'] . "</a>";        
        
        }
        $fsfcms_current_count = $fsfcms_media_attributes['mediaCount'];
      $fsfcms_not_first_flag = TRUE;
      }
    }
  $fsfcms_media_stats_output .=  "</div></div>"; 
  return $fsfcms_media_stats_output;
  }


//  TODO: Refactor everything below this point to use the above general call and move all formatting
//        to the functions included in the "fsf_cms_functions" file. The API functions should
//        only include JSON output straight from the database.  
//
//
// Portfolio Functions
//
//

//
//
//  Actually make the call to the API and return the result
//
//
//



// Get an arbitrary number of images, sorted arbitrarily
function fsf_port_getImages($fsf_port_getImage_sort, $fsf_port_getImage_limit)
  {
  global $fsfcms_api_url;
  $fsf_api_file = "fsf.port.getImages.php";
  $fsf_api_options = "";

  if($fsf_port_getImage_sort !="")
    {
    $fsf_api_options = "?image_sort=" . $fsf_port_getImage_sort;
    } else  {
    $fsf_api_options = "?image_sort=DESC";
    }
  if($fsf_port_getImage_sort !="")
    {
    $fsf_api_options .= "&image_limit=" . $fsf_port_getImage_limit;
    } else  {
    $fsf_api_options .= "&image_limit=5";
    }
  $fsf_port_getImages_content  = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);    
  return $fsf_port_getImages_content;
  }

// Get just one image  
function fsf_port_getImage($fsf_port_getImage_parameters)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file = "fsf.port.getImage.php";
  $fsf_api_options = "";

  if(!empty($fsf_port_getImage_parameters))
    {
    if($fsf_port_getImage_parameters['lookup'] == "id")
      {
      $fsfcms_image_id  = $fsf_port_getImage_parameters['imageId'];          
      if(preg_match("/^\d+$/", $fsfcms_image_id))
        {
        $fsf_api_options = "?imageId=" . $fsfcms_image_id;  
        }
      } elseif($fsf_port_getImage_parameters['lookup'] == "URL")  {
      $fsf_api_options  = "?yearMonth=" . $fsf_port_getImage_parameters['yearMonth'] . "&slug=" . $fsf_port_getImage_parameters['slug'];
      }
    }
  $fsf_port_getImage_content  = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);    

  return $fsf_port_getImage_content;
  }
  
function fsf_port_getPreviousImageID($fsf_port_currImage_id)
  {
  global $fsfcms_api_url;

  $fsf_api_file = "fsf.port.getPreviousImageID.php";
  $fsf_api_options = "";

  if($fsf_port_currImage_id !="")
    {
    $fsf_api_options = "?image_id=" . $fsf_port_currImage_id;
    }
  $fsf_port_getPreviousImageID_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);  

  return $fsf_port_getPreviousImageID_content;
  }



// Access the API and return a link to the PREVIOUS image, as well as the title.
// TODO:  Internationalize "reverse"
//        Make the pre "reverse" string user configurable

function fsf_port_getPreviousImageCleanURL($fsf_port_currImage_id)
  {
  // Initialize Variables
  $fsf_port_getPreviousImageCleanURL_content  = "";
  
  global  $fsfcms_api_url;
  global  $fsfcms_page_request;
  
  $fsf_api_file     = "fsf.port.getPreviousImageCleanURL.php";
  $fsf_api_options  = "";

  if($fsf_port_currImage_id !="")
    {
    $fsf_api_options  =   "?imageId=" . $fsf_port_currImage_id;
    $fsf_api_options  .=  "&pageReq=" . urlencode($fsfcms_page_request); 
    }
  $fsf_port_getPreviousImageCleanURL_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsf_port_getPreviousImageCleanURL_array  = json_decode($fsf_port_getPreviousImageCleanURL_json, true);
  $fsf_port_getPreviousImageCleanURL_URL    = $fsf_port_getPreviousImageCleanURL_array['previousCleanURL'];		  
  $fsf_port_getPreviousImageCleanURL_title  = $fsf_port_getPreviousImageCleanURL_array['previousTitle'];
  if($fsf_port_getPreviousImageCleanURL_URL != NULL)
    {
    $fsf_port_getPreviousImageCleanURL_content  = "<a href=\"" . $fsf_port_getPreviousImageCleanURL_URL . 
                                                  "\" title=\"" . $fsf_port_getPreviousImageCleanURL_title . 
                                                  "\">&laquo;&nbsp;reverse</a>";
    } else  {
    $fsf_port_getPreviousImageCleanURL_content  = "";
    }
  return $fsf_port_getPreviousImageCleanURL_content;                                                               
  }




function fsf_port_getNextImageID($fsf_port_currImage_id)
  {
  global $fsfcms_api_url;

  
  $fsf_api_file = "fsf.port.getNextImageID.php";
  $fsf_api_options = "";
                                
  if($fsf_port_currImage_id !="")
    {
    $fsf_api_options = "?image_id=" . $fsf_port_currImage_id;
    }    
  $fsf_port_getNextImageID_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);

  return $fsf_port_getNextImageID_content;
  }



// Access the API and return a link to the NEXT image, as well as the title.
// TODO:  Internationalize "forward"
//        Make the post "forward" string user configurable

function fsf_port_getNextImageCleanURL($fsf_port_currImage_id)
  {
  // Initialize Variables
  $fsf_port_getNextImageCleanURL_content  = "";

  global $fsfcms_api_url;  
  global  $fsfcms_page_request;
  
  $fsf_api_file     = "fsf.port.getNextImageCleanURL.php";
  $fsf_api_options  = "";

  if($fsf_port_currImage_id !="")
    {
    $fsf_api_options  = "?imageId=" . $fsf_port_currImage_id;
    $fsf_api_options  .=  "&pageReq=" . urlencode($fsfcms_page_request);  
    }
  $fsf_port_getNextImageCleanURL_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getNextImageCleanURL_array  = json_decode($fsf_port_getNextImageCleanURL_json, true);            
  $fsf_port_getNextImageCleanURL_URL    = $fsf_port_getNextImageCleanURL_array['nextCleanURL'];  
  $fsf_port_getNextImageCleanURL_title  = $fsf_port_getNextImageCleanURL_array['nextTitle'];
  if($fsf_port_getNextImageCleanURL_URL != NULL)
    {
    $fsf_port_getNextImageCleanURL_content  = "<a href=\"" . $fsf_port_getNextImageCleanURL_URL . 
                                              "\" title=\"" . $fsf_port_getNextImageCleanURL_title . 
                                              "\">forward&nbsp;&raquo;</a>";
    } else  {
    $fsf_port_getNextImageCleanURL_content  = "";
    }     
  return $fsf_port_getNextImageCleanURL_content; 
  }

function fsf_port_getFirstImageCleanURL()
  {
  // Initialize Variables
  $fsf_port_getFirstImageCleanURL_content  = "";

  global $fsfcms_api_url;  

  $fsf_api_file     = "fsf.port.getFirstImageCleanURL.php";
  $fsf_api_options  = "";

  $fsf_port_getFirstImageCleanURL_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getFirstImageCleanURL_array  = json_decode($fsf_port_getFirstImageCleanURL_json, true);            
  $fsf_port_getFirstImageCleanURL_URL    = $fsf_port_getFirstImageCleanURL_array['firstCleanURL'];  
  $fsf_port_getFirstImageCleanURL_title  = $fsf_port_getFirstImageCleanURL_array['firstTitle'];
  if($fsf_port_getFirstImageCleanURL_URL != NULL)
    {
    $fsf_port_getFirstImageCleanURL_content  = $fsf_port_getFirstImageCleanURL_URL;
    } else  {
    $fsf_port_getFirstImageCleanURL_content  = "";
    }     
  return $fsf_port_getFirstImageCleanURL_content; 
  }

//
//
// Access the API and return a list of keywords associated with a specific image (without links)
//
//

function fsf_port_getImageKeywordsNoLinks($fsf_port_image_id)
  {
  // Initialize Variables
  $fsf_port_getImageKeywords_output = "";

  global $fsfcms_api_url;  

  $fsf_api_file     = "fsf.port.getImageKeywords.php";
  $fsf_api_options  = "";

  if($fsf_port_image_id !="")
    {
    $fsf_api_options  = "?imageId=" . $fsf_port_image_id;
    } else  {
    $fsf_api_options  = "";
    }

  $fsf_port_getImageKeywordsNoLinks_json     = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageKeywordsNoLinks_keywords = json_decode($fsf_port_getImageKeywordsNoLinks_json, true);
  if(array_pop($fsf_port_getImageKeywordsNoLinks_keywords) == 200)
    {
    return $fsf_port_getImageKeywordsNoLinks_keywords;
    } else  {
    return $fsf_port_getImageKeywordsNoLinks_keywords['errorMessage'];
    } 
  }

//
//
// Access the API and return a list of keywords associated with a specific image (with links)
//
//

function fsf_port_getImageKeywords($fsf_port_image_id)
  {
  // Initialize Variables
  $fsf_port_getImageKeywords_output = "";

  global $fsfcms_api_url;  

  $fsf_api_file     = "fsf.port.getImageKeywords.php";
  $fsf_api_options  = "";

  if($fsf_port_image_id !="")
    {
    $fsf_api_options  = "?imageId=" . $fsf_port_image_id;
    } else  {
    $fsf_api_options  = "";
    }

  $fsf_port_getImageKeywords_json     = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageKeywords_keywords = json_decode($fsf_port_getImageKeywords_json, true);

  // Process the keywords
  if(array_pop($fsf_port_getImageKeywords_keywords) == 200)
    {
    foreach($fsf_port_getImageKeywords_keywords as $fsf_port_getImageKeywords_keyword)
      {   
      $fsf_port_getImageKeywords_output .= "<a href=\"/keywords/" . $fsf_port_getImageKeywords_keyword['keywordSlug'] . "\">" . $fsf_port_getImageKeywords_keyword['keyword'] . "</a>, ";
      } 
    $fsf_port_getImageKeywords_output = rtrim($fsf_port_getImageKeywords_output,", ");
    } else  {
    $fsf_port_getImageKeywords_output = $fsf_port_getImageKeywords_keywords['errorMessage'];
    }
  return $fsf_port_getImageKeywords_output;
  }

//
//
// Access the API and return a list of catagories associated with a specific image (without links)
//
//

function fsf_port_getImageCategoriesNoLinks($fsf_port_image_id)
  {                               
  // Initialize Variables
  $fsf_port_getImageCategoriesNoLinks_output = "";

  global $fsfcms_api_url;  

  $fsf_api_file     = "fsf.port.getImageCategories.php";
  $fsf_api_options  = "";

  if($fsf_port_image_id !="")
    {
    $fsf_api_options  = "?image_id=" . $fsf_port_image_id;
    } else  {
    $fsf_api_options  = "";
    }

  $fsf_port_getImageCategoriesNoLinks_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageCategoriesNoLinks_categories = json_decode($fsf_port_getImageCategoriesNoLinks_json, true);
  return $fsf_port_getImageCategoriesNoLinks_categories;
  }


//
//
// Access the API and return a list of catagories associated with a specific image (with links)
//
//

function fsf_port_getImageCategories($fsf_port_image_id)
  {                               
  // Initialize Variables
  $fsf_port_getImageCategories_output = "";

  global $fsfcms_api_url;  

  $fsf_api_file     = "fsf.port.getImageCategories.php";
  $fsf_api_options  = "";

  if($fsf_port_image_id !="")
    {
    $fsf_api_options  = "?image_id=" . $fsf_port_image_id;
    } else  {
    $fsf_api_options  = "";
    }

  $fsf_port_getImageCategories_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageCategories_categories = json_decode($fsf_port_getImageCategories_json, true);
  $fsf_port_getImageCategories_output     = array();
      
  // Process the categories                  
    if($fsf_port_getImageCategories_categories['0'] == "FSFPGIC-None-Found")
    {
    $fsf_port_getImageCategories_output = "No categories were found for this image.";
    } else { 
    $fsf_port_getImageCategories_multiple = "NO";
    if (count($fsf_port_getImageCategories_categories) > 1)
      {
      $fsf_port_getImageCategories_multiple = "YES";      
      }
    $fsf_port_getImageCategories_output['multipleCategories'] = $fsf_port_getImageCategories_multiple;
    foreach($fsf_port_getImageCategories_categories as $fsf_port_getImageCategories_category)
      {   
      $fsf_port_getImageCategories_links .= "<a href=\"/categories/" . strtolower($fsf_port_getImageCategories_category['categorySlug']) . "\">" . ucwords($fsf_port_getImageCategories_category['categoryName']) . "</a>, ";
      } 
    $fsf_port_getImageCategories_links = rtrim($fsf_port_getImageCategories_links,", ");  
    $fsf_port_getImageCategories_output['categoriesWithLinks'] = $fsf_port_getImageCategories_links;
    } 
  return $fsf_port_getImageCategories_output;
  }
  
function fsf_port_getImageLinkCleanURL($fsf_port_currImage_id)
  {
  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getImageLinkCleanURL.php";
  $fsf_api_options  = "";

  if($fsf_port_currImage_id !="")
    {
    $fsf_api_options  = "?image_id=" . $fsf_port_currImage_id; 
    }
  $fsf_port_getImageLinkCleanURL_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsf_port_getImageLinkCleanURL_content;                                                               
  }



//
//
// Access the API and return a list of authors
//
//

function fsf_port_getAuthorsCleanURL()
  {
  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getAuthorsCleanURL.php";
  $fsf_api_options  = ""; 
  $fsf_port_getAuthorsCleanURL_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getAuthorsCleanURL_content; 
  }

function fsf_port_getCategoriesCleanURL()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCategoriesCleanURL.php";
  $fsf_api_options  = ""; 
  $fsf_port_getCategoriesCleanURL_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCategoriesCleanURL_content; 
  }

function fsf_port_getCategoriesList()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCategoriesList.php";
  $fsf_api_options  = ""; 
  $fsf_port_getCategoriesList_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCategoriesList_content; 
  }

function fsf_port_getCategoryInfo($fsf_port_category_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCategoryInfo.php";
  $fsf_api_options  = "?categoryId=" . $fsf_port_category_id; 
  $fsf_port_getCategoryInfo_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCategoryInfo_content; 
  }

function fsf_port_getCamerasCleanURL()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCamerasCleanURL.php";
  $fsf_api_options  = ""; 
  $fsf_port_getCamerasCleanURL_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCamerasCleanURL_content; 
  }

function fsf_port_getCamerasList()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCamerasList.php";
  $fsf_api_options  = ""; 
  $fsf_port_getCamerasList_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCamerasList_content; 
  }

function fsf_port_getCameraInfo($fsf_port_camera_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCameraInfo.php";
  $fsf_api_options  = "?cameraId=" . $fsf_port_camera_id; 
  $fsf_port_getCameraInfo_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCameraInfo_content; 
  }

function fsf_port_getMediaCleanURL()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getMediaCleanURL.php";
  $fsf_api_options  = ""; 
  $fsf_port_getMediaCleanURL_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getMediaCleanURL_content; 
  } 

function fsf_port_getMediaList()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getMediaList.php";
  $fsf_api_options  = ""; 
  $fsf_port_getMediaList_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getMediaList_content; 
  }

function fsf_port_getMediaInfo($fsf_port_media_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getMediaInfo.php";
  $fsf_api_options  = "?mediaId=" . $fsf_port_media_id; 
  $fsf_port_getMediaInfo_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getMediaInfo_content; 
  }

function fsf_port_getAuthorImageIDs($fsfcms_getAuthorImageIDs_authorID)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getAuthorImageIDs.php";
  $fsf_api_options  = "?authorID=" . $fsfcms_getAuthorImageIDs_authorID; 
  $fsf_port_getAuthorImageIDs_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getAuthorImageIDs_content; 
  }


function fsf_port_getCategoryImageIDs($fsfcms_getCategoryImageIDs_category_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCategoryImageIDs.php";
  $fsf_api_options  = "?categoryId=" . $fsfcms_getCategoryImageIDs_category_id; 
  $fsf_port_getCategoryImageIDs_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCategoryImageIDs_content; 
  }


function fsf_port_getCameraImageIDs($fsfcms_getCameraImageIDs_camera_slug)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCameraImageIDs.php";
  $fsf_api_options  = "?cameraSlug=" . urlencode($fsfcms_getCameraImageIDs_camera_slug); 
  $fsf_port_getCameraImageIDs_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCameraImageIDs_content; 
  }

function fsf_port_getImageThumbnailByID($fsfcms_image_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getImageThumbnailByID.php";
  $fsf_api_options  = "?image_id=" . $fsfcms_image_id; 
  $fsf_port_getImageThumbnailByID_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getImageThumbnailByID_content; 
  }

function fsf_port_getMediaImageIDs($fsfcms_getMediaImageIDs_media_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getMediaImageIDs.php";
  $fsf_api_options  = "?mediaId=" . $fsfcms_getMediaImageIDs_media_id; 
  $fsf_port_getMediaImageIDs_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getMediaImageIDs_content; 
  }

function fsf_port_getMediaImageThumbnails($fsfcms_media_slug,$fsfcms_page,$fsfcms_items)
  {
  $fsf_port_getMediaImageThumbnails_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getMediaImageThumbnails.php";
  $fsf_api_options  = "?mediaSlug=" . urlencode($fsfcms_media_slug); 

  if($fsfcms_items !="")
    {
    $fsf_api_options_i  = "&items=" . $fsfcms_items; 
    if($fsfcms_page != "")
      {
      $fsf_api_options_p  = "&page=" . $fsfcms_page;
      } else  {
      $fsf_api_options_p  = "&page=1";
      }
    } else  {
    $fsf_api_options_p = "";
    $fsf_api_options_i = "";
    }

  $fsf_api_options  .=  $fsf_api_options_p . $fsf_api_options_i;

  $fsf_port_getMediaImageThumbnails_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getMediaImageThumbnails_thumbnails = json_decode($fsf_port_getMediaImageThumbnails_json, true);
  $fsf_port_getMediaImageThumbnails_content    = fsf_port_media_thumbnail_links($fsf_port_getMediaImageThumbnails_thumbnails,$fsfcms_media_slug);
  return $fsf_port_getMediaImageThumbnails_content; 
  }

function fsf_port_getMediaNameBySlug($fsfcms_getMediaNameBySlug_media_slug)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getMediaNameBySlug.php";
  $fsf_api_options  = "?mediaSlug=" . $fsfcms_getMediaNameBySlug_media_slug; 
  $fsf_port_getMediaNameBySlug_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getMediaNameBySlug_content; 
  }

//
//
//  Access the API and return image thumbnails
//
//

function fsf_port_getImageThumbnails($fsfcms_page,$fsfcms_items)
  {
  $fsf_port_getImageThumbnails_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file       = "fsf.port.getImageThumbnails.php";

  if($fsfcms_items !="")
    {
    $fsf_api_options_i  = "&items=" . $fsfcms_items; 
    if($fsfcms_page != "")
      {
      $fsf_api_options_p  = "?page=" . $fsfcms_page;
      } else  {
      $fsf_api_options_p  = "?page=1";
      }
    } else  {
    $fsf_api_options_p = "";
    $fsf_api_options_i = "";
    }


  $fsf_api_options = $fsf_api_options_p . $fsf_api_options_i; 
  $fsf_port_getImageThumbnails_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageThumbnails_thumbnails = json_decode($fsf_port_getImageThumbnails_json, true);
  $fsf_port_getImageThumbnails_content    = fsf_port_thumbnail_links($fsf_port_getImageThumbnails_thumbnails);

  return $fsf_port_getImageThumbnails_content;
  } 

//
//
//  Access the API and return image thumbnails for the archive pages
//
//

function fsf_port_getArchiveImageThumbnails($fsfcms_getImageThumbnails_options)
  {
  $fsf_port_getImageThumbnails_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file       = "fsf.port.getImageThumbnails.php";
  
  if(empty($fsfcms_getImageThumbnails_options))
    {
    $fsf_api_options  = "";
    } else  {
    $fsf_api_options  = "?";
    foreach($fsfcms_getImageThumbnails_options as $key => $value)
      {
      $fsf_api_options  .=  $key . "=" . $value . "&";
      }
      $fsf_api_options  =   rtrim($fsf_api_options,"&");
    }
 
  $fsf_port_getImageThumbnails_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageThumbnails_thumbnails = json_decode($fsf_port_getImageThumbnails_json, true);
  $fsf_port_getImageThumbnails_content    = fsf_port_archive_thumbnail_links($fsf_port_getImageThumbnails_thumbnails);

  return $fsf_port_getImageThumbnails_content;
  }

//
//
//  Access the API and return image thumbnails for a specified author
//  
//

function fsf_port_getAuthorImageThumbnails($fsfcms_author_id,$fsfcms_page,$fsfcms_items,$fsf_cms_author_slug)
  {
  $fsf_port_getAuthorImageThumbnails_content  = "";

  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getAuthorImageThumbnails.php";
  $fsf_api_options  = "?authorId=" . $fsfcms_author_id; 

  if($fsfcms_items !="")
    {
    $fsf_api_options_i  = "&items=" . $fsfcms_items; 
    if($fsfcms_page != "")
      {
      $fsf_api_options_p  = "&page=" . $fsfcms_page;
      } else  {
      $fsf_api_options_p  = "&page=1";
      }
    } else  {
    $fsf_api_options_p = "";
    $fsf_api_options_i = "";
    }

  $fsf_api_options  .=  $fsf_api_options_p . $fsf_api_options_i;
  
  $fsf_port_getAuthorImageThumbnails_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getAuthorImageThumbnails_thumbs = json_decode($fsf_port_getAuthorImageThumbnails_json, true);
  $fsf_port_getAuthorImageThumbnails_content  = fsf_port_author_thumbnail_links($fsf_port_getAuthorImageThumbnails_thumbs,$fsf_cms_author_slug);
  return $fsf_port_getAuthorImageThumbnails_content; 
  }

//
//
//  Access the API and return image thumbnails for a specified camera model
//  
//
  
function fsf_port_getCameraImageThumbnails($fsfcms_camera_slug,$fsfcms_page,$fsfcms_items)
  {
  $fsf_port_getCameraImageThumbnails_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getCameraImageThumbnails.php";
  $fsf_api_options  = "?cameraSlug=" . urlencode($fsfcms_camera_slug);
  
  if($fsfcms_items !="")
    {
    $fsf_api_options_i  = "&items=" . $fsfcms_items; 
    if($fsfcms_page != "")
      {
      $fsf_api_options_p  = "&page=" . $fsfcms_page;
      } else  {
      $fsf_api_options_p  = "&page=1";
      }
    } else  {
    $fsf_api_options_p = "";
    $fsf_api_options_i = "";
    }

  $fsf_api_options  .=  $fsf_api_options_p . $fsf_api_options_i;
  
  $fsf_port_getCameraImageThumbnails_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getCameraImageThumbnails_thumbnails = json_decode($fsf_port_getCameraImageThumbnails_json, true);
  $fsf_port_getCameraImageThumbnails_content    = fsf_port_camera_thumbnail_links($fsf_port_getCameraImageThumbnails_thumbnails,$fsfcms_camera_slug);
  return $fsf_port_getCameraImageThumbnails_content; 
  }

//
//
//  Access the API and return image thumbnails for a specified category
//  
//
  
function fsf_port_getCategoryImageThumbnails($fsfcms_category_slug,$fsfcms_page,$fsfcms_items)
  {
  $fsf_port_getCategoryImageThumbnails_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getCategoryImageThumbnails.php";
  $fsf_api_options  = "?categorySlug=" . urlencode($fsfcms_category_slug); 

  if($fsfcms_items !="")
    {
    $fsf_api_options_i  = "&items=" . $fsfcms_items; 
    if($fsfcms_page != "")
      {
      $fsf_api_options_p  = "&page=" . $fsfcms_page;
      } else  {
      $fsf_api_options_p  = "&page=1";
      }
    } else  {
    $fsf_api_options_p = "";
    $fsf_api_options_i = "";
    }

  $fsf_api_options  .=  $fsf_api_options_p . $fsf_api_options_i;

  $fsf_port_getCategoryImageThumbnails_json       = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getCategoryImageThumbnails_thumbnails = json_decode($fsf_port_getCategoryImageThumbnails_json, true);
  $fsf_port_getCategoryImageThumbnails_content    = fsf_port_category_thumbnail_links($fsf_port_getCategoryImageThumbnails_thumbnails,$fsfcms_category_slug);
  return $fsf_port_getCategoryImageThumbnails_content; 
  }

function fsf_port_getCategoryNameBySlug($fsfcms_category_slug)
  {
  $fsf_port_getCategoryNameBySlug_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getCategoryNameBySlug.php";
  $fsf_api_options  = "?categorySlug=" . urlencode($fsfcms_category_slug); 
  $fsf_port_getCategoryNameBySlug_json    = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getCategoryNameBySlug_name    = json_decode($fsf_port_getCategoryNameBySlug_json, true);
  $fsf_port_getCategoryNameBySlug_content = $fsf_port_getCategoryNameBySlug_name['categoryName'];
  return $fsf_port_getCategoryNameBySlug_content;  
  }


//
//  Access the API and return attributes for a specified keyword
//

function fsf_port_getKeywordAttributes($fsfcms_keyword_slug)
  {
  $fsf_port_getKeywordAttributes_content  = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getKeywordAttributes.php";
  $fsf_api_options  = "?keywordSlug=" . urlencode($fsfcms_keyword_slug); 
  $fsf_port_getKeywordAttributes_json     = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getKeywordAttributes_content  = json_decode($fsf_port_getKeywordAttributes_json, true);
  
  return $fsf_port_getKeywordAttributes_content;  
  }

//
//
//  Access the API and return image thumbnails for a specified keyword
//  
//  REM DELETEME
  
function fsf_port_getKeywordImageThumbnails($fsfcms_keyword,$fsfcms_page,$fsfcms_items)
  {
  $fsf_port_getKeywordImageThumbnails_content = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getKeywordImageThumbnails.php";
  $fsf_api_options  = "?keyword=" . urlencode(str_replace("-","_",$fsfcms_keyword)); 

  if($fsfcms_items !="")
    {
    $fsf_api_options_i  = "&items=" . $fsfcms_items; 
    if($fsfcms_page != "")
      {
      $fsf_api_options_p  = "&page=" . $fsfcms_page;
      } else  {
      $fsf_api_options_p  = "&page=1";
      }
    } else  {
    $fsf_api_options_p = "";
    $fsf_api_options_i = "";
    }

  $fsf_api_options  .=  $fsf_api_options_p . $fsf_api_options_i;

  $fsf_port_getKeywordImageThumbnails_json        = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getKeywordImageThumbnails_thumbnails  = json_decode($fsf_port_getKeywordImageThumbnails_json, true);
  $fsf_port_getKeywordImageThumbnails_content     = fsf_port_keyword_thumbnail_links($fsf_port_getKeywordImageThumbnails_thumbnails);
  return $fsf_port_getKeywordImageThumbnails_content; 
  }

function fsf_port_getCloudKeywordsLogarithmic($fsfcms_number)
  {
  $fsf_port_getCloudKeywordsLogarithmic_output  = "";

  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCloudKeywordsLogarithmic.php";
  $fsf_api_options  = "?number=" . $fsfcms_number; 
  $fsf_port_getCloudKeywordsLogarithmic_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsfcms_getCloudKeys_keywords                 = json_decode($fsf_port_getCloudKeywordsLogarithmic_content,true);
  //echo "<br><pre>";print_r($fsfcms_getCloudKeys_keywords);echo"</pre>";
  if(array_pop($fsfcms_getCloudKeys_keywords) == 200)
    {
    $fsf_port_getCloudKeywordsLogarithmic_output .= "<ol>";

    foreach($fsfcms_getCloudKeys_keywords as $fsfcms_getCloudKeys_keyword => $fsfcms_getCloudKeys_keyword_attributes)
      { 
      $fsfcms_getCloudKeys_keyword            = $fsfcms_getCloudKeys_keyword;
      $fsfcms_getCloudKeys_keyword_slug       = $fsfcms_getCloudKeys_keyword_attributes['keywordSlug'];
      $fsfcms_getCloudKeys_keyword_count      = $fsfcms_getCloudKeys_keyword_attributes['keywordCount'];
      $fsfcms_getCloudKeys_keyword_class_name = $fsfcms_getCloudKeys_keyword_attributes['keywordClassName'];
      $fsf_port_getCloudKeywordsLogarithmic_output .= "<li><span class=\"" . $fsfcms_getCloudKeys_keyword_class_name . 
                                                    "\"><a href=\"/keywords/" . $fsfcms_getCloudKeys_keyword_slug . "\" title=\"" . $fsfcms_getCloudKeys_keyword_count . "\">" .
                                                    $fsfcms_getCloudKeys_keyword . "</a></span></li> ";
      }
    $fsf_port_getCloudKeywordsLogarithmic_output .= "</ol>";
    } else  {
    $fsf_port_getCloudKeywordsLogarithmic_output = $fsfcms_getCloudKeys_keywords['errorMessage'];
    }
    
  return $fsf_port_getCloudKeywordsLogarithmic_output; 
  }



//
//
//  Access the API and return the short link for a specified image
//  TODO: Pagination
//

function fsf_port_getImageShortLink($fsfcms_image_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getImageShortLink.php";
  $fsf_api_options  = "?image_id=" . $fsfcms_image_id; 
  $fsf_port_getImageShortLink_json        = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageShortLink_array       = json_decode($fsf_port_getImageShortLink_json,true);
  if($fsf_port_getImageShortLink_array['0'] == "FSFGISL-None-Found")
    {
    $fsf_port_getImageShortLink_output  = "A short link is not available for this image.";
    } else  {
    $fsf_port_getImageShortLink_url         = $fsf_port_getImageShortLink_array['imageShortLinkURL'];
    $fsf_port_getImageShortLink_image_title = $fsf_port_getImageShortLink_array['imageTitle'];
    $fsf_port_getImageShortLink_output      = $fsf_port_getImageShortLink_url;
    }  
  return $fsf_port_getImageShortLink_output; 
  }

//
//
//  Access the API and return information about a camera
//
//
//

function fsf_port_getCameraInfoBySlug($fsfcms_getCameraInfoBySlug_camera_slug)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getCameraInfoBySlug.php";
  $fsf_api_options  = "?cameraSlug=" . $fsfcms_getCameraInfoBySlug_camera_slug; 
  $fsf_port_getCameraInfoBySlug_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getCameraInfoBySlug_content; 
  }

//
//
//  Access the API and return announcemnts
//
//
//

function fsf_cms_getAnnouncements($fsfcms_announcement_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.cms.getAnnouncements.php";
  if($fsfcms_announcement_id !="")
    {
    $fsf_api_options  = "?announcement_id=" . $fsfcms_announcement_id;
    } else  {
    $fsf_api_options  = "";
    } 
  $fsf_cms_getAnnouncements_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_cms_getAnnouncements_content; 
  }

//
//
//  Return a list of publications
//
//

function fsf_cms_getPublications($fsfcms_pub_identifier)
  {
  global $fsfcms_api_url;
  if($fsfcms_pub_identifier == "all")
    {
    $fsf_api_options  = "";
    } elseif(is_numeric($fsfcms_pub_identifier))  {
    $fsf_api_options  = "?publicationId=" . $fsfcms_pub_identifier;    
    } else  {
    //  TODO  SEC Filter Input to Avoid SQL Injection, ETC also check to see if it is a valid slug
    $fsf_api_options  = "?publicationSlug=" . $fsfcms_pub_identifier;
    }
  $fsf_api_file     = "fsf.cms.getPublications.php"; 
  $fsf_port_getPublications_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  
  return $fsf_port_getPublications_content; 
  }



//
//
//  Site Functions
//
//

// This is another candidate for inclusion in the common functions file.
function fsfcms_getSiteImageFilePath()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getSiteImageFilePath.php";
  $fsf_api_options  = ""; 
  $fsf_port_getSiteImageFilePath_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getSiteImageFilePath_array  = json_decode($fsf_port_getSiteImageFilePath_json);
  $fsfcms_getSiteImageFilePath_output   = $fsf_port_getSiteImageFilePath_array[0];
  return $fsfcms_getSiteImageFilePath_output; 
  }

function fsfcms_getSiteMinimumURL()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getSiteMinimumURL.php";
  $fsf_api_options  = ""; 
  $fsf_port_getSiteMinimumURL_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getSiteMinimumURL_array  = json_decode($fsf_port_getSiteMinimumURL_json);
  $fsfcms_getSiteMinimumURL_output   = $fsf_port_getSiteMinimumURL_array[0];
  return $fsfcms_getSiteMinimumURL_output; 
  }

function fsfcms_getFeedNumberOfItems()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getFeedNumberOfItems.php";
  $fsf_api_options  = ""; 
  $fsf_port_getFeedNumberOfItems_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getFeedNumberOfItems_array  = json_decode($fsf_port_getFeedNumberOfItems_json);
  $fsfcms_getFeedNumberOfItems_output   = $fsf_port_getFeedNumberOfItems_array[0];
  return $fsfcms_getFeedNumberOfItems_output; 
  }

//  Get the user info
function fsf_cms_getUserInfo($fsfcms_user_id)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.getUserInfo.php";
  if(ctype_digit($fsfcms_user_id))
    {
    $fsf_api_options  = "?userId=" . $fsfcms_user_id;
    } else  {
    $fsf_api_options  = "?userSlug=" . $fsfcms_user_id;
    }
  $fsfcms_cms_get_user_info_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsfcms_cms_get_user_info_json; 
  }

function fsf_port_getImageAuthors()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getImageAuthors.php";
  $fsf_api_options  = ""; 
  $fsf_port_getImageAuthors_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_getImageAuthors_array  = json_decode($fsf_port_getImageAuthors_json,true);

  return $fsf_port_getImageAuthors_array;   
  }
//
//
//  Useful Functions
//
//


//
//
//
//
//  CMS FUNCTIONS
//
//
//

function fsf_cms_getHeaderContent($fsfcms_page)
  {
  $fsf_cms_getHeaderContent_output = "";

  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.getHeaderContent.php";
  $fsf_api_options  = "?page=" . $fsfcms_page; 
  $fsf_cms_getHeaderContent_content = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_cms_getHeaderContent = json_decode($fsf_cms_getHeaderContent_content,true);
  $fsf_cms_getHeaderContent_output = $fsf_cms_getHeaderContent['all']['content'];
  
  return $fsf_cms_getHeaderContent_output;     
  }
?>