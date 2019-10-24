<?php

$fsfcms_is_logged_in      = FALSE;
$fsfcms_current_time      = time();

// Check to see if the user is logged in.  If so, continue on, otherwise exit.
if ($HTTP_COOKIE_VARS["fsfcms_login"] == "yes")
  {
  session_start();
  if($_SESSION['fsfcms_s_is_logged_in'])
    {
    $fsfcms_is_logged_in  = TRUE;
    $fsfcms_current_time  = 2147483647;   // Note this is the end of the 32 bit UNIX epoch, so, obviously, this is a 2038 problem
    }
  }

$fsfcms_current_time_mysql_format = date("Y-m-d H:i:s", $fsfcms_current_time);    
?>