<?php

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../includes/fsf_cms_access_API.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output	        = array();

$fail_msg	    = "No short link was found for this image. ";

// Initialize Get Variables
$image_id       = $_GET['imageId'];

//  Connect to the API and get the name of the short server

define(FSFCMS_API_URL, "http://www.slr680.com/api/v001/");  //  TODO - SET THIS IN CFG.PHP Also, find out where it is currently getting set.

$api_options	        = array();
$api_options['s']       = "URLShortener";
$api_options['apiFile'] = "fsf.site.getSetting.php";

$short_server_JSON	= fsf_cms_accessAPI($api_options);
$short_server	    = json_decode($short_server_JSON,TRUE);

if (array_pop($short_server) == 200) {
    $short_server_URL	= $short_server['URLShortener'];
    try {
        $query	=   "SELECT key_prefix, short_key FROM " . FSFCMS_PORT_REDIRECT_TABLE .  
                    " WHERE " . FSFCMS_PORT_REDIRECT_TABLE . ".image_id = ? LIMIT 1";
        $stmt	= $fsfcms_db_link -> prepare($query);
        $stmt->execute(array($image_id));
        $row	= $stmt->fetch(PDO::FETCH_ASSOC);

        $output['imageShortLinkURL']    = $short_server_URL . $row['key_prefix'] . "/" . $row['short_key'];
        $output['status']               = 200;  //  TODO: Error trap this better so that it doesn't return null when the DB doesn't find anything. Should be 404.
    } catch(PDOException $exception) {
        if($fsfcms_is_logged_in) {
            $output['error']    = $exception->getMessage();
        }
        $output['failMessage']  = $fail_msg;
        $output['status']       = 500;  
    }
} else {
    $output['error']    = "The name of the short server could not be found. This error is fatal. ";
    $output['status']   = 500;
}

header('Content-Type: application/json');
echo json_encode($output);
?>