<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getHeaderContent_output           = array();
$fsfcms_getHeaderContent_all_output       = array();
$fsfcms_getHeaderContent_where_clause = "";

// Initialize Get Variables
$fsfcms_getHeaderContent_page         = $_GET['page'];

// Set up the DB queries
$fsfcms_getHeaderContent_all_query  = "SELECT " . $fsfcms_header_content_table . ".id, content, post FROM " 
                                    . $fsfcms_header_content_table . " WHERE page = 'all' LIMIT 1";

$fsfcms_getHeaderContent_all_result = mysql_query($fsfcms_getHeaderContent_all_query);
$fsfcms_getHeaderContent_all_row    = mysql_fetch_row($fsfcms_getHeaderContent_all_result);

$fsfcms_getHeaderContent_all_output['id']       = $fsfcms_getHeaderContent_all_row[0];
$fsfcms_getHeaderContent_all_output['content']  = $fsfcms_getHeaderContent_all_row[1];
$fsfcms_getHeaderContent_all_output['post']     = $fsfcms_getHeaderContent_all_row[2];

$fsfcms_getHeaderContent_ouput['all'] = $fsfcms_getHeaderContent_all_output;

header('Content-Type: application/json');
echo json_encode($fsfcms_getHeaderContent_ouput);

?>