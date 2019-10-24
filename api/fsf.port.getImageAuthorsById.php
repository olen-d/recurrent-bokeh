<?php

require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$fsfcms_gia_output    = array();

$fsfcms_gia_none      = "No authors were found for this image. ";

$fsfcms_gia_image_id  = $_GET['imageId'];

//  Get the list of authors
//  Define the query
$fsfcms_gia_query = "SELECT " . FSFCMS_USERS_TABLE . ".id as user_id, username, name_first, name_middle, name_last, author_slug FROM " .
                    FSFCMS_AUTHORS_TABLE . " INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".user_id = " . 
                    FSFCMS_USERS_TABLE . ".id INNER JOIN " . FSFCMS_IMAGES_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".image_parent_id = " . 
                    FSFCMS_IMAGES_TABLE . ".id WHERE " . FSFCMS_IMAGES_TABLE . ".id = ?";

//  Prepare the statement and access the database
if($fsfcms_gia_authors_statement  = $fsfcms_db_link->prepare($fsfcms_gia_query))
  {
  if($fsfcms_gia_authors_statement->bind_param("i",$fsfcms_gia_image_id))
    {
    if($fsfcms_gia_authors_statement->execute())
      {
      if($fsfcms_gia_authors_statement->bind_result($user_id,$username,$name_first,$name_middle,$name_last,$author_slug))
        { 
        while ($fsfcms_gia_authors_statement->fetch())
          {
          $fsfcms_gia_output[]  = array (
                                        userId => $user_id,
                                        authorSlug => $author_slug,
                                        authorUserName => $username,
                                        authorFirstName => $name_first,
                                        authorMiddleName => $name_middle,
                                        authorLastName => $name_last
                                        
                                        );
          }
          if(count($fsfcms_gia_output) <= 0)
            {
            $fsfcms_gia_output[] = $fsfcms_gia_none;
            }
        $fsfcms_gia_output['status']          = 200;
        } else  {
        $fsfcms_gia_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_gia_output['status']          = 500;        
        }
      } else  {
      $fsfcms_gia_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_gia_output['status']            = 500;      
      }
    } else  {
    $fsfcms_gia_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_gia_output['status']              = 500;
    }
  } else  {
  //  $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_gia_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_gia_output['status']                = 500;
  }

$fsfcms_gia_authors_statement->free_result();

header('Content-Type: application/json');
echo json_encode($fsfcms_gia_output);
?>