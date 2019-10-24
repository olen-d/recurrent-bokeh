<?php

// Note - consider creating a common functions file for both the admin and front sides
// This section is packed with candidates for inclusion in the common functions file.
// The common file is fsf_cms_functions
// Move get total image number to fsf_cms_functions


function fsfcms_getPublishedImagesNumber()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.port.getPublishedImages.php";
  $fsf_api_options  = "";

  $fsf_port_published_images_json = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_port_published_images      = json_decode($fsf_port_published_images_json,true);
  $fsf_port_published_images_number = $fsf_port_published_images['publishedImages'];     
  return $fsf_port_published_images_number;  
  }

//
//
//  API Functions
//
//               
function fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options)
  {     
  $fsf_port_cookies = "";
  
  // Get the cookies and dump them into a string that CURL can understand 
  foreach($_COOKIE as $cookie_name => $cookie_value)
    {
    $fsf_port_cookies .= $cookie_name . "=" . $cookie_value . ";"; 
    }
   
  $fsf_preacher_curl_connect_timeout  = 9000;     // milliseconds
  $fsf_preacher_curl_request_timeout  = 9000;     // milliseconds
  session_write_close();
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



function fsf_admin_port_makeImageShortLinkCleanURL($fsfcms_image_id)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.admin.port.makeImageShortLinkCleanURL.php";
  $fsf_api_options  = "?image_id=" . $fsfcms_image_id;


  $fsf_port_makeImageShortLinkCleanURL = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsf_port_makeImageShortLinkCleanURL;                                                               
  }

function fsfcms_status_CPUload()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.status.CPUload.php";
  $fsf_api_options  = "";

  $fsf_status_CPUload_json  = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_status_CPUload       = json_decode($fsf_status_CPUload_json,true);     
  return $fsf_status_CPUload;    
  }

function fsfcms_status_RAM()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.status.RAM.php";
  $fsf_api_options  = "";

  $fsf_status_RAM_json  = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_status_RAM       = json_decode($fsf_status_RAM_json,true);     
  return $fsf_status_RAM;    
  }

function fsfcms_status_IO()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.status.IO.php";
  $fsf_api_options  = "";

  $fsf_status_IO_json  = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_status_IO       = json_decode($fsf_status_IO_json,true);     
  return $fsf_status_IO;    
  }

function fsfcms_status_DB()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.status.DB.php";
  $fsf_api_options  = "";

  $fsf_status_DB_json  = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);
  $fsf_status_DB       = json_decode($fsf_status_DB_json,true);     
  return $fsf_status_DB;    
  }


// Get all image data. Useful on the portfolio page, for example.
function fsfcms_get_images_all_data($fsfcms_page,$fsfcms_images_per_page,$filter,$filter_id)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.admin.getImagesAllData.php";
  $fsf_api_options  = "?page=" . $fsfcms_page . "&imagesPerPage=" . $fsfcms_images_per_page;
  if ($filter != "")
    {
    $fsf_api_options .= "&filter=" . $filter . "&filterId=" .$filter_id;
    }
  $fsf_admin_get_imagea_all_data_json = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsf_admin_get_imagea_all_data_json;  
  }

// Get the path for where admin include files are stored
function fsfcms_admin_get_admin_include_path()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.admin.getAdminIncludePath.php";
  $fsf_api_options  = "";

  $fsfcms_admin_get_admin_include_path_json   = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_admin_get_admin_include_path        = json_decode($fsfcms_admin_get_admin_include_path_json,true);
  $fsfcms_admin_get_admin_include_path_output = $fsfcms_admin_get_admin_include_path['adminIncludePath'];
  return $fsfcms_admin_get_admin_include_path_output;  
  }

// Get all configuration options for the CMS
function fsfcms_admin_get_options()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.admin.getOptions.php";
  $fsf_api_options  = "";

  $fsfcms_admin_get_options_json   = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsfcms_admin_get_options_json;  
  }

// Get the list of templates
function fsfcms_admin_get_templates()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.admin.getTemplates.php";
  $fsf_api_options  = "";

  $fsfcms_admin_get_templates_json   = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsfcms_admin_get_templates_json;  
  }

//  Get the list of template pages
function fsfcms_cms_get_template_pages($template_id)
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.getTemplatePages.php";
  $fsf_api_options  = "?templateId=" . $template_id;

  $fsfcms_cms_get_template_pages_json   = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_cms_get_template_pages        = json_decode($fsfcms_cms_get_template_pages_json,true);
  return $fsfcms_cms_get_template_pages; 
  }

//  Get the list of users
function fsfcms_cms_getUsersList()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.cms.getUsersList.php";
  $fsf_api_options  = "";

  $fsfcms_cms_get_users_list_json   = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  return $fsfcms_cms_get_users_list_json; 
  }

//  Get the list of date and time formats
function fsfcms_cms_get_datetime_formats()
  {
  global $fsfcms_api_url;
  $fsf_api_file     = "fsf.admin.getDateTimeFormats.php";
  $fsf_api_options  = "";

  $fsfcms_cms_get_datetime_formats_json   = fsf_admin_preacher_curl($fsfcms_api_url, $fsf_api_file, $fsf_api_options);     
  $fsfcms_cms_get_datetime_formats        = json_decode($fsfcms_cms_get_datetime_formats_json,true);
  return $fsfcms_cms_get_datetime_formats; 
  }
    
function fsfcms_make_image_thumbnail($fsfcms_image_source_file,$fsfcms_thumb_destination_file)
  {
  global $fsfcms_config_table;
  /*
  $fsfcms_portImageURL_query      = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting ='portImageURL' LIMIT 1";
  $fsfcms_portImageURL_result     = mysql_query($fsfcms_portImageURL_query);
  $fsfcms_portImageURL_row        = mysql_fetch_row($fsfcms_portImageURL_result); 
  $fsfcms_image_source_path       = $fsfcms_portImageURL_row[0];
  $fsfcms_image_source_file       = $fsfcms_image_source_path . $fsfcms_image_source_filename;
  */ 
  $fsfcms_image_source            = imagecreatefromjpeg($fsfcms_image_source_file);
  $fsfcms_image_source_width      = imagesx($fsfcms_image_source);
  $fsfcms_image_source_height     = imagesy($fsfcms_image_source);
  $fsfcms_portThumbsWidth_query      = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting ='portThumbsWidth' LIMIT 1";
  $fsfcms_portThumbsWidth_result     = mysql_query($fsfcms_portThumbsWidth_query);
  $fsfcms_portThumbsWidth_row        = mysql_fetch_row($fsfcms_portThumbsWidth_result); 
  $fsfcms_thumb_width       = $fsfcms_portThumbsWidth_row[0];

  $fsfcms_portThumbsHeight_query      = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting ='portThumbsHeight' LIMIT 1";
  $fsfcms_portThumbsHeight_result     = mysql_query($fsfcms_portThumbsHeight_query);
  $fsfcms_portThumbsHeight_row        = mysql_fetch_row($fsfcms_portThumbsHeight_result); 
  $fsfcms_thumb_height       = $fsfcms_portThumbsHeight_row[0];

  // Figure out if the image is square, horizontal, or vertical
  if ($fsfcms_image_source_width == $fsfcms_image_source_height)
    {
    $fsfcms_image_source_orientation  = "square";
    } elseif ($fsfcms_image_source_width > $fsfcms_image_source_height) {
    $fsfcms_image_source_orientation  = "landscape";
    } elseif ($fsfcms_image_source_width < $fsfcms_image_source_height) {
    $fsfcms_image_source_orientation  = "portrait";
    } else  {
    // Epic Fail
    $fsfcms_image_source_orientation  = "<p class=\"fail\">Error: Something has gone horribly wrong. Have a nice day!</p><p class=\"fail-info\">Image width: "  
                                        . $fsfcms_image_source_width . "<br />Image height: " . $fsfcms_image_source_height . "</p>";
    }

  // Figure out if the thumbnail is square horizontal, or vertical
  if ($fsfcms_thumb_width == $fsfcms_thumb_height)
    {
    $fsfcms_thumb_orientation  = "square";
    } elseif ($fsfcms_thumb_width > $fsfcms_thumb_height) {
    $fsfcms_thumb_orientation  = "landscape";
    } elseif ($fsfcms_thumb_width < $fsfcms_thumb_height) {
    $fsfcms_thumb_orientation  = "portrait";
    } else  {
    // Epic Fail
    $fsfcms_thumb_orientation  = "<p class=\"fail\">Error: Something has gone horribly wrong. Have a nice day!</p><p class=\"fail-info\">Thumbnail width: "  
                                        . $fsfcms_thumb_width . "<br />Thumbnail height: " . $fsfcms_thumb_height . "</p>";
    }

  /*
  $fsfcms_portThumbsURL_query      = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting ='portThumbsURL' LIMIT 1";
  $fsfcms_portThumbsURL_result     = mysql_query($fsfcms_portThumbsURL_query);
  $fsfcms_portThumbsURL_row        = mysql_fetch_row($fsfcms_portThumbsURL_result); 
  $fsfcms_portThumb_destination       = $fsfcms_portThumbsURL_row[0];
  */
  
  if ($fsfcms_image_source_orientation != $fsfcms_thumb_orientation)
    {
    if ($fsfcms_image_source_orientation == "portrait" && $fsfcms_thumb_orientation == "landscape")
      {
      $scale  = $fsfcms_thumb_height / $fsfcms_thumb_width; 
      $src_width  = $fsfcms_image_source_width;
      $src_height = $fsfcms_image_source_width * $scale;
      $src_x  = 0;
      $fsfcms_image_source_midpoint = $fsfcms_image_source_height / 2;
      $fsfcms_image_new_midpoint = $src_height / 2;
      $src_y  = $fsfcms_image_source_midpoint - $fsfcms_image_new_midpoint;
      }
      
    } else  {
      // Deal with landscape to portrait if ($fsfcms_image_source_orientation == "landscape" && $fsfcms_thumb_orientation == "portrait")
      // Don't forget about squares, too
      $src_x  = 0;
      $src_y  = 0;
      $src_width = $fsfcms_image_source_width;
      $src_height = $fsfcms_image_source_height;
    }
    $fsfcms_virtual_image     = imagecreatetruecolor($fsfcms_thumb_width, $fsfcms_thumb_height);
    imagecopyresampled($fsfcms_virtual_image, $fsfcms_image_source, 0, 0, $src_x, $src_y, $fsfcms_thumb_width, $fsfcms_thumb_height, $src_width, $src_height);
    imagejpeg($fsfcms_virtual_image, $fsfcms_thumb_destination_file);
  }

function fsfcms_delete_file($fsfcms_file)
  {
  if(file_exists($fsfcms_file))
    {
    if(unlink($fsfcms_file))
      {
      $fsfcms_delete_status = 200;
      } else  {
      $fsfcms_delete_status = 500;
      }
    }  else  {
    $fsfcms_delete_status = 404;
    }
  return $fsfcms_delete_status;  
  }  
?>