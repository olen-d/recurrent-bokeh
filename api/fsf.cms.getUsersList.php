<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getUsersList_output = array();

// Get the list of cameras
$fsfcms_getUsersList_query  =  "SELECT id, username, name_first, name_middle, name_last, email_address, biography FROM " . 
                                        $fsfcms_users_table . " ORDER BY name_last ASC, name_first ASC";

$fsfcms_getUsersList_result = mysql_query($fsfcms_getUsersList_query);

if($fsfcms_getUsersList_result)
  {
  if(mysql_num_rows($fsfcms_getUsersList_result) > 0)
    { 
    while($fsfcms_getUsersList_row = mysql_fetch_assoc($fsfcms_getUsersList_result))
      {
      $fsfcms_getUsersList_output[] = array(
                                                    userId        =>  $fsfcms_getUsersList_row['id'],
                                                    userName      =>  $fsfcms_getUsersList_row['username'],
                                                    firstName     =>  $fsfcms_getUsersList_row['name_first'],
                                                    middleName    =>  $fsfcms_getUsersList_row['name_middle'],
                                                    lastName      =>  $fsfcms_getUsersList_row['name_last'],
                                                    emailAddress  =>  $fsfcms_getUsersList_row['email_address'],
                                                    biography     =>  $fsfcms_getUsersList_row['biography']
                                                    );            
      }
    $fsfcms_getUsersList_output['status'] = 200;
    } else  {
    $fsfcms_getUsersList_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getUsersList_output['status']        = 404;
    }  
  } else  {
  $fsfcms_getUsersList_output['errorMessage']  = "Request could not be completed because of a database error.";
  $fsfcms_getUsersList_output['status']        = 500;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getUsersList_output); 
?>
