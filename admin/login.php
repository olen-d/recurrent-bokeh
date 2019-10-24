<?php
require "pass/phpass-0.3/PasswordHash.php";

$hash_cost_log2   = 8; // MOVE TO CONFIG FILE LATERZ
$hash_portable    = FALSE; // MOVE TO CONFIG FILE LATERZ

$hasher = new PasswordHash($hash_cost_log2, $hash_portable);
$fsfcms_user = $_POST['userName'];
$fsfcms_pass = $_POST['pass'];   
$fsfcms_hash = '*';
$fsfcms_hash_query  = "SELECT id, password FROM " . $fsfcms_users_table . " WHERE username = '" . $fsfcms_user . "'";
$fsfcms_hash_result = mysql_query($fsfcms_hash_query) or die(mysql_error());
$fsfcms_hash_row = mysql_fetch_assoc($fsfcms_hash_result);
$fsfcms_hash = $fsfcms_hash_row['password'];

if ($hasher->CheckPassword($fsfcms_pass, $fsfcms_hash))
  {
  $what = 'Authentication succeeded';
  setcookie("fsfcms_login", "yes", time() + 3600, "/");
  $fsfcms_is_logged_in  = TRUE;
  session_start();
  $_SESSION['fsfcms_s_is_logged_in'] = TRUE;
  $_SESSION['fsfcms_s_internal_id'] = $fsfcms_hash_row['id'];
    
  // Check to see if Remember User is checked
  if($_POST['rememberUser'] == "on")
    {
    setcookie("fsfcms_remember_user", "yes", time() + 2592000, "/");  // Remember user name for 30 days
    setcookie("fsfcms_remember_user_name", $fsfcms_user, time() + 2592000, "/");
    } else  {
    setcookie("fsfcms_remember_user", "", time() - 3600, "/");        // Deletes the cookie if Remember User is not checked
    setcookie("fsfcms_remember_user_name", "", time() - 3600, "/");
    }

  //
  //
  // Remember to put the login time in the database
  //
  //

  header( 'Location: ' . fsfcms_getSiteURL() . 'admin/dashboard' );
  // Update session_time, session_id
  } else {
    $fsfcms_authentication_fail_flag  = 1;
    $what = 'Your user name or password was incorrect.';
    $what_formatted = "<div id=\"authentication-failed\"><p>" . $what . "</p></div>";
  } 
unset($hasher); 
?>
