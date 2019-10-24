<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getServerTimeZone_output           = array();

// Set up the DB queries
$fsfcms_getServerTimeZone_query  = "SELECT value FROM " 
                                    . $fsfcms_config_table . " WHERE setting = 'serverTimeZone' LIMIT 1";

$fsfcms_getServerTimeZone_result = mysql_query($fsfcms_getServerTimeZone_query);
$fsfcms_getServerTimeZone_row    = mysql_fetch_row($fsfcms_getServerTimeZone_result);

$fsfcms_getServerTimeZone_output['serverTimeZoneName']    = $fsfcms_getServerTimeZone_row[0];
$fsfcms_getServerTimeZone_timeZoneServer  = new DateTimeZone($fsfcms_getServerTimeZone_row[0]);
$fsfcms_getServerTimeZone_dateTimeServer  = new DateTime("now", $fsfcms_getServerTimeZone_timeZoneServer);
$fsfcms_getServerTimeZone_offset = $fsfcms_getServerTimeZone_dateTimeServer->format("Z")/3600;
$fsfcms_getServerTimeZone_output['serverTimeZoneOffset']  = $fsfcms_getServerTimeZone_offset;

header('Content-Type: application/json');
echo json_encode($fsfcms_getServerTimeZone_output);

?>