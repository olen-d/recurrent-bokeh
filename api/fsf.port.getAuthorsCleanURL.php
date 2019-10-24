<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getAuthorsCleanURL_output = array();

// Get the list of authors
$fsfcms_getAuthorsCleanURL_query  = "SELECT " . $fsfcms_users_table . ".id, name_first, name_middle, name_last, author_slug, biography, COUNT(" . $fsfcms_authors_table . ".user_id) AS images_posted FROM " . $fsfcms_users_table . ", " . $fsfcms_authors_table . 
                                    " WHERE " . $fsfcms_users_table . ".id = " . $fsfcms_authors_table . ".user_id GROUP BY " . $fsfcms_authors_table . ".user_id";

$fsfcms_getAuthorsCleanURL_result = mysql_query($fsfcms_getAuthorsCleanURL_query);
   
if($fsfcms_getAuthorsCleanURL_result)
  {
  $fsfcms_total_authors = mysql_num_rows($fsfcms_getAuthorsCleanURL_result);
  if($fsfcms_total_authors > 0)
    { 
    while($fsfcms_getAuthorsCleanURL_row = mysql_fetch_assoc($fsfcms_getAuthorsCleanURL_result))
      {
      $fsfcms_getAuthorsCleanURL_output[] = array(
                                              userId              =>  $fsfcms_getAuthorsCleanURL_row['id'],
                                              firstName           =>  $fsfcms_getAuthorsCleanURL_row['name_first'],
                                              middleName          =>  $fsfcms_getAuthorsCleanURL_row['name_middle'],
                                              lastName            =>  $fsfcms_getAuthorsCleanURL_row['name_last'],
                                              authorSlug          =>  $fsfcms_getAuthorsCleanURL_row['author_slug'],
                                              biography           =>  $fsfcms_getAuthorsCleanURL_row['biography'],
                                              authorImagesPosted  =>  $fsfcms_getAuthorsCleanURL_row['images_posted'],
                                              authorCleanURL      =>  "authors/" . $fsfcms_getAuthorsCleanURL_row['author_slug']
                                              );            
      }
    $fsfcms_getAuthorsCleanURL_output[]['status']     = 200;
    } else  {
    $fsfcms_getAuthorsCleanURL_output['errorMessage'] = "Request could not be completed because no results were found in the database.";
    $fsfcms_getAuthorsCleanURL_output['status']       = 404;
    }  
  } else  {
  $fsfcms_getAuthorsCleanURL_output['errorMessage']   = "Request could not be completed because of a database error.";
  $fsfcms_getAuthorsCleanURL_output['status']         = 500;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getAuthorsCleanURL_output);
?>