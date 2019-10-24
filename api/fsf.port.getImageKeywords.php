<?php

require "../admin/cfg.php";
require "../admin/startDBi.php";

//
//
//  Version:  1.0
//  Author:   Olen Daelhousen
//
//  Requires: Image id number, passed as "imageId"
//
//  Output:
//  Index
//    Keyword ID
//    Keyword
//    Keyword Slug ...

// Initialize Script Variables
$fsfcms_gik_output = array();
$fsfcms_gik_none   = "No keywords associated with the specified image were found. ";   // REM TO INTERNATIONALIZE THIS

// Initialize Get Variables
$fsfcms_gik_image_id       = $_GET['imageId'];

//  Prepare the statement and access the database
if($fsfcms_gik_keywords_statement  = $fsfcms_db_link->prepare("SELECT " . FSFCMS_KEYWORDS_TABLE . ".id AS keyword_id, keyword, keyword_slug FROM " . FSFCMS_KEYWORDS_TABLE . " LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " . FSFCMS_KEYWORDS_TABLE . ".id = " . FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id WHERE " . FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id = ? ORDER BY keyword ASC"))
  {
  if($fsfcms_gik_keywords_statement->bind_param("i",$fsfcms_gik_image_id))
    {
    if($fsfcms_gik_keywords_statement->execute())
      {
      if($fsfcms_gik_keywords_statement->bind_result($keyword_id,$keyword,$keyword_slug))
        { 
        while ($fsfcms_gik_keywords_statement->fetch())
          {
          $fsfcms_gik_output[]            = array (
                                                  keywordId   =>  $keyword_id,
                                                  keywordSlug =>  $keyword_slug,
                                                  keyword     =>  $keyword
                                                  ); 
          }
          if(count($fsfcms_gik_output) <= 0)
            {
            $fsfcms_gik_output[] = $fsfcms_gik_none;
            }
        $fsfcms_gik_output['status']          = 200;
        } else  {
        $fsfcms_gik_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_gik_output['status']          = 500;        
        }
      } else  {
      $fsfcms_gik_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_gik_output['status']            = 500;      
      }
    } else  {
    $fsfcms_gik_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_gik_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_gik_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_gik_output['status']                = 500;
  }

$fsfcms_gik_keywords_statement->free_result();

header('Content-Type: application/json');
echo json_encode($fsfcms_gik_output);
?>