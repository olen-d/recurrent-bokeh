<?php
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getSiteGlobalItemsPerPage_output = array();
$fsfcms_getSiteGlobalItemsPerPage_none   = "FSFGSGIPP-None-Found";
  
$fsfcms_getSiteGlobalItemsPerPage_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'globalItemsPerPage' LIMIT 1";
$fsfcms_getSiteGlobalItemsPerPage_result = mysql_query($fsfcms_getSiteGlobalItemsPerPage_query);
$fsfcms_getSiteGlobalItemsPerPage_row    = mysql_fetch_row($fsfcms_getSiteGlobalItemsPerPage_result);
$fsfcms_getSiteGlobalItemsPerPage_output[] = $fsfcms_getSiteGlobalItemsPerPage_row[0];

header('Content-Type: application/json');
echo json_encode($fsfcms_getSiteGlobalItemsPerPage_output);
?>
