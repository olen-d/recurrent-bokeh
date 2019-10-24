<?php
//  DEPRACATED - fsf.image.getPrevious is the new black
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_getPreviousImageCleanURL_output = array();
$fsfcms_gpicu_url_prefix                = "";
$fsfcms_gpicu_post_output               = array();

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
    $fsfcms_gpicu_url_prefix .= $fsfcms_page_request_parts[$i] . "/";
    }
  $fsfcms_gpicu_slug  = $fsfcms_page_request_parts[1];
  $fsfcms_gpicu_page  = reset($fsfcms_page_request_parts);  
  } else  {
  $fsfcms_gpicu_slug  = "";
  $fsfcms_gpicu_page  = "image";
  }

//  Set up the DB queries     
//  Get the previous image     

if($fsfcms_gpicu_statement  = $fsfcms_db_link->prepare("SELECT post FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".id  = ? AND " . 
                                                      FSFCMS_IMAGES_TABLE . ".post < ? LIMIT 1"))
  {
  if($fsfcms_gpicu_statement->bind_param("is",$fsfcms_current_image_id,$fsfcms_current_time_mysql_format))
    {
    if($fsfcms_gpicu_statement->execute())
      {
      if($fsfcms_gpicu_statement->bind_result($post))
        { 
        while ($fsfcms_gpicu_statement->fetch())
          {
          $fsfcms_gpicu_post_output[] = $post;  
          }
        $fsfcms_gpicu_post_output['status']          = 200;
        } else  {
        $fsfcms_gpicu_post_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_gpicu_post_output['status']          = 500;        
        }
      } else  {
      $fsfcms_gpicu_post_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_gpicu_post_output['status']            = 500;      
      }
    } else  {
    $fsfcms_gpicu_post_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_gpicu_post_output['status']              = 500;
    }
  } else  {
  $fsfcms_gpicu_post_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_gpicu_post_output['status']                = 500;
  }

$fsfcms_gpicu_post_status = array_pop($fsfcms_gpicu_post_output);
if ($fsfcms_gpicu_post_status == 200)
  {
  $fsfcms_current_image_post  = reset($fsfcms_gpicu_post_output);

  switch($fsfcms_gpicu_page)
    {
    case  "authors":
      $fsfcms_gpicu_prev_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " LEFT JOIN " . FSFCMS_AUTHORS_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_AUTHORS_TABLE . 
                                                                ".image_parent_id INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".user_id = " .
                                                                FSFCMS_USERS_TABLE . ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_USERS_TABLE . ".author_slug = ? ORDER BY post DESC LIMIT 0,1";    

      if($fsfcms_gpicu_prev_statement  = $fsfcms_db_link->prepare($fsfcms_gpicu_prev_image_query))
        {
        $fsfcms_gpicu_prepare_status = TRUE;
        if($fsfcms_gpicu_prev_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gpicu_slug))
          {
          $fsfcms_gpicu_bind_status = TRUE;
          } else  {
          $fsfcms_gpicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gpicu_prepare_status = FALSE; 
        }
    break;
    case  "cameras":
      $fsfcms_gpicu_prev_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " INNER JOIN " . FSFCMS_CAMERAS_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".camera_id = " . FSFCMS_CAMERAS_TABLE . 
                                                                ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_CAMERAS_TABLE . ".slug = ? ORDER BY post DESC LIMIT 0,1";    

      if($fsfcms_gpicu_prev_statement  = $fsfcms_db_link->prepare($fsfcms_gpicu_prev_image_query))
        {
        $fsfcms_gpicu_prepare_status = TRUE;
        if($fsfcms_gpicu_prev_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gpicu_slug))
          {
          $fsfcms_gpicu_bind_status = TRUE;
          } else  {
          $fsfcms_gpicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gpicu_prepare_status = FALSE; 
        }    
    break;
    case  "categories":
      $fsfcms_gpicu_prev_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " LEFT JOIN " . FSFCMS_CATEGORIES_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_TABLE . 
                                                                ".parent_id INNER JOIN " . FSFCMS_CATEGORY_NAMES_TABLE . " ON " . FSFCMS_CATEGORIES_TABLE . ".category_id = " .
                                                                FSFCMS_CATEGORY_NAMES_TABLE . ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_CATEGORY_NAMES_TABLE . ".category_slug = ? ORDER BY post DESC LIMIT 0,1";    

      if($fsfcms_gpicu_prev_statement  = $fsfcms_db_link->prepare($fsfcms_gpicu_prev_image_query))
        {
        $fsfcms_gpicu_prepare_status = TRUE;
        if($fsfcms_gpicu_prev_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gpicu_slug))
          {
          $fsfcms_gpicu_bind_status = TRUE;
          } else  {
          $fsfcms_gpicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gpicu_prepare_status = FALSE; 
        }
    break;
    case  "keywords":
      $fsfcms_gpicu_prev_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . 
                                                                ".image_parent_id INNER JOIN " . FSFCMS_KEYWORDS_TABLE . " ON " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " .
                                                                FSFCMS_KEYWORDS_TABLE . ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_KEYWORDS_TABLE . ".keyword_slug = ? ORDER BY post DESC LIMIT 0,1";

      if($fsfcms_gpicu_prev_statement  = $fsfcms_db_link->prepare($fsfcms_gpicu_prev_image_query))
        {
        $fsfcms_gpicu_prepare_status = TRUE;
        if($fsfcms_gpicu_prev_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gpicu_slug))
          {
          $fsfcms_gpicu_bind_status = TRUE;
          } else  {
          $fsfcms_gpicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gpicu_prepare_status = FALSE;
        }    
    break;
    case  "media":
      $fsfcms_gpicu_prev_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                                                                " INNER JOIN " . FSFCMS_MEDIA_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".media_id = " . FSFCMS_MEDIA_TABLE . 
                                                                ".id WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? AND " . FSFCMS_MEDIA_TABLE . ".slug = ? ORDER BY post DESC LIMIT 0,1";    

      if($fsfcms_gpicu_prev_statement  = $fsfcms_db_link->prepare($fsfcms_gpicu_prev_image_query))
        {
        $fsfcms_gpicu_prepare_status = TRUE;
        if($fsfcms_gpicu_prev_statement->bind_param("sss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format,$fsfcms_gpicu_slug))
          {
          $fsfcms_gpicu_bind_status = TRUE;
          } else  {
          $fsfcms_gpicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gpicu_prepare_status = FALSE; 
        }    
    break;
    default:
    case  "image":    
      $fsfcms_gpicu_prev_image_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                                                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . 
                                                                FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                                                                ".post < ? ORDER BY post DESC LIMIT 0,1"; 

      if($fsfcms_gpicu_prev_statement  = $fsfcms_db_link->prepare($fsfcms_gpicu_prev_image_query))
        {
        $fsfcms_gpicu_prepare_status = TRUE;
        if($fsfcms_gpicu_prev_statement->bind_param("ss",$fsfcms_current_image_post,$fsfcms_current_time_mysql_format))
          {
          $fsfcms_gpicu_bind_status = TRUE;
          } else  {
          $fsfcms_gpicu_bind_status = FALSE;          
          }
        } else  {
        $fsfcms_gpicu_prepare_status = FALSE;
        }
    break;
    }
    
  if($fsfcms_gpicu_prepare_status)
    {
    if($fsfcms_gpicu_bind_status)
      {
      if($fsfcms_gpicu_prev_statement->execute())
        {
        if($fsfcms_gpicu_prev_statement->bind_result($title,$prevYear,$prevMonth,$titleSlug))
          { 
          while ($fsfcms_gpicu_prev_statement->fetch())
            {
            $fsfcms_getPreviousImageCleanURL_output['previousCleanURL']     = "/" . $fsfcms_gpicu_url_prefix . $prevYear . "/" . $prevMonth . "/" . $titleSlug;
            $fsfcms_getPreviousImageCleanURL_output['previousTitle']        = $title;  
            }
          $fsfcms_getPreviousImageCleanURL_output['status']          = 200;
          } else  {
          $fsfcms_getPreviousImageCleanURL_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
          $fsfcms_getPreviousImageCleanURL_output['status']          = 500;        
          }
        } else  {
        $fsfcms_getPreviousImageCleanURL_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
        $fsfcms_getPreviousImageCleanURL_output['status']            = 500;      
        }
      } else  {
      $fsfcms_getPreviousImageCleanURL_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
      $fsfcms_getPreviousImageCleanURL_output['status']              = 500;
      }
    } else  {
    //  $fsfcms_db_error  = $fsfcms_db_link->error;
    $fsfcms_getPreviousImageCleanURL_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
    $fsfcms_getPreviousImageCleanURL_output['status']                = 500;
    }
  } else  {
  // Failed to get current image
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getPreviousImageCleanURL_output);

?>