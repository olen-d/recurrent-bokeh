<?php
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getSiteGlobalPaged = array();
$fsfcms_getSiteGlobalPaged_none   = "FSFGSAP-None-Found";
  
$fsfcms_getSiteGlobalPaged_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'globalPaged' LIMIT 1";
$fsfcms_getSiteGlobalPaged_result = mysql_query($fsfcms_getSiteGlobalPaged_query);
$fsfcms_getSiteGlobalPaged_row    = mysql_fetch_row($fsfcms_getSiteGlobalPaged_result);
$fsfcms_getSiteGlobalPaged_output[] = $fsfcms_getSiteGlobalPaged_row[0];

header('Content-Type: application/json');
echo json_encode($fsfcms_getSiteGlobalPaged_output);
?>
