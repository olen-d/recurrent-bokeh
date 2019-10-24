<?php
require "../admin/cfg.php";
require "../admin/startDB.php"; // TODO: UPDATE THIS TO USE PDO

// Initialize Script Variables
$fsfcms_getSiteTitle_output = array();
$fsfcms_getSiteTitle_none   = "FSFGST-None-Found";
  
$fsfcms_getSiteTitle_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'siteTitle' LIMIT 1";
$fsfcms_getSiteTitle_result = mysql_query($fsfcms_getSiteTitle_query);
$fsfcms_getSiteTitle_row    = mysql_fetch_row($fsfcms_getSiteTitle_result);
$fsfcms_getSiteTitle_output['siteTitle'] = $fsfcms_getSiteTitle_row[0];
$fsfcms_getSiteTitle_output['status'] = 200;    // TODO: FIX THIS TO ERROR TRAP

header('Content-Type: application/json');
echo json_encode($fsfcms_getSiteTitle_output);
?>
