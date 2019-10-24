<?php
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getSiteImageFilePath_output = array();
$fsfcms_getSiteImageFilePath_none   = "FSFGSIFP-None-Found";
  
$fsfcms_getSiteImageFilePath_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'portImagePath' LIMIT 1";
$fsfcms_getSiteImageFilePath_result = mysql_query($fsfcms_getSiteImageFilePath_query);
$fsfcms_getSiteImageFilePath_row    = mysql_fetch_row($fsfcms_getSiteImageFilePath_result);
$fsfcms_getSiteImageFilePath_output[] = $fsfcms_getSiteImageFilePath_row[0];

header('Content-Type: application/json');
echo json_encode($fsfcms_getSiteImageFilePath_output);
?>
