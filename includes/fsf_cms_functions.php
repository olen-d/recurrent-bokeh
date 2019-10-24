<?php  

function fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options)
  {
  $fsf_port_cookies = "";
  // Get the cookies and dump them into a string that CURL can understand 
  foreach($_COOKIE as $cookie_name => $cookie_value)
    {
    $fsf_port_cookies .= $cookie_name . "=" . $cookie_value . ";"; 
    }

  $fsf_preacher_curl_connect_timeout  = 9000;     // milliseconds
  $fsf_preacher_curl_request_timeout  = 9000;     // milliseconds
  session_write_close();                          // necessary so that CURL can access the cookies.
  $fsf_preacher_curl_handle = curl_init();
  
  curl_setopt($fsf_preacher_curl_handle,CURLOPT_COOKIE,$fsf_port_cookies);  
  curl_setopt($fsf_preacher_curl_handle,CURLOPT_URL,$fsfcms_api_url . $fsf_api_file . $fsf_api_options);
  curl_setopt($fsf_preacher_curl_handle,CURLOPT_RETURNTRANSFER,TRUE);
  curl_setopt($fsf_preacher_curl_handle,CURLOPT_CONNECTTIMEOUT_MS,$fsf_preacher_curl_connect_timeout);
  curl_setopt($fsf_preacher_curl_handle,CURLOPT_TIMEOUT_MS,$fsf_preacher_curl_request_timeout);
  $fsf_preacher_curl_content = curl_exec($fsf_preacher_curl_handle);
  curl_close($fsf_preacher_curl_handle);

  return $fsf_preacher_curl_content;  
  }
  
//  FUNCTIONS THAT USE THE API
function fsfcms_categoriesDropdown($path)
  {
  $categories_json         = fsf_port_getCategoriesList();
  $categories              = json_decode($categories_json,TRUE);

  if(array_pop($categories) ==  200)
    {
    $categories_select .=  "<select name=\"categories\" onchange=\"self.location.href=this.options[this.selectedIndex].value;\">";  // TODO: Pull this out and make into and event handler
    $categories_select .=  "<option value=\"\">SELECT A CATEGORY...</option>";

    foreach($categories as $category)
      {
      $categories_select .=  "<option value=\"" . $path . "/" . $category['categoryId'] . "\">" . strtoupper($category['categoryName']) . "</option>";     
      }
    $categories_select   .=  "</select>";
    }
    
  return $categories_select;    
  }

function fsfcms_getTotalImagesNumber()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getTotalImages.php";
  $fsf_api_options  = "";

  $fsf_port_total_images_json = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_total_images      = json_decode($fsf_port_total_images_json,true);
  $fsf_port_total_images_number = $fsf_port_total_images['totalImages'];     
  return $fsf_port_total_images_number;  
  }

function fsfcms_getTotalImagesNumberByAuthor($fsfcms_author_id)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getTotalImagesByAuthor.php";
  $fsf_api_options  = "?authorID=" . $fsfcms_author_id;
                                           
  $fsf_port_total_images_by_author_json = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_total_images_by_author      = json_decode($fsf_port_total_images_by_author_json,true);
  $fsf_port_total_images_by_author_number = $fsf_port_total_images_by_author['totalImagesByAuthor'];     
  return $fsf_port_total_images_by_author_number;  
  }

function fsfcms_getTotalImagesNumberByCamera($fsfcms_camera_slug)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getTotalImagesByCamera.php";
  $fsf_api_options  = "?cameraSlug=" . $fsfcms_camera_slug;
                                           
  $fsf_port_total_images_by_camera_json = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_total_images_by_camera      = json_decode($fsf_port_total_images_by_camera_json,true);
  $fsf_port_total_images_by_camera_number = $fsf_port_total_images_by_camera['totalImagesByCamera'];     
  return $fsf_port_total_images_by_camera_number;  
  }

function fsfcms_getTotalImagesNumberByCategory($fsfcms_category_slug)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getTotalImagesByCategory.php";

  if(is_numeric($fsfcms_category_slug))
    {
    $fsf_api_options  = "?categoryId=" . $fsfcms_category_slug;
    } else  {
    $fsf_api_options  = "?categorySlug=" . $fsfcms_category_slug;
    }
                                           
  $fsf_port_total_images_by_category_json = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_total_images_by_category      = json_decode($fsf_port_total_images_by_category_json,true);
  $fsf_port_total_images_by_category_number = $fsf_port_total_images_by_category['totalImages'];     
  return $fsf_port_total_images_by_category_number;  
  }

function fsfcms_getTotalImagesNumberByKeyword($fsfcms_keyword_slug)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getTotalImagesByKeyword.php";
  $fsf_api_options  = "?keywordSlug=" . $fsfcms_keyword_slug;
                                           
  $fsf_port_total_images_by_keyword_json = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_total_images_by_keyword      = json_decode($fsf_port_total_images_by_keyword_json,true);
  $fsf_port_total_images_by_keyword_number = $fsf_port_total_images_by_keyword['totalImagesByKeyword'];  
  return $fsf_port_total_images_by_keyword_number;  
  }

function fsfcms_getTotalImagesNumberByMedia($fsfcms_media_slug)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getTotalImagesByMedia.php";
  $fsf_api_options  = "?mediaSlug=" . $fsfcms_media_slug;
                                           
  $fsf_port_total_images_by_media_json = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_total_images_by_media      = json_decode($fsf_port_total_images_by_media_json,true);
  $fsf_port_total_images_by_media_number = $fsf_port_total_images_by_media['totalImagesByMedia'];     
  return $fsf_port_total_images_by_media_number;  
  }

//
//
//  Keywords page functions
//
//

function fsfcms_port_getImageThumbnailsByKeyword($fsfcms_keyword_slug,$fsfcms_page,$fsfcms_items)
  {
  $fsf_port_getKeywordImageThumbnails_content = "";

  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.port.getImageThumbnailsByKeyword.php";
  $fsf_api_options  = "?keywordSlug=" . urlencode($fsfcms_keyword_slug); 

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
  $fsf_port_getKeywordImageThumbnails_content     = fsf_port_keyword_thumbnail_links($fsf_port_getKeywordImageThumbnails_thumbnails,$fsfcms_keyword_slug);
  return $fsf_port_getKeywordImageThumbnails_content;  
  }

  
function fsfcms_getSiteTitle()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.cms.getSiteTitle.php";
  $fsf_api_options  = "";

  $fsfcms_getSiteTitle_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getSiteTitle_array  = json_decode($fsfcms_getSiteTitle_json, true);
  $fsfcms_getSiteTitle_output    = $fsfcms_getSiteTitle_array['siteTitle'];
  	
  return $fsfcms_getSiteTitle_output; 
  }

function fsfcms_getSiteURL()
  {
  global $fsfcms_api_url;
  if(strlen($fsfcms_api_url)<1)
    {
    $fsfcms_api_url = FSFCMS_API_URL; //  TODO UPDATE EVERYTHING TO TAKE FSFCMS_API_URL AS A CONSTANT!
    }
  $fsf_api_file     = "fsf.cms.getSiteURL.php";
  $fsf_api_options  = "";

  $fsfcms_getSiteURL_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getSiteURL_array  = json_decode($fsfcms_getSiteURL_json, true);
  $fsfcms_getSiteURL_output    = $fsfcms_getSiteURL_array['0'];	
  
  return $fsfcms_getSiteURL_output; 
  }

function fsfcms_getSiteGlobalPaged()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.cms.getSiteGlobalPaged.php";
  $fsf_api_options  = "";

  $fsfcms_getSiteArchivesPaged_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getSiteArchivesPaged_array  = json_decode($fsfcms_getSiteArchivesPaged_json, true);
  $fsfcms_getSiteArchivesPaged_output    = $fsfcms_getSiteArchivesPaged_array['0'];
  	
  return $fsfcms_getSiteArchivesPaged_output; 
  }

function fsfcms_getSiteGlobalItemsPerPage()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.cms.getSiteGlobalItemsPerPage.php";
  $fsf_api_options  = "";

  $fsfcms_getSiteArchivesItemsPerPage_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getSiteArchivesItemsPerPage_array  = json_decode($fsfcms_getSiteArchivesItemsPerPage_json, true);
  $fsfcms_getSiteArchivesItemsPerPage_output    = $fsfcms_getSiteArchivesItemsPerPage_array['0'];
  	
  return $fsfcms_getSiteArchivesItemsPerPage_output; 
  }

function fsfcms_getAuthorInfoByID($author_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.port.getAuthorInfoByID.php";
  $fsf_api_options  = "?authorID=" . $author_id;
               
  $fsfcms_getAuthorInfoByID_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
//print_r($fsfcms_getAuthorInfoByID_json); exit;

  $fsfcms_getAuthorInfoByID_array  = json_decode($fsfcms_getAuthorInfoByID_json, true);
 	//print_r($fsfcms_getAuthorInfoByID_array); exit;
  return $fsfcms_getAuthorInfoByID_array;   
  }

function fsfcms_getSiteContact()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.getSiteContact.php";
  $fsf_api_options  = "";
               
  $fsfcms_getSiteContact_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getSiteContact_array  = json_decode($fsfcms_getSiteContact_json, true);

  return $fsfcms_getSiteContact_array;   
  }
   
// FUNCTIONS THAT DIRECTLY ACCESS THE DB, INTERNAL TO THE CMS
// REM Consider moving these to API functions in the future, especially the ones commented "API Function", with the possible exception of the template functions
// REM API Function
function fsfcms_getSiteURLshortenerURL()
  {
  global $fsfcms_config_table;
  
  $fsfcms_getSiteURLshortenerURL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'siteURLshortenerURL' LIMIT 1";
   // echo $fsfcms_getSiteTitle_query;  
  $fsfcms_getSiteURLshortenerURL_result = mysql_query($fsfcms_getSiteURLshortenerURL_query);
  $fsfcms_getSiteURLshortenerURL_row    = mysql_fetch_row($fsfcms_getSiteURLshortenerURL_result);
  $fsfcms_getSiteURLshortenerURL_output = $fsfcms_getSiteURLshortenerURL_row[0];

  return $fsfcms_getSiteURLshortenerURL_output; 
  }

function fsfcms_getPortImagePath()
  {
  global $fsfcms_config_table;
  
  $fsfcms_getPortImagePath_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portImagePath' LIMIT 1";
   // echo $fsfcms_getSiteTitle_query;  
  $fsfcms_getPortImagePath_result = mysql_query($fsfcms_getPortImagePath_query);
  $fsfcms_getPortImagePath_row    = mysql_fetch_row($fsfcms_getPortImagePath_result);
  $fsfcms_getPortImagePath_output = $fsfcms_getPortImagePath_row[0];

  return $fsfcms_getPortImagePath_output; 
  }
//REM API Function
function fsfcms_getPortThumbsURL()
  {
  global $fsfcms_config_table;
  
  $fsfcms_getPortThumbsURL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsURL' LIMIT 1";
   // echo $fsfcms_getSiteTitle_query;  
  $fsfcms_getPortThumbsURL_result = mysql_query($fsfcms_getPortThumbsURL_query);
  $fsfcms_getPortThumbsURL_row    = mysql_fetch_row($fsfcms_getPortThumbsURL_result);
  $fsfcms_getPortThumbsURL_output = $fsfcms_getPortThumbsURL_row[0];

  return $fsfcms_getPortThumbsURL_output; 
  }

function fsfcms_getPortThumbsPath()
  {
  global $fsfcms_config_table;
  
  $fsfcms_getPortThumbsPath_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portThumbsPath' LIMIT 1";
   // echo $fsfcms_getSiteTitle_query;  
  $fsfcms_getPortThumbsPath_result = mysql_query($fsfcms_getPortThumbsPath_query);
  $fsfcms_getPortThumbsPath_row    = mysql_fetch_row($fsfcms_getPortThumbsPath_result);
  $fsfcms_getPortThumbsPath_output = $fsfcms_getPortThumbsPath_row[0];

  return $fsfcms_getPortThumbsPath_output; 
  }

function fsfcms_getSiteCopyright()
  {
  global $fsfcms_config_table;
  
  $fsfcms_getSiteCopyright_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'siteCopyright' LIMIT 1";
   // echo $fsfcms_getSiteTitle_query;  
  $fsfcms_getSiteCopyright_result = mysql_query($fsfcms_getSiteCopyright_query);
  $fsfcms_getSiteCopyright_row    = mysql_fetch_row($fsfcms_getSiteCopyright_result);
  $fsfcms_getSiteCopyright_output = "Copyright " . date("Y",time()) . " " . $fsfcms_getSiteCopyright_row[0];

  return $fsfcms_getSiteCopyright_output; 
  }
// REM API Function
function fsfcms_getSiteBrief()
  {
  global $fsfcms_config_table;
  
  $fsfcms_getSiteBrief_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'siteBrief' LIMIT 1";
   // echo $fsfcms_getSiteTitle_query;  
  $fsfcms_getSiteBrief_result = mysql_query($fsfcms_getSiteBrief_query);
  $fsfcms_getSiteBrief_row    = mysql_fetch_row($fsfcms_getSiteBrief_result);
  $fsfcms_getSiteBrief_output = $fsfcms_getSiteBrief_row[0];

  return $fsfcms_getSiteBrief_output; 
  }

function fsfcms_getServerTimeZone()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     = "fsf.cms.getServerTimeZone.php";
  $fsf_api_options  = "";

  $fsfcms_getServerTimeZone_json    = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getServerTimeZone_array   = json_decode($fsfcms_getServerTimeZone_json, true);
  $fsfcms_getServerTimeZone_output  = $fsfcms_getServerTimeZone_array;
  	
  return $fsfcms_getServerTimeZone_output;  
  }
// TEMPLATE FUNCTIONS, internal to the CMS
function fsfcms_getNavigation()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     =   "fsf.cms.getNavigation.php";
  $fsf_api_options  =   "?sortColumn=navigationAlias";
  $fsf_api_options  .=  "&sortOrder=ASC";

  $fsfcms_getNavigation_pages_json  = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getNavigation_pages       = json_decode($fsfcms_getNavigation_pages_json,true);
  foreach($fsfcms_getNavigation_pages as $fsfcms_getNavigation_page)
    {
    $fsfcms_getNavigation_output  .=  "<li><a href=\"/"  . $fsfcms_getNavigation_page['pageSlug'] . "\">" . $fsfcms_getNavigation_page['navigationAlias'] . "</a></li>";   
    }
  return $fsfcms_getNavigation_output;  
  }
  
function fsfcms_getCurrentTemplate()
  {
  global $fsfcms_config_table;
  $fsfcms_get_current_template_output = array();
  
  $fsfcms_GCT_get_templates_path_query  = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'templatesPath' LIMIT 1";
  $fsfcms_GCT_get_templates_path_result = mysql_query($fsfcms_GCT_get_templates_path_query);
  $fsfcms_GCT_get_templates_path_row    = mysql_fetch_row($fsfcms_GCT_get_templates_path_result);
  $fsfcms_GCT_get_templates_path_output = $fsfcms_GCT_get_templates_path_row[0];

  $fsfcms_GCT_get_templates_current_query  = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'currentTemplate' LIMIT 1";
  $fsfcms_GCT_get_templates_current_result = mysql_query($fsfcms_GCT_get_templates_current_query);
  $fsfcms_GCT_get_templates_current_row    = mysql_fetch_row($fsfcms_GCT_get_templates_current_result);
  $fsfcms_GCT_get_templates_current_output = $fsfcms_GCT_get_templates_current_row[0];

  $fsfcms_get_current_template_output['templatesPath']    = $fsfcms_GCT_get_templates_path_output; 
  $fsfcms_get_current_template_output['currentTemplate']  = $fsfcms_GCT_get_templates_current_output;

  return $fsfcms_get_current_template_output; 
  }

function fsfcms_getTemplateIDbySlug($template_slug)
  {
  global $fsfcms_templates_table;
  
  $fsfcms_get_template_id_query = "SELECT id FROM " . $fsfcms_templates_table . " WHERE template_slug = '" . $template_slug . "'";
  $fsfcms_get_template_id_result = mysql_query($fsfcms_get_template_id_query);
  $fsfcms_get_template_id_row    = mysql_fetch_row($fsfcms_get_template_id_result);
  $fsfcms_get_template_id_output = $fsfcms_get_template_id_row[0];
  
  return $fsfcms_get_template_id_output;
  }
  
function fsfcms_getTemplatePageFilenames($template_id)
  {
  global $fsfcms_pages_table;
  $fsfcms_get_template_page_filenames_output  = array();
  $fsfcms_get_template_page_filenames_query   = "SELECT page_slug, page_filename FROM " . $fsfcms_pages_table . " WHERE template_id = " . $template_id;
  $fsfcms_get_template_page_filenames_result  = mysql_query($fsfcms_get_template_page_filenames_query);
  while($fsfcms_row = mysql_fetch_assoc($fsfcms_get_template_page_filenames_result))
    {
    $fsfcms_get_template_page_filenames_output[$fsfcms_row['page_slug']] = $fsfcms_row['page_filename'];
    }
  
  return $fsfcms_get_template_page_filenames_output;
  }

function fsfcms_getTemplateHomePage($template_id)
  {
  global $fsfcms_api_url;
  
  $fsf_api_file     =   "fsf.cms.getTemplateHomePage.php";
  $fsf_api_options  =   "?templateId="  . $template_id;

  $fsfcms_getTemplateHomePage_json  = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_getTemplateHomePage       = json_decode($fsfcms_getTemplateHomePage_json,true);

  return $fsfcms_getTemplateHomePage;  
  }

function fsfcms_getAnnouncements_mostRecent()
  {
  global  $fsfcms_current_image_width;
  
  $fsfcms_announcement_json   = fsf_cms_getAnnouncements("");
  $fsfcms_announcement        = json_decode($fsfcms_announcement_json,true);

  $fsfcms_announcement_status  = array_pop($fsfcms_announcement);
  if ($fsfcms_announcement_status == 200)
    {
    $fsfcms_server_timezone     = fsfcms_getServerTimeZone();
    $fsfcms_server_timezone_off = $fsfcms_server_timezone['serverTimeZoneOffset'];

    $fsfcms_announcement_date_formatted = date("l, F jS, Y \a\\t g:i a",$fsfcms_announcement[0]['postedDate'] + $fsfcms_server_timezone_off * 3600);  
    $fsfcms_announcement_hidden_item_id = "ah0";
    
    $fsfcms_announcement_output .= "<div id=\"announcements\" style=\"width:" . $fsfcms_current_image_width . "px;\">";
    $fsfcms_announcement_output .= "<div class=\"announcement-headline\"><h2><span class=\"announcement-expand\">" . $fsfcms_announcement[0]['headline'] . "...</h2></span></div>";
    $fsfcms_announcement_output .= "<div class=\"announcement-body\" id=\"" . $fsfcms_announcement_hidden_item_id . "\">";
    $fsfcms_announcement_output .= "<div class=\"announcement-author\">" . $fsfcms_announcement[0]['authorFirstName'] . "&nbsp;" . $fsfcms_announcement[0]['authorLastName'] . "</div>";
    $fsfcms_announcement_output .= "<div class=\"announcement-article\">" . $fsfcms_announcement[0]['article'] . "</div>";
    $fsfcms_announcement_output .= "<div class=\"announcement-date\">" . $fsfcms_announcement_date_formatted . "</div>";
    $fsfcms_announcement_output .= "<div class=\"announcement-toggle-article\"><span class=\"announcement-collapse\">Collapse</span></div>"; 
    $fsfcms_announcement_output .= "</div>";
    $fsfcms_announcement_output .= "</div>";
    } else  {
    $fsfcms_announcement_output = "";     
    }
  return $fsfcms_announcement_output;
  }
//
//
//  General Functions
//
//

//  Convert UTC to the server timezone
//  TODO - maybe make the timezone stuff a seperate function or constant
function fsfcms_server_timezone($fsfcms_unix_timestamp)
  {
  global $fsfcms_api_url;
  $fsfcms_get_server_tz = json_decode(fsf_preacher_curl($fsfcms_api_url, "fsf.cms.getServerTimeZone.php", ""),true);
  $fsfcms_server_tz     = $fsfcms_get_server_tz['serverTimeZoneName'];

  $fsfcms_server_dt = new DateTime();
  $fsfcms_server_dt -> setTimezone(new DateTimeZone($fsfcms_server_tz));
  $fsfcms_server_dt -> setTimestamp($fsfcms_unix_timestamp); 
  
  return $fsfcms_server_dt; 
  }

//  Return the relative image posting time
function fsfcms_relative_time($fsfcms_image_unix_timestamp)
  {
  global $fsfcms_api_url;
  
  $fsfcms_relative_time_output  = "";

  $fsfcms_date_diff_seconds       = time() - $fsfcms_image_unix_timestamp;  
  $fsfcms_date_interval_spec      = "PT" . $fsfcms_date_diff_seconds . "S";
 
  $fsfcms_get_server_tz = json_decode(fsf_preacher_curl($fsfcms_api_url, "fsf.cms.getServerTimeZone.php", ""),true);
  $fsfcms_server_tz     = $fsfcms_get_server_tz['serverTimeZoneName'];

  $fsfcms_current_dt    = new DateTime();
  $fsfcms_current_dt -> setTimezone(new DateTimeZone($fsfcms_server_tz));
  $fsfcms_image_post_dt = new DateTime();
  $fsfcms_image_post_dt -> setTimezone(new DateTimeZone($fsfcms_server_tz));
  $fsfcms_image_post_dt -> setTimestamp($fsfcms_image_unix_timestamp);
  $fsfcms_date_interval = $fsfcms_image_post_dt->diff($fsfcms_current_dt);

  $fsfcms_relative_time_plural  = "";
  if($fsfcms_date_interval->invert == 0)
    {
    $fsfcms_relative_time_tense = "ago";
    } elseif  ($fsfcms_date_interval->invert == 1)  {
    $fsfcms_relative_time_tense = "in the future";
    }
  if($fsfcms_date_interval->y > 0)
    {
    if($fsfcms_date_interval->y > 1)
      {
      $fsfcms_relative_time_plural  = "s";
      }
    $fsfcms_relative_time_output  = $fsfcms_date_interval->y . " year" . $fsfcms_relative_time_plural . " " . $fsfcms_relative_time_tense;
    } elseif($fsfcms_date_interval->m > 0)  {
    if($fsfcms_date_interval->m > 1)
      {
      $fsfcms_relative_time_plural  = "s";
      }
    $fsfcms_relative_time_output  = $fsfcms_date_interval->m . " month" . $fsfcms_relative_time_plural . " " . $fsfcms_relative_time_tense;    
    } elseif($fsfcms_date_interval->d > 0)  {
    if($fsfcms_date_interval->d > 1)
      {
      $fsfcms_relative_time_plural  = "s";
      }
    $fsfcms_relative_time_output  = $fsfcms_date_interval->d . " day" . $fsfcms_relative_time_plural . " " . $fsfcms_relative_time_tense;    
    } elseif($fsfcms_date_interval->h > 0)  {
    if($fsfcms_date_interval->h > 1)
      {
      $fsfcms_relative_time_plural  = "s";
      }
    $fsfcms_relative_time_output  = $fsfcms_date_interval->h . " hour" . $fsfcms_relative_time_plural . " " . $fsfcms_relative_time_tense;    
    } elseif($fsfcms_date_interval->i > 0)  {
    if($fsfcms_date_interval->i > 1)
      {
      $fsfcms_relative_time_plural  = "s";
      }
    $fsfcms_relative_time_output  = $fsfcms_date_interval->i . " minute" . $fsfcms_relative_time_plural . " " . $fsfcms_relative_time_tense;    
    } else  {
    $fsfcms_relative_time_output  = "Just now";
    }     
  return $fsfcms_relative_time_output;
  }

//  Truncate text to an arbitrary number of characters, breaking on a word boundary
function fsfcms_truncate_text($fsfcms_original_text,$fsfcms_character_limit,$fsfcms_text_append)
  {
  $fsfcms_truncate_text_output  = "";
  if(strlen($fsfcms_original_text) > $fsfcms_character_limit)
    {
    $fsfcms_truncate_text_output  =   substr($fsfcms_original_text,0,strrpos(substr($fsfcms_original_text,0,$fsfcms_character_limit)," "));
    $fsfcms_truncate_text_output  =   preg_replace("/\pP\z/","",trim($fsfcms_truncate_text_output)) . $fsfcms_text_append;
    } else  {
    $fsfcms_truncate_text_output  = $fsfcms_original_text;    
    }
  return $fsfcms_truncate_text_output;
  }

//  Make a comma seperated list of the authors associated with an image
function fsf_port_format_authors($fsf_port_authors)
  {
  $fsf_port_format_authors_output = "";
  foreach($fsf_port_authors as $fsf_port_author)
    {
    $fsf_port_format_authors_output .= $fsf_port_author['authorFirstName'] . " " . $fsf_port_author['authorLastName']. ", ";
    }
    $fsf_port_format_authors_output = rtrim($fsf_port_format_authors_output,", ");
  return $fsf_port_format_authors_output;
  }

//  Make a comma seperated of the category names associated with an image
function fsf_port_format_categories($fsf_port_categories)
  {
  $fsf_port_format_categories_output = "";
  foreach($fsf_port_categories as $fsf_port_category)
    {
    $fsf_port_format_categories_output .= "<a href=\"/categories/" . $fsf_port_category['categorySlug'] . "\">" . $fsf_port_category['categoryName'] . "</a>, ";
    }
    $fsf_port_format_categories_output = rtrim($fsf_port_format_categories_output,", ");
  return $fsf_port_format_categories_output;
  }

//  Make a comma seperated of the keywords associated with an image
function fsf_port_format_keywords($fsf_port_keywords)
  {
  $fsf_port_format_keywords_output = "";
  foreach($fsf_port_keywords as $fsf_port_keyword)  
    {
    $fsf_port_format_keywords_output .= "<a href=\"/keywords/" . $fsf_port_keyword['keywordSlug'] . "\">" . $fsf_port_keyword['keyword'] . "</a>, ";
    }
    $fsf_port_format_keywords_output = rtrim($fsf_port_format_keywords_output,", ");
  return $fsf_port_format_keywords_output;
  }


/*
//  Depracated. Replaced by the magic drop down.
function fsf_port_thumbnail_guide_dates($fsf_port_thumbnails)
  {
  // <\sp\a\\n \c\l\a\s\s\=\"\s\up\e\\r\">S</\sp\a\\n>
  $fsf_port_guide_dates = array();
  
  $last_thumbnail     = end($fsf_port_thumbnails);
  $first_thumbnail    = reset($fsf_port_thumbnails);

  $last_thumbnail_dt  = fsfcms_server_timezone($last_thumbnail['postedDateUnixTimestamp']);
  $fsf_port_guide_dates['last_thumbnail_guide_date']  = $last_thumbnail_dt->format("F j, Y");

  $first_thumbnail_dt = fsfcms_server_timezone($first_thumbnail['postedDateUnixTimestamp']);
  $fsf_port_guide_dates['first_thumbnail_guide_date'] = $first_thumbnail_dt->format("F j, Y");
  
  return $fsf_port_guide_dates;                                                                                    
  }
*/
  
//  Generate the thumbnail links for the archive pages
function fsf_port_archive_thumbnail_links($fsf_port_thumbnails)
  { 
  $fsf_port_thumbnail_links_output  = "";
  $fsf_port_thumbnail_status        = array_pop($fsf_port_thumbnails);

  //  $fsf_port_thumbnail_guide_dates   = fsf_port_thumbnail_guide_dates($fsf_port_thumbnails); DEPRACATED, SUPERCEDED BY THE DROP DOWN
  //  $fsf_port_thumbnail_links_output  .=  "<div id=\"guide-dates\"><div id=\"last-guide-date\">" . $fsf_port_thumbnail_guide_dates['last_thumbnail_guide_date']. "</div><div id=\"first-guide-date\">" . $fsf_port_thumbnail_guide_dates['first_thumbnail_guide_date']. "</div></div>";
  
  if($fsf_port_thumbnail_status['status'] == 200)
    
    {
    foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
      {    
      $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
      $fsf_port_thumbnail_image_caption         = fsfcms_truncate_text($fsf_port_thumbnail['caption'],68,"...");
      $fsf_port_thumbnail_image_authors         = fsf_port_format_authors($fsf_port_thumbnail['authors']);
      $fsf_port_thumbnail_image_posted_date     = fsfcms_relative_time($fsf_port_thumbnail['postedDateUnixTimestamp']);
      $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
      $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
      $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
      $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\">";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-author\">" . $fsf_port_thumbnail_image_authors . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-title\"><h3><a href=\"/" . $fsf_port_thumbnail_image_link_clean_URL . "\">" . fsfcms_truncate_text($fsf_port_thumbnail_image_title,28,"...") . "</a></h3></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-caption\">" . $fsf_port_thumbnail_image_caption . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-posted-date\">" .  $fsf_port_thumbnail_image_posted_date . "</div>";
      $fsf_port_thumbnail_links_output          .=  "</div>";
      }
    } else  {
    $fsf_port_thumbnail_links_output = "<p>" . $fsf_port_thumbnail['status'] . "</p><p>" . $fsf_port_thumbnail['errorMessage'] . "</p>";
    }
  return $fsf_port_thumbnail_links_output;  
  }

//  Generate the thumbnail links for the authors pages
function fsf_port_author_thumbnail_links($fsf_port_thumbnails,$fsf_author_slug)
  { 
  $fsf_port_thumbnail_links_output  = "";
  $fsf_port_thumbnail_status        = array_pop($fsf_port_thumbnails);

  if($fsf_port_thumbnail_status['status'] == 200)
    {
    foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
      {
      $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
      $fsf_port_thumbnail_image_caption         = fsfcms_truncate_text($fsf_port_thumbnail['caption'],68,"...");
      $fsf_port_thumbnail_image_authors         = fsf_port_format_authors($fsf_port_thumbnail['authors']);
      $fsf_port_thumbnail_image_posted_date     = fsfcms_relative_time($fsf_port_thumbnail['postedDateUnixTimestamp']);
      $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
      $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
      $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
      $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\">";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . "authors/" . $fsf_author_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-author\">" . $fsf_port_thumbnail_image_authors . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-title\"><h3><a href=\"/" . "authors/" . $fsf_author_slug . "/". $fsf_port_thumbnail_image_link_clean_URL . "\">" . fsfcms_truncate_text($fsf_port_thumbnail_image_title,28,"...") . "</a></h3></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-caption\">" . $fsf_port_thumbnail_image_caption . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-posted-date\">" .  $fsf_port_thumbnail_image_posted_date . "</div>";
      $fsf_port_thumbnail_links_output          .=  "</div>";
      }
    } else  {
    $fsf_port_thumbnail_links_output = "<p>" . $fsf_port_thumbnail['status'] . "</p><p>" . $fsf_port_thumbnail['errorMessage'] . "</p>";
    }
  return $fsf_port_thumbnail_links_output;  
  }

//  Generate the thumbnail links for the camera pages
function fsf_port_camera_thumbnail_links($fsf_port_thumbnails,$fsf_camera_slug)
  { 
  $fsf_port_thumbnail_links_output  = "";
  $fsf_port_thumbnail_status        = array_pop($fsf_port_thumbnails);

  if($fsf_port_thumbnail_status['status'] == 200)
    {
    foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
      {
      $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
      $fsf_port_thumbnail_image_caption         = fsfcms_truncate_text($fsf_port_thumbnail['caption'],68,"...");
      $fsf_port_thumbnail_image_authors         = fsf_port_format_authors($fsf_port_thumbnail['authors']);
      $fsf_port_thumbnail_image_posted_date     = fsfcms_relative_time($fsf_port_thumbnail['postedDateUnixTimestamp']);
      $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
      $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
      $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
      $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\">";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . "cameras/" . $fsf_camera_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-author\">" . $fsf_port_thumbnail_image_authors . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-title\"><h3><a href=\"/" . "cameras/" . $fsf_camera_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\">" . fsfcms_truncate_text($fsf_port_thumbnail_image_title,28,"...") . "</a></h3></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-caption\">" . $fsf_port_thumbnail_image_caption . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-posted-date\">" .  $fsf_port_thumbnail_image_posted_date . "</div>";
      $fsf_port_thumbnail_links_output          .=  "</div>";
      }
    } else  {
    $fsf_port_thumbnail_links_output = "<p>" . $fsf_port_thumbnail['status'] . "</p><p>" . $fsf_port_thumbnail['errorMessage'] . "</p>";
    }
  return $fsf_port_thumbnail_links_output;  
  }

//  Generate the thumbnail links for the category pages
function fsf_port_category_thumbnail_links($fsf_port_thumbnails,$fsfcms_category_slug)
  { 
  $fsf_port_thumbnail_links_output  = "";
  $fsf_port_thumbnail_status        = array_pop($fsf_port_thumbnails);

  if($fsf_port_thumbnail_status['status'] == 200)
    {
    $i    = 0;
    $hack = "";
    foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
      {
      $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
      $fsf_port_thumbnail_image_caption         = fsfcms_truncate_text($fsf_port_thumbnail['caption'],68,"...");
      $fsf_port_thumbnail_image_authors         = fsf_port_format_authors($fsf_port_thumbnail['authors']);
      $fsf_port_thumbnail_image_categories      = fsf_port_format_categories($fsf_port_thumbnail['categories']);
      $fsf_port_thumbnail_image_posted_date     = fsfcms_relative_time($fsf_port_thumbnail['postedDateUnixTimestamp']);
      $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
      $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
      $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
      $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\"" . $hack . ">";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . "categories/" . $fsfcms_category_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-author\">" . $fsf_port_thumbnail_image_authors . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-title\"><h3><a href=\"/" . "categories/" . $fsfcms_category_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\">" . fsfcms_truncate_text($fsf_port_thumbnail_image_title,28,"...") . "</a></h3></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-caption\">" . $fsf_port_thumbnail_image_caption . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-category\">" . $fsf_port_thumbnail_image_categories . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-posted-date\">" .  $fsf_port_thumbnail_image_posted_date . "</div>";
      $fsf_port_thumbnail_links_output          .=  "</div>";
      $i++;
      if($i == 3)
        {
        $hack = "style=\"clear:both;\"";
        $i    = 0;
        } else  {
        $hack = "";
        }
      }
    } else  {
    $fsf_port_thumbnail_links_output = "<p>" . $fsf_port_thumbnail['status'] . "</p><p>" . $fsf_port_thumbnail['errorMessage'] . "</p>";
    }
  return $fsf_port_thumbnail_links_output;  
  }

//  Generate the thumbnail links for the keyword pages
function fsf_port_keyword_thumbnail_links($fsf_port_thumbnails,$fsfcms_keyword_slug)
  {     
  $fsf_port_thumbnail_links_output  = "";
  $fsf_port_thumbnail_status        = array_pop($fsf_port_thumbnails);

  if($fsf_port_thumbnail_status == 200)
    {
    $i    = 0;
    $hack = "";
    foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
      {
      global $fsfcms_api_url;


      //  API Call to get the authors associated with the image                                           
      $fsf_api_authors_file           = "fsf.port.getImageAuthorsById.php";
      $fsf_api_authors_options        = "?imageId=" . $fsf_port_thumbnail['id'];
      $fsf_port_image_authors_json    = fsf_preacher_curl($fsfcms_api_url, $fsf_api_authors_file, $fsf_api_authors_options);
      $fsf_port_image_authors         = json_decode($fsf_port_image_authors_json,true);
      $fsf_port_image_authors_status  = array_pop($fsf_port_image_authors);     

      //  API Call to get the keywords associated with the image                                           
      $fsf_api_keywords_file          = "fsf.port.getImageKeywords.php";
      $fsf_api_keywords_options       = "?imageId=" . $fsf_port_thumbnail['id']; 
      $fsf_port_image_keywords_json   = fsf_preacher_curl($fsfcms_api_url, $fsf_api_keywords_file, $fsf_api_keywords_options);
      $fsf_port_image_keywords        = json_decode($fsf_port_image_keywords_json,true);
      $fsf_port_image_keywords_status = array_pop($fsf_port_image_keywords);

      //  Actually build the output      
      $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
      $fsf_port_thumbnail_image_caption         = fsfcms_truncate_text($fsf_port_thumbnail['caption'],68,"...");
      $fsf_port_thumbnail_image_authors         = fsf_port_format_authors($fsf_port_image_authors);
      $fsf_port_thumbnail_image_keywords        = fsf_port_format_keywords($fsf_port_image_keywords);
      $fsf_port_thumbnail_image_posted_date     = fsfcms_relative_time($fsf_port_thumbnail['postedDateUnixTimestamp']);
      $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
      $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
      $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
      $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\"" . $hack . ">";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . "keywords/" . $fsfcms_keyword_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-author\">" . $fsf_port_thumbnail_image_authors . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-title\"><h3><a href=\"/" . "keywords/" . $fsfcms_keyword_slug . "/" .  $fsf_port_thumbnail_image_link_clean_URL . "\">" . fsfcms_truncate_text($fsf_port_thumbnail_image_title,28,"...") . "</a></h3></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-caption\">" . $fsf_port_thumbnail_image_caption . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-keyword\">" . $fsf_port_thumbnail_image_keywords . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-posted-date\">" .  $fsf_port_thumbnail_image_posted_date . "</div>";
      $fsf_port_thumbnail_links_output          .=  "</div>";
      $i++;
      if($i == 3)
        {
        $hack = "style=\"clear:both;\"";
        $i    = 0;
        } else  {
        $hack = "";
        }
      }
    } else  {
    $fsf_port_thumbnail_links_output = "<p>" . $fsf_port_thumbnail['status'] . "</p><p>" . $fsf_port_thumbnail['errorMessage'] . "</p>";
    }
  return $fsf_port_thumbnail_links_output;  
  }

//  Generate the thumbnail links for the media pages
function fsf_port_media_thumbnail_links($fsf_port_thumbnails,$fsfcms_media_slug)
  { 
  $fsf_port_thumbnail_links_output  = "";
  $fsf_port_thumbnail_status        = array_pop($fsf_port_thumbnails);

  if($fsf_port_thumbnail_status['status'] == 200)
    {
    foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
      {
      $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
      $fsf_port_thumbnail_image_caption         = fsfcms_truncate_text($fsf_port_thumbnail['caption'],68,"...");
      $fsf_port_thumbnail_image_authors         = fsf_port_format_authors($fsf_port_thumbnail['authors']);
      $fsf_port_thumbnail_image_posted_date     = fsfcms_relative_time($fsf_port_thumbnail['postedDateUnixTimestamp']);
      $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
      $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
      $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
      $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\">";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . "media/" . $fsfcms_media_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-author\">" . $fsf_port_thumbnail_image_authors . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-title\"><h3><a href=\"/" . "media/" . $fsfcms_media_slug . "/" . $fsf_port_thumbnail_image_link_clean_URL . "\">" . fsfcms_truncate_text($fsf_port_thumbnail_image_title,28,"...") . "</a></h3></div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-caption\">" . $fsf_port_thumbnail_image_caption . "</div>";
      $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-posted-date\">" .  $fsf_port_thumbnail_image_posted_date . "</div>";
      $fsf_port_thumbnail_links_output          .=  "</div>";
      }
    } else  {
    $fsf_port_thumbnail_links_output = "<p>" . $fsf_port_thumbnail['status'] . "</p><p>" . $fsf_port_thumbnail['errorMessage'] . "</p>";
    }
  return $fsf_port_thumbnail_links_output;  
  }

//
//  Hexagon Cartogram Functions
//

//
//
//  Generate the U.S. Map Hexagon Grid
//
//

function fsf_cms_hex_grid_us()
  {
  global $fsfcms_api_url;

  
  $rows               = 9;
  $cols               = 12;
  $hex_num            = 0;

  $fsf_api_file       = "fsf.cms.getStatesByHexId.php";
  $fsf_api_options    = "";

  $states_json        = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $states             = json_decode($states_json,true); 

  $counts             = array();
  $bucket_thresholds  = array();
  
  $total_buckets      = 5;

  $status = array_pop($states);  
  foreach($states AS $hex_id => $attributes) 
    {
    $count  = $attributes['stateImageCount'];
    if(!is_null($count) && $count !=0)
      {
      $counts[$hex_id] = $count;
      } else  {  
      $states[$hex_id]['class'] = "stateCountClassNone";
      }
    }  
  arsort($counts);

  $max_count  = max($counts); 
  $min_count  = min($counts);

  $bucket_breaks  = array();
  $low_count      = $min_count;
  $high_count     = $max_count;
    
  //  Head/Tail Classification 
/*
  $new_counts     = array();
  $bucket         = 1;

  while (count($counts) > 0)
    {
    $mean_count = array_sum($counts) / count($counts);
    $ht_bucket_threshold  = $mean_count;
    $bucket_breaks[$bucket]['high'] = floor($ht_bucket_threshold); 
    $bucket_breaks[$bucket]['low']  = ceil($low_count);
    $low_count = $ht_bucket_threshold;

    foreach($counts as $hex_id => $count)
      {
      if($count <= $ht_bucket_threshold)
        {
        $states[$hex_id]['class'] = "stateCountClass" . $bucket;
        } else  {
        $new_counts[$hex_id] = $count;
        }
      }
      $bucket++;
      $counts = $new_counts;
      unset($new_counts);
      $new_counts = array();
    }
*/
  //  Logarithmic Classification    
 
  $log_max    = log($max_count);
  $log_range  = $log_max - log($min_count);
  $log_gap    = $log_range / $total_buckets;
  $log_bucket_threshold = $log_max - $log_gap;
  $bucket     = $total_buckets;
//  $bucket_minimums['bucket' . $bucket]  = ceil(exp($log_bucket_threshold)); 
  $bucket_breaks[$bucket]['high'] = floor($high_count); 
  $bucket_breaks[$bucket]['low']  = ceil(exp($log_bucket_threshold));

//    $low_count = $ht_bucket_threshold;

  foreach ($counts as $hex_id => $count)
              {                       
              if (log($count) < $log_bucket_threshold && $bucket > 1)     //  The > 1 condition prevents unexpected behavior with low counts (e.g. 1) 
                {			
                $bucket--;
                $bucket_breaks[$bucket]['high'] = floor(exp($log_bucket_threshold));
                $log_bucket_threshold = $log_bucket_threshold - $log_gap;
                $bucket_breaks[$bucket]['low']  = ceil(exp($log_bucket_threshold));
                }	
              $states[$hex_id]['class']  = "stateCountClass" . $bucket;
              next($counts);	// Theoretically, the foreach should increment this, I have no idea why it doesn't, hence the hack
              }
  $bucket_breaks[$bucket]['low'] = $low_count;  // Hack to set the last bucket to the correct low count, for some reason ceil gives strange results when the count is 1            

  //  Build the map tiles
  $hex_num = 1;
  $hgus_output  .=  "<div id=\"hex-grid\">";
  for ($i=0; $i<$rows; $i++)
    { 
    if ($i % 2 == 0)
      {
      $hgus_output  .=  "<div class=\"hex-row\">";
      $col_count    =   $cols;
      } else  {
      $hgus_output  .=  "<div class=\"hex-row even\">";
      $col_count    =   $cols - 1;
      }
    for ($j=0; $j<$col_count; $j++)
      {
      $state_short_name = $states[$hex_num]['stateShortName'];
      if($state_short_name !="")
        {
        $hgus_output .= "<div id=\"hex" . $hex_num . "\" class=\"hex " . $states[$hex_num]['class'] . "\"><div class=\"hex-content\"><p>" . $state_short_name . "</p></div></div>";
        } else  {
        $hgus_output .= "<div id=\"hex" . $hex_num . "\" class=\"hex blank\"></div>";
        }
      $hex_num++;
      }
    $hgus_output  .=  "</div>";
    }    
  $hgus_output  .=  "</div>";

//  Build the legend
  $b_id  = 1;
  $b_c   =  count($bucket_breaks);

  ksort($bucket_breaks);
  
  $hgus_output    .=  "<div id=\"map-legend\">";
  foreach($bucket_breaks as $b)
    {
    $hgus_output  .=  "<div class=\"legend-item\"><div class=\"hex-legend class" . $b_id . "\"></div><p>";
    $hgus_output  .=  $b['low'] . " - " . number_format($b['high']);          // number_format(
    $hgus_output    .=  "</p></div>";
    $b_id++;
    }
  $hgus_output    .=  "</div>";
  return $hgus_output;
  }

//
//
//  Generate the event listeners for when map hexagons are rolled over
//
//

function fsf_cms_hex_grid_us_js_event_listeners()
  {
  global $fsfcms_api_url;
  
  $fsf_api_file       = "fsf.cms.getStatesByHexId.php";
  $fsf_api_options    = "";

  $states_json        = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $states             = json_decode($states_json,true);  

  $status = array_pop($states);
  if($status == 200)
    {  
    foreach($states AS $hex_id => $attributes) 
      {
      $state_slug = $attributes['stateSlug'];
      if(!is_null($state_slug) && $state_slug != "")
        {
        $hgusjseh_output  .=  "document.getElementById(\"hex" . $hex_id . "\").addEventListener(\"click\",function(){getHexTileThumbs(\"hex" . $hex_id . "\",\"" . $state_slug . "\");});\n\t";
        }
      }
    } else  {
    $hgusjseh_output  = "<p>" . $status . " Generation of JavaScript Event Handlers for U.S. Hexagon Cartogram Failed. </p>";
    }  
  return $hgusjseh_output;  
  }
  

//  Generate the thumbnail images (generic function)
function fsf_port_thumbnail_links($fsf_port_thumbnails)
  {
  $fsf_port_thumbnail_links_output            = "";
  
  foreach($fsf_port_thumbnails as $fsf_port_thumbnail)
    {
    $fsf_port_thumbnail_image_title           = $fsf_port_thumbnail['title'];
    $fsf_port_thumbnail_image_link_clean_URL  = $fsf_port_thumbnail['imageLink'];
    $fsf_port_thumbnail_source                = $fsf_port_thumbnail['thumbnailURL'];
    $fsf_port_thumbnail_width                 = $fsf_port_thumbnail['thumbnailWidth'];
    $fsf_port_thumbnail_height                = $fsf_port_thumbnail['thumbnailHeight'];
    $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-wrapper\">";
    $fsf_port_thumbnail_links_output          .=  "<div class=\"thumbnails-border\"><div class=\"thumbnail-images\"><a href=\"/" . $fsf_port_thumbnail_image_link_clean_URL . "\"><img src =\"" . $fsf_port_thumbnail_source . "\" width=\"" . $fsf_port_thumbnail_width . "\" height=\"" . $fsf_port_thumbnail_height . "\"  alt=\"" . $fsf_port_thumbnail_image_title . "\" title=\"" . $fsf_port_thumbnail_image_title . "\" /></a></div></div>";
    $fsf_port_thumbnail_links_output          .=  "</div>";
    }
    
  return $fsf_port_thumbnail_links_output;  
  }

//  Format the date and time

function fsf_cms_format_datetime($fsfcms_unix_ts)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.getOption.php";
  $fsf_api_options  = "?option=serverTimeZone";

  $server_timezone_name_json    = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $server_timezone_name_array   = json_decode($server_timezone_name_json,true);
  $server_timezone_name_status  = array_pop($server_timezone_name_array);

  $date_time_formatted  = array();

  if($server_timezone_name_status == 200)
    {
    $fsf_api_options  = "?option=dateTimeFormat";
    $datetime_format_json     = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
    $datetime_format_array    = json_decode($datetime_format_json,true);
    $datetime_format_status   = array_pop($datetime_format_array);

    if($datetime_format_status == 200)
      {    
      $server_timezone_offset = fsf_cms_get_server_timezone_offset_seconds($server_timezone_name_array['option']);    
      $server_ts              = $server_timezone_offset + $fsfcms_unix_ts;
      $date_time_formatted['dateFormatted']       = date($datetime_format_array['option'],$server_ts);
      $date_time_formatted['timeFormatted12']     = date("g:i a",$server_ts);
      $date_time_formatted['timeFormatted24']     = date("H:i",$server_ts);
      $date_time_formatted['timeFormattedFuzzy']  = fsf_cms_fuzzy_time($server_ts);
      return $date_time_formatted;
      }
    } else  {
    // No timezone!
    }   
  }

function fsf_cms_get_server_timezone_offset_seconds($server_timezone_name)
  {
  $server_timezone  = new DateTimeZone($server_timezone_name);
  $server_datetime  = new DateTime("now", $server_timezone);
  $offset_seconds   = $server_datetime->format("Z");
  
  return $offset_seconds;
  }

function fsf_cms_fuzzy_time($fsfcms_ts)
  {
  $fsfcms_hours = date("G",$fsfcms_ts);
  $fsfcms_prefix = "in the ";

  if ($fsfcms_hours < 6)
    {
    $fsfcms_fuzzy_time  = "o'dark thirty";
    $fsfcms_prefix      = "at ";
    } elseif ($fsfcms_hours < 12) {
    $fsfcms_fuzzy_time = "morning";
    } elseif ($fsfcms_hours < 17) {
    $fsfcms_fuzzy_time = "afternoon";
    } elseif ($fsfcms_hours < 21) {
    $fsfcms_fuzzy_time = "evening";
    } elseif ($fsfcms_hours < 24) {
    $fsfcms_fuzzy_time = "night";
    $fsfcms_prefix = "at ";
    } else  {
    $fsfcms_fuzzy_time = "";
    $fsfcms_prefix = "";
    }

  $fsfcms_fuzzy_time_output  = $fsfcms_prefix . $fsfcms_fuzzy_time;
  return $fsfcms_fuzzy_time_output;
  }
  
function fsf_cms_expandShortURL($fsfcms_key_prefix, $fsfcms_short_key)
  {
  global $fsfcms_api_url;

  $fsf_api_file     = "fsf.cms.expandShortURL.php";
  $fsf_api_options  = "?keyPrefix=" . $fsfcms_key_prefix;
  if($fsfcms_short_key != "")
    {
    $fsf_api_options  .= "&shortKey=" . $fsfcms_short_key; 
    }
  $fsf_cms_expandShortURL_json    = fsf_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_cms_expandShortURL_array   = json_decode($fsf_cms_expandShortURL_json,TRUE);
  $fsf_cms_expandShortURL_status  = array_pop($fsf_cms_expandShortURL_array);
     
  if($fsf_cms_expandShortURL_status == 200)
    {
    $fsf_cms_long_URL = $fsf_cms_expandShortURL_array['longURL'];
    return $fsf_cms_long_URL;    
    }
  }
?>