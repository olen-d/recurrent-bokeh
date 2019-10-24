<?php

//  TODO:   Set up an option to cameras by image slug
//  TODO:   Add the images table so cameras associated with ids set to post in the future aren't shown to users who are not logged in

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

$output     = array();
$cameras    = array();

$fail_msg   = "No cameras were found for this image. ";

$image_id   = $_GET['imageId'];

// Get all cameras associated with an image
$query  =   "SELECT " . FSFCMS_CAMERAS_TABLE . ".id, manufacturer, model, slug, description FROM " . 
                FSFCMS_CAMERAS_TABLE  . " INNER JOIN " . FSFCMS_CAMERAS_MAP_TABLE . " ON " . 
                FSFCMS_CAMERAS_TABLE . ".id = " . FSFCMS_CAMERAS_MAP_TABLE . ".camera_id WHERE " . 
                FSFCMS_CAMERAS_MAP_TABLE . ".image_parent_id = ?";
//echo $query;
try {
    $stmt = $fsfcms_db_link->prepare($query);
    $stmt->execute(array($image_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cameras[]  =   array   (
                                cameraId            =>  $row['id'],
                                cameraSlug          =>  $row['slug'],
                                cameraManufacturer  =>  $row['manufacturer'],
                                cameraModel         =>  $row['model'],
                                cameraFullName      =>  $row['manufacturer'] . " " .  $row['model'],
                                cameraDescription   =>  $row['description']
                                );
    }
    $output['cameras']  = $cameras;
    $output['status']   = 200;
    
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $output['error']    =   $exception->getMessage();
    }
    $output['status']       =   500;  
}

header('Content-Type: application/json');
echo json_encode($output);
?>