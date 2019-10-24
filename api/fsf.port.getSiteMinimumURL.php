<?php
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getSiteMinimumURL_output = array();
$fsfcms_getSiteMinimumURL_none   = "FSFGSMU-None-Found";
  
$fsfcms_getSiteMinimumURL_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'siteMinimumURL' LIMIT 1";
$fsfcms_getSiteMinimumURL_result = mysql_query($fsfcms_getSiteMinimumURL_query);
$fsfcms_getSiteMinimumURL_row    = mysql_fetch_row($fsfcms_getSiteMinimumURL_result);
$fsfcms_getSiteMinimumURL_output[] = $fsfcms_getSiteMinimumURL_row[0];

header('Content-Type: application/json');
echo json_encode($fsfcms_getSiteMinimumURL_output);
?>
