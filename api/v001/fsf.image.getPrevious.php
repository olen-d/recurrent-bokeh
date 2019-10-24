<?php
//  Note: fsf.port.getPreviousImageID is now depracated, as this microservice now returns the previous image ID

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output	            = array();
$post_output	    = array();

$url_prefix	        = "";

$fail_msg	        = "No previous image was found. ";

// Initialize Get Variables
$image_id	    = $_GET['imageId'];
$page_request	= trim(urldecode($_GET['pageReq']),"/");

//  Preserve any extraneous content in the page request string
$page_request_parts	= explode("/",$page_request);
if(count($fsfcms_page_request_parts) > 3)
  {
  $years = preg_grep("/^\d{4}$/",$fpage_request_parts);
  $year  = array_shift($years);
  $limit = array_search($year,$page_request_parts);
  for($i=0;$i < $limit;$i++)
    {
    $url_prefix .= $page_request_parts[$i] . "/";
    }
  $slug  = $page_request_parts[1];
  $page  = reset($page_request_parts);  
  } else  {
  $slug  = "";
  $page  = "image";
  }

//  Set up the DB queries     
//  Get the previous image
try {     
    $query	=   "SELECT post FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . FSFCMS_IMAGES_TABLE . ".id  = ? AND " . 
                FSFCMS_IMAGES_TABLE . ".post < ? LIMIT 1";

    $stmt	= $fsfcms_db_link -> prepare($query);
    $stmt->execute(array($image_id,$fsfcms_current_time_mysql_format));
    $row	= $stmt->fetch(PDO::FETCH_ASSOC);

    $post_output[] = $row['post'];
    $post_output['status']       = 200;  
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $post_output['error']    = $exception->getMessage();
    }
    $post_output['failMessage']  = $fail_msg;
    $post_output['status']       = 500;  
}

$post_status = array_pop($post_output);
if ($post_status == 200)
  {
  $current_image_post  = reset($post_output);

  switch($page) {
    case  "authors":
      $prev_query	=   "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                      FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                      " LEFT JOIN " . FSFCMS_AUTHORS_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_AUTHORS_TABLE . 
                      ".image_parent_id INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".user_id = " .
                      FSFCMS_USERS_TABLE . ".id WHERE " . 
                      FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                      ".post < ? AND " . FSFCMS_USERS_TABLE . ".author_slug = ? ORDER BY post DESC LIMIT 0,1";
    break;
    case  "cameras":
      $prev_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                      FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                      " INNER JOIN " . FSFCMS_CAMERAS_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".camera_id = " . FSFCMS_CAMERAS_TABLE . 
                      ".id WHERE " . 
                      FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                      ".post < ? AND " . FSFCMS_CAMERAS_TABLE . ".slug = ? ORDER BY post DESC LIMIT 0,1";    
    break;
    case  "categories":
      $prev_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                      FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                      " LEFT JOIN " . FSFCMS_CATEGORIES_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_TABLE . 
                      ".parent_id INNER JOIN " . FSFCMS_CATEGORY_NAMES_TABLE . " ON " . FSFCMS_CATEGORIES_TABLE . ".category_id = " .
                      FSFCMS_CATEGORY_NAMES_TABLE . ".id WHERE " . 
                      FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                      ".post < ? AND " . FSFCMS_CATEGORY_NAMES_TABLE . ".category_slug = ? ORDER BY post DESC LIMIT 0,1";    
    break;
    case  "keywords":
      $prev_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                      FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                      " LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . 
                      ".image_parent_id INNER JOIN " . FSFCMS_KEYWORDS_TABLE . " ON " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " .
                      FSFCMS_KEYWORDS_TABLE . ".id WHERE " . 
                      FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                      ".post < ? AND " . FSFCMS_KEYWORDS_TABLE . ".keyword_slug = ? ORDER BY post DESC LIMIT 0,1";
    break;
    case  "media":
      $prev_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                      FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . 
                      " INNER JOIN " . FSFCMS_MEDIA_TABLE . " ON " .FSFCMS_IMAGES_TABLE . ".media_id = " . FSFCMS_MEDIA_TABLE . 
                      ".id WHERE " . 
                      FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                      ".post < ? AND " . FSFCMS_MEDIA_TABLE . ".slug = ? ORDER BY post DESC LIMIT 0,1";    
    break;
    default:
    case  "image":    
      $prev_query  = "SELECT title, YEAR(" . FSFCMS_IMAGES_TABLE . ".post) AS prevYear, DATE_FORMAT(" . 
                      FSFCMS_IMAGES_TABLE . ".post,'%m') AS prevMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . " WHERE " . 
                      FSFCMS_IMAGES_TABLE . ".post < ? AND " . FSFCMS_IMAGES_TABLE . 
                      ".post < ? ORDER BY post DESC LIMIT 0,1";
    break;
  } 
  try {
      $stmt	= $fsfcms_db_link -> prepare($prev_query);
      if ($page == "image") {
        $stmt->execute(array($current_image_post,$fsfcms_current_time_mysql_format));
      } else {
        $stmt->execute(array($current_image_post,$fsfcms_current_time_mysql_format,$slug));
      }
      $row	= $stmt->fetch(PDO::FETCH_ASSOC);
      $output['previousCleanURL'] = "/" . $url_prefix . $row['prevYear'] . "/" . $row['prevMonth'] . "/" . $row['title_slug'];
      $output['previousTitle']    = $row['title'];
      $output['status']           = 200;  
  } catch(PDOException $exception) {
      if($fsfcms_is_logged_in) {
        $post_output['error']       = $exception->getMessage();
      }
      $post_output['failMessage'] = $fail_msg;
      $post_output['status']      = 500;  
  }
}

header('Content-Type: application/json');
echo json_encode($output);
?>