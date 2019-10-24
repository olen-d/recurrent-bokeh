<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_gkit_output = array();

if (isset($_GET['keywordSlug']))
  {
  $fsfcms_getKeywordImageThumbnails_keyword = $_GET['keywordSlug']; 
  }
  
// Get the URL for the Thumbnails & Width and Height From the Configuration Table

$fsfcms_gkit_thumbs_url_result    = $fsfcms_db_link->query("SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = 'portThumbsURL' LIMIT 1"); 
$fsfcms_gkit_thumbs_url_row       = $fsfcms_gkit_thumbs_url_result->fetch_row();
$fsfcms_gkit_thumbs_url           = $fsfcms_gkit_thumbs_url_row[0];

$fsfcms_gkit_thumbs_width_result  = $fsfcms_db_link->query("SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = 'portThumbsWidth' LIMIT 1");
$fsfcms_gkit_thumbs_width_row     = $fsfcms_gkit_thumbs_width_result->fetch_row();
$fsfcms_gkit_thumbs_width         = $fsfcms_gkit_thumbs_width_row[0];

$fsfcms_gkit_thumbs_height_result = $fsfcms_db_link->query("SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = 'portThumbsHeight' LIMIT 1");
$fsfcms_gkit_thumbs_height_row    = $fsfcms_gkit_thumbs_height_result->fetch_row();;
$fsfcms_gkit_thumbs_height        = $fsfcms_gkit_thumbs_height_row[0];

if (isset($_GET['items']))   
  {
  $fsfcms_getKeywordImageThumbnails_items = $_GET['items']; 
  if (isset($_GET['page']))
    {
    $fsfcms_getKeywordImageThumbnails_page = $_GET['page']; 
    } else  {
    $fsfcms_getKeywordImageThumbnails_page = 1;
    }
  $fsfcms_getKeywordImageThumbnails_offset = ($fsfcms_getKeywordImageThumbnails_page - 1) * $fsfcms_getKeywordImageThumbnails_items;
  $fsfcms_getKeywordImageThumbnails_limit  = " LIMIT " . $fsfcms_getKeywordImageThumbnails_items . " OFFSET " . $fsfcms_getKeywordImageThumbnails_offset;
  } else  {
  $fsfcms_getKeywordImageThumbnails_limit  = "";
  }

//  Prepare the statement and access the database
if($fsfcms_gkit_thumbs_statement  = $fsfcms_db_link->prepare("SELECT " . FSFCMS_IMAGES_TABLE . ".id, filename, title, caption, UNIX_TIMESTAMP(" . 
                                                              FSFCMS_IMAGES_TABLE . ".post) AS postedUnixTimestamp, YEAR(" . 
                                                              FSFCMS_IMAGES_TABLE . ".post) AS imageYear, DATE_FORMAT(" . 
                                                              FSFCMS_IMAGES_TABLE . ".post,'%m') AS imageMonth, title_slug FROM " . FSFCMS_IMAGES_TABLE . ", " . FSFCMS_KEYWORDS_TABLE . ", " . FSFCMS_KEYWORDS_MAP_TABLE .   
                                                              " WHERE " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id AND " . 
                                                              FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id = " . FSFCMS_KEYWORDS_TABLE . ".id AND " . 
                                                              FSFCMS_KEYWORDS_TABLE . ".keyword_slug = ? AND " . 
                                                              FSFCMS_IMAGES_TABLE . ".post < ? ORDER BY " . FSFCMS_IMAGES_TABLE . ".post DESC" . 
                                                              $fsfcms_getKeywordImageThumbnails_limit))
  {
  if($fsfcms_gkit_thumbs_statement->bind_param("ss",$fsfcms_getKeywordImageThumbnails_keyword,$fsfcms_current_time_mysql_format ))
    {
    if($fsfcms_gkit_thumbs_statement->execute())
      {
      if($fsfcms_gkit_thumbs_statement->bind_result($id,$filename,$title,$caption,$postedUnixTimestamp,$imageYear,$imageMonth,$title_slug))
        { 
        while ($fsfcms_gkit_thumbs_statement->fetch())
          {
          $fsfcms_gkit_output[] = array(
                                        id => $id,
                                        title => $title,
                                        caption => trim($caption),
                                        postedDateUnixTimestamp => $postedUnixTimestamp,
                                        imageLink => $imageYear . "/" . $imageMonth . "/" . $title_slug,
                                        thumbnailURL => $fsfcms_gkit_thumbs_url . "thumb_" . $filename,
                                        thumbnailWidth => $fsfcms_gkit_thumbs_width,
                                        thumbnailHeight => $fsfcms_gkit_thumbs_height
                                        );  
          }
        $fsfcms_gkit_output['status']          = 200;
        } else  {
        $fsfcms_gkit_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_gkit_output['status']          = 500;        
        }
      } else  {
      $fsfcms_gkit_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_gkit_output['status']            = 500;      
      }
    } else  {
    $fsfcms_gkit_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_gkit_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_gkit_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_gkit_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_gkit_output);
?>