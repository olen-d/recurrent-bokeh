<?php

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output	    = array();

$fail_msg	= "The clean URL for the image was not found. ";

// Initialize Get Variables
$image_id	= $_GET['imageId'];

try {
    $query	= "SELECT YEAR(".   FSFCMS_IMAGES_TABLE . ".post) AS imageYear, DATE_FORMAT(" . 
                                FSFCMS_IMAGES_TABLE . ".post,'%m') AS imageMonth, title_slug FROM " . 
                                FSFCMS_IMAGES_TABLE . " WHERE (" . FSFCMS_IMAGES_TABLE . ".id = ?) LIMIT 1";
    $stmt	= $fsfcms_db_link -> prepare($query);
    $stmt->execute(array($image_id));
    $row	= $stmt->fetch(PDO::FETCH_ASSOC);

    $output['imageLink']    = $row['imageYear'] . "/" . $row['imageMonth'] . "/" . $row['title_slug'];
    $output['status']       = 200;  //  TODO: Error trap this better so that it doesn't return null when the DB doesn't find anything. Should be 404.
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $output['error']    = $exception->getMessage();
    }
    $output['failMessage']  = $fail_msg;
    $output['status']       = 500;  
}

header('Content-Type: application/json');
echo json_encode($output);
?>