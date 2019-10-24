<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_getNextImageCleanURL_output     = array();
$fsfcms_gnicu_url_prefix                = "";
$fsfcms_gnicu_post_output               = array();

// Initialize Get Variables
$fsfcms_current_image_id        = $_GET['imageId'];
$fsfcms_page_request            = trim(urldecode($_GET['pageReq']),"/");

//  Preserve any extraneous content in the page request string
$fsfcms_page_request_parts      = explode("/",$fsfcms_page_request);
if(count($fsfcms_page_request_parts) > 3)
  {
  $fsfcms_years = preg_grep("/^\d{4}$/",$fsfcms_page_request_parts);
  $fsfcms_year  = array_shift($fsfcms_years);
  $fsfcms_limit = array_search($fsfcms_year,$fsfcms_page_request_parts);
  for($i=0;$i < $fsfcms_limit;$i++)
    {
    $fsfcms_gnicu_url_prefix .= $fsfcms_page_request_parts[$i] . "/";
    }  
  $fsfcms_gnicu_slug  = $fsfcms_page_request_parts[1];
  $fsfcms_gnicu_page  = reset($fsfcms_page_request_parts);  
  } else  {
  $fsfcms_gnicu_slug  = "";
  $fsfcms_gnicu_page  = "image";
  }

//  Set up the DB queries
//  Get the next image     

if($fsfcms_gnicu_statement  = $fsfcms_db_link->prepare("SELECT post FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".id  = ? AND " . 
                                                      FSFCMS_IMAGES_TABLE . ".post < ? LIMIT 1"))
  {
  if($fsfcms_gnicu_statement->bind_param("is",$fsfcms_current_image_id,$fsfcms_current_time_mysql_format))
    {
    if($fsfcms_gnicu_statement->execute())
      {
      if($fsfcms_gnicu_statement->bind_result($post))
        { 
        while ($fsfcms_gnicu_statement->fetch())
          {
          $fsfcms_gnicu_post_output[] = $post;  
          }
        $fsfcms_gnicu_post_output['status']          = 200;
        } else  {
        $fsfcms_gnicu_post_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_gnicu_post_output['status']          = 500;        
        }
      } else  {
      $fsfcms_gnicu_post_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_gnicu_post_output['status']            = 500;      
      }
    } else  {
    $fsfcms_gnicu_post_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_gnicu_post_output['status']              = 500;
    }
  } else  {
  $fsfcms_gnicu_post_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_gnicu_post_output['status']                = 500;
  }

$fsfcms_gnicu_post_status = array_pop($fsfcms_gnicu_post_output);
if ($fsfcms_gnicu_post_status == 200)
  {
  $fsfcms_current_image_post  = reset($fsfcms_gnicu_post_output);

  switch($fsfcms_gnicu_page)
    {
    case  "authors":
      $fsfcms_gnicu_next_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS nextYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS nextMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " LEFT JOIN " . FSFCMS_AUTHORS_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_AUTHORS_TABLE . 
                                                                ".image_parent_id INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".user_id = " .
                                                                FSFCMS_USERS_TABLE . ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post > ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_USERS_TABLE . ".author_slug = ? ORDER BY post ASC LIMIT 0,1";    

      if($fsfcms_gnicu_next_statement  = $fsfcms_db_link->prepare($fsfcms_gnicu_next_image_query))
        {
        $fsfcms_gnicu_prepare_status = TRUE;
        if($fsfcms_gnicu_next_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gnicu_slug))
          {
          $fsfcms_gnicu_bind_status = TRUE;
          } else  {
          $fsfcms_gnicu_bind_status = FALSE;         
          }
        } else  {

        $fsfcms_gnicu_prepare_status = FALSE;  
        }   
    break;
    case  "cameras":
      $fsfcms_gnicu_next_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS nextYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS nextMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " INNER JOIN " . FSFCMS_CAMERAS_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".camera_id = " . FSFCMS_CAMERAS_TABLE . 
                                                                ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post > ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_CAMERAS_TABLE . ".slug = ? ORDER BY post ASC LIMIT 0,1";    

      if($fsfcms_gnicu_next_statement  = $fsfcms_db_link->prepare($fsfcms_gnicu_next_image_query))
        {
        $fsfcms_gnicu_prepare_status = TRUE;
        if($fsfcms_gnicu_next_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gnicu_slug))
          {
          $fsfcms_gnicu_bind_status = TRUE;
          } else  {
          $fsfcms_gnicu_bind_status = FALSE;         
          }
        } else  {

        $fsfcms_gnicu_prepare_status = FALSE;  
        }   
    break;
    case  "categories":
      $fsfcms_gnicu_next_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS nextYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS nextMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " LEFT JOIN " . FSFCMS_CATEGORIES_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_TABLE . 
                                                                ".parent_id INNER JOIN " . FSFCMS_CATEGORY_NAMES_TABLE . " ON " . FSFCMS_CATEGORIES_TABLE . ".category_id = " .
                                                                FSFCMS_CATEGORY_NAMES_TABLE . ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post > ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_CATEGORY_NAMES_TABLE . ".category_slug = ? ORDER BY post ASC LIMIT 0,1";    

      if($fsfcms_gnicu_next_statement  = $fsfcms_db_link->prepare($fsfcms_gnicu_next_image_query))
        {
        $fsfcms_gnicu_prepare_status = TRUE;
        if($fsfcms_gnicu_next_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gnicu_slug))
          {
          $fsfcms_gnicu_bind_status = TRUE;
          } else  {
          $fsfcms_gnicu_bind_status = FALSE;         
          }
        } else  {

        $fsfcms_gnicu_prepare_status = FALSE;  
        }
    break;
    case  "keywords":
      $fsfcms_gnicu_next_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS nextYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS nextMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . 
                                                                ".image_parent_id INNER JOIN " . FSFCMS_KEYWORDS_TABLE . " ON " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " .
                                                                FSFCMS_KEYWORDS_TABLE . ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post > ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_KEYWORDS_TABLE . ".keyword_slug = ? ORDER BY post ASC LIMIT 0,1";

      if($fsfcms_gnicu_next_statement  = $fsfcms_db_link->prepare($fsfcms_gnicu_next_image_query))
        {
        $fsfcms_gnicu_prepare_status = TRUE;
        if($fsfcms_gnicu_next_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gnicu_slug))
          {
          $fsfcms_gnicu_bind_status = TRUE;
          } else  {
          $fsfcms_gnicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gnicu_prepare_status = FALSE;
        }    
    break;
    default:
    case  "media":
      $fsfcms_gnicu_next_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS nextYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS nextMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " INNER JOIN " . FSFCMS_MEDIA_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".media_id = " . FSFCMS_MEDIA_TABLE . 
                                                                ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post > ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_MEDIA_TABLE . ".slug = ? ORDER BY post ASC LIMIT 0,1";    

      if($fsfcms_gnicu_next_statement  = $fsfcms_db_link->prepare($fsfcms_gnicu_next_image_query))
        {
        $fsfcms_gnicu_prepare_status = TRUE;
        if($fsfcms_gnicu_next_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gnicu_slug))
          {
          $fsfcms_gnicu_bind_status = TRUE;
          } else  {
          $fsfcms_gnicu_bind_status = FALSE;         
          }
        } else  {

        $fsfcms_gnicu_prepare_status = FALSE;  
        }   
    break;
    case  "image":    
      $fsfcms_gnicu_next_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS nextYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS nextMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post > ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? ORDER BY post ASC LIMIT 0,1";

      if($fsfcms_gnicu_next_statement  = $fsfcms_db_link->prepare($fsfcms_gnicu_next_image_query))
        {
        $fsfcms_gnicu_prepare_status = TRUE;
        if($fsfcms_gnicu_next_statement->bind_param("ss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format))
          {
          $fsfcms_gnicu_bind_status = TRUE;
          } else  {
          $fsfcms_gnicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gnicu_prepare_status = FALSE;
        }
    break;
    }
    
  if($fsfcms_gnicu_prepare_status)
    {
    if($fsfcms_gnicu_bind_status)
      {
      if($fsfcms_gnicu_next_statement->execute())
        {
        if($fsfcms_gnicu_next_statement->bind_result($title,$nextYear,$nextMonth,$titleSlug))
          { 
          while ($fsfcms_gnicu_next_statement->fetch())
            {
            $fsfcms_getNextImageCleanURL_output['nextCleanURL']     = "/" . $fsfcms_gnicu_url_prefix . $nextYear . "/" . $nextMonth . "/" . $titleSlug;
            $fsfcms_getNextImageCleanURL_output['nextTitle']        = $title;  
            }
          $fsfcms_getNextImageCleanURL_output['status']          = 200;
          } else  {
          $fsfcms_getNextImageCleanURL_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
          $fsfcms_getNextImageCleanURL_output['status']          = 500;        
          }
        } else  {
        $fsfcms_getNextImageCleanURL_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
        $fsfcms_getNextImageCleanURL_output['status']            = 500;      
        }
      } else  {
      $fsfcms_getNextImageCleanURL_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
      $fsfcms_getNextImageCleanURL_output['status']              = 500;
      }
    } else  {
    //  $fsfcms_db_error  = $fsfcms_db_link->error;
    $fsfcms_getNextImageCleanURL_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
    $fsfcms_getNextImageCleanURL_output['status']                = 500;
    }
  } else  {
  // Failed to get current image
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getNextImageCleanURL_output);

?>