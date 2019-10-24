<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_gka_output = array();

if (isset($_GET['keywordId']))
  {
  //  TODO: SET THIS UP TO TAKE THE ID, FOR NOW IT DOES NOTHING
  exit;  
  } elseif (isset($_GET['keywordSlug']))  {
  $fsfcms_gka_keyword_slug = $_GET['keywordSlug']; 
  }

//  Prepare the statement and access the database
if($fsfcms_gka_statement  = $fsfcms_db_link->prepare("SELECT id, keyword, keyword_slug FROM " . FSFCMS_KEYWORDS_TABLE .    
                                                              " WHERE " . FSFCMS_KEYWORDS_TABLE . ".keyword_slug = ? 
                                                              LIMIT 1"))
  {
  if($fsfcms_gka_statement->bind_param("s",$fsfcms_gka_keyword_slug))
    {
    if($fsfcms_gka_statement->execute())
      {
      if($fsfcms_gka_statement->bind_result($id,$keyword,$keyword_slug))
        { 
        while ($fsfcms_gka_statement->fetch())
          {
          $fsfcms_gka_output    = array(
                                        id => $id,
                                        keyword => $keyword,
                                        keywordSlug => $keyword_slug
                                        );  
          }
        $fsfcms_gka_output['status']          = 200;
        } else  {
        $fsfcms_gka_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_gka_output['status']          = 500;        
        }
      } else  {
      $fsfcms_gka_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_gka_output['status']            = 500;      
      }
    } else  {
    $fsfcms_gka_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_gka_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_gka_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_gka_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_gka_output);
?>