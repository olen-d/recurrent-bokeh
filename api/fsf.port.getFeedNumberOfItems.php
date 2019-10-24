<?php
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getFeedNumberOfItems_output = array();
$fsfcms_getFeedNumberOfItems_none   = "FSFGSIFP-None-Found";
  
$fsfcms_getFeedNumberOfItems_query = "SELECT value FROM " . $fsfcms_config_table . " WHERE setting = 'feedItems' LIMIT 1";
$fsfcms_getFeedNumberOfItems_result = mysql_query($fsfcms_getFeedNumberOfItems_query);
$fsfcms_getFeedNumberOfItems_row    = mysql_fetch_row($fsfcms_getFeedNumberOfItems_result);
$fsfcms_getFeedNumberOfItems_output[] = $fsfcms_getFeedNumberOfItems_row[0];

header('Content-Type: application/json');
echo json_encode($fsfcms_getFeedNumberOfItems_output);
?>
