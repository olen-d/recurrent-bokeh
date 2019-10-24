<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getAuthorInfoByID_output  = array();
$fsfcms_getAuthorInfoByID_none    = "FSFPGAIBI-None-Found";
$fsfcms_author_id                 = $_GET['authorID'];

$fsfcms_getAuthorInfoByID_query   = "SELECT id, username, name_first, name_middle, name_last, email_address FROM " . $fsfcms_users_table .  
                                    " WHERE id = " . $fsfcms_author_id . " LIMIT 1";

$fsfcms_getAuthorInfoByID_result  = mysql_query($fsfcms_getAuthorInfoByID_query);
   
if($fsfcms_getAuthorInfoByID_result)
  {
  $fsfcms_author_info_num_rows = mysql_num_rows($fsfcms_getAuthorInfoByID_result);
  if($fsfcms_author_info_num_rows > 0)
    {
    $fsfcms_getAuthorInfoByID_row = mysql_fetch_row($fsfcms_getAuthorInfoByID_result);
    $fsfcms_getAuthorInfoByID_output['authorID']          = $fsfcms_getAuthorInfoByID_row[0];
    $fsfcms_getAuthorInfoByID_output['authorUserName']    = $fsfcms_getAuthorInfoByID_row[1];
    $fsfcms_getAuthorInfoByID_output['authorFirstName']   = $fsfcms_getAuthorInfoByID_row[2];
    $fsfcms_getAuthorInfoByID_output['authorMiddleName']  = $fsfcms_getAuthorInfoByID_row[3];
    $fsfcms_getAuthorInfoByID_output['authorLastName']    = $fsfcms_getAuthorInfoByID_row[4];
    $fsfcms_getAuthorInfoByID_output['authorEmail']       = $fsfcms_getAuthorInfoByID_row[5]; 
    } else  {
    $fsfcms_getAuthorInfoByID_output['0'] = $fsfcms_getAuthorInfoByID_none;
    }
  } else  {
  $fsfcms_getAuthorInfoByID_output['0'] = $fsfcms_getAuthorInfoByID_none;  
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getAuthorInfoByID_output);
?>
