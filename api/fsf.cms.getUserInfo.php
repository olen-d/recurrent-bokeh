<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getUserInfo_output = array();

// Autodetect whether an ID or slug has been provided
if(isset($_GET['userId']))
  {
  $fsfcms_user_id                   = $_GET['userId'];
  $fsfcms_getUserInfo_where_clause  = "id = " . $fsfcms_user_id; 
  } elseif(isset($_GET['userSlug']))  {
  $fsfcms_user_slug                 = $_GET['userSlug'];
  $fsfcms_getUserInfo_where_clause  = "author_slug = '" . $fsfcms_user_slug . "'";
  }
//  REM: EXIT AND RETURN AN ERROR IF NO USER ID IS PROVIDED  

//  Set Up the DB Queries
$fsfcms_getUserInfo_query   = "SELECT id, username, name_first, name_middle, name_last, author_slug, email_address, biography FROM " . $fsfcms_users_table . 
                              " WHERE " . $fsfcms_getUserInfo_where_clause . " LIMIT 1";

$fsfcms_getUserInfo_result  = mysql_query($fsfcms_getUserInfo_query);

if($fsfcms_getUserInfo_result)
  {
  if(mysql_num_rows($fsfcms_getUserInfo_result) > 0)
    {
    $fsfcms_getUserInfo_row = mysql_fetch_row($fsfcms_getUserInfo_result);
    $fsfcms_getUserInfo_output['userId']        = $fsfcms_getUserInfo_row[0];
    $fsfcms_getUserInfo_output['userName']      = $fsfcms_getUserInfo_row[1];
    $fsfcms_getUserInfo_output['firstName']     = $fsfcms_getUserInfo_row[2];
    $fsfcms_getUserInfo_output['middleName']    = $fsfcms_getUserInfo_row[3];
    $fsfcms_getUserInfo_output['lastName']      = $fsfcms_getUserInfo_row[4];
    $fsfcms_getUserInfo_output['authorSlug']    = $fsfcms_getUserInfo_row[5];
    $fsfcms_getUserInfo_output['emailAddress']  = $fsfcms_getUserInfo_row[6];
    $fsfcms_getUserInfo_output['biography']     = $fsfcms_getUserInfo_row[7];
    $fsfcms_getUserInfo_output['status']        = 200;
    } else  {
    $fsfcms_getUserInfo_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getUserInfo_output['status']        = 404;
    }
  } else  {
  $fsfcms_getUserInfo_output['errorMessage']    = "Request could not be completed because of a database error.";
  $fsfcms_getUserInfo_output['status']          = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getUserInfo_output);
?>
