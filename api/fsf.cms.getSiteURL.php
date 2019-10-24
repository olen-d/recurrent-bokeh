<?php
require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output	    = array();
$fail_msg	= "The site URL was not found.";

try {
    $query	= "SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = 'siteURL' LIMIT 1";
    $stmt	= $fsfcms_db_link -> query($query);
    $row	= $stmt->fetch();

    $output['siteURL']  = $row[0];
    $output['status']   = 200;  //  TODO: Error trap this better so that it doesn't return null when the DB doesn't find anything. Should be 404.
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $output['error']    =   $exception->getMessage();
    }
    $output['status']       =   500;  
}

header('Content-Type: application/json');
echo json_encode($output);
?>