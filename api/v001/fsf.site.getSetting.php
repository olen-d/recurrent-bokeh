<?php

//  Dependancies:   fsf.image.getShortLink

// TODO:    FIX URLShortener setting in the DB

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output	    = array();
$fail_flag	= false;

$setting   = $_GET['s'];

switch ($setting) {
    case "siteTitle":
        define(SETTING_NAME, "siteTitle");
        $output_key	= "siteTitle";
        $fail_msg	= "The site title was not found.";
        break;
    case "siteURL":
        define(SETTING_NAME, "siteURL");
        $output_key	= "siteURL";
        $fail_msg	= "The site URL was not found.";
        break;
    case "imageURL":
        define(SETTING_NAME, "imageURL");
        $output_key	= "imageURL";
        $fail_msg	= "The image URL was not found.";
        break;
    case "URLShortener":
        define(SETTING_NAME, "siteURLshortenerURL");
        $output_key = "URLShortener";
        $fail_msg	= "The URL shortener was not found. ";
        break;
    default:
        $fail_flag	            = true;
        $output['failMessage']  = "Something went terribly wrong.";
        $output['error']        = "Bad request. The server could not complete the request due to invalid request message framing. Please check that 's' corresponds to a valid setting.";
        $output['status']       = 400;
}

if (!$fail_flag) {
    try {
        $query	= "SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = ? LIMIT 1";
        $stmt	= $fsfcms_db_link -> prepare($query);
        $stmt->execute(array(SETTING_NAME));
        $row	= $stmt->fetch();

        $output[$output_key]    = $row[0];
        $output['status']       = 200;  //  TODO: Error trap this better so that it doesn't return null when the DB doesn't find anything. Should be 404.
    } catch(PDOException $exception) {
        if($fsfcms_is_logged_in) {
            $output['error']    = $exception->getMessage();
        }
        $output['failMessage']  = $fail_msg;
        $output['status']       = 500;  
    }
}

header('Content-Type: application/json');
echo json_encode($output);
?>