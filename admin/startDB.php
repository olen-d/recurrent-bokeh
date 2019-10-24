<?php

$fsfcms_general_error   = "HTTP/1.0 500 Internal Server Error";
$fsfcms_specific_error  = "";

$fsfcms_db_link = @mysql_connect(FSFCMS_DB_HOST, FSFCMS_DB_USERNAME, FSFCMS_DB_PASSWORD);
if (!$fsfcms_db_link)
  {
  $fsfcms_specific_error  = "Could not connect to the database";
  // Check to see if the user is logged in and issue a mysql_error - $fsfcms_mysql_error = mysql_error();
  header($fsfcms_general_error);
  die("<h1>" . $fsfcms_general_error . "</h1><h2>" . $fsfcms_specific_error . "</h2>");
  }

if (!mysql_select_db(FSFCMS_DB_NAME))
  {
  $fsfcms_specific_error  = "Could not select the specified database";
  // Check to see if the user is logged in and issue a mysql_error - $fsfcms_mysql_error = mysql_error();
  header($fsfcms_general_error);
  die("<h1>" . $fsfcms_general_error . "</h1><h2>" . $fsfcms_specific_error . "</h2>");
  }  

// Get global configuration options from the database
$fsfcms_api_url_result = mysql_query("SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'siteAPIURL' LIMIT 1");
if ($fsfcms_api_url_result)
  {
  $fsfcms_api_url_row = mysql_fetch_row($fsfcms_api_url_result);
  $fsfcms_api_url = $fsfcms_api_url_row[0];
  define("FSFCMS_API_URL",$fsfcms_api_url);
  } else  {
  echo "<p><b>Fatal error</b>: Failed to retrieve API URL from the database. Please check your database settings and try again. </p>";
  exit;
  }
//echo "<p>API Found: " . $fsfcms_api_url . "</p>";
?>
