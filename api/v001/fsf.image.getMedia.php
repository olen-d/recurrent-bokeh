<?php

//  TODO:   Set up an option to get media by image slug
//  TODO:   Add the images table so media associated with ids set to post in the future aren't shown to users who are not logged in

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

$output =   array();
$media  =   array();

$fail_msg   = "No media was found for this image. ";

$image_id   = $_GET['imageId'];

// Get all media associated with an image
$query  =   "SELECT " . FSFCMS_MEDIA_TABLE . ".id, manufacturer, name, speed, type, slug FROM " . 
                FSFCMS_MEDIA_TABLE  . " INNER JOIN " . FSFCMS_MEDIA_MAP_TABLE . " ON " . 
                FSFCMS_MEDIA_TABLE . ".id = " . FSFCMS_MEDIA_MAP_TABLE . ".media_id WHERE " . 
                FSFCMS_MEDIA_MAP_TABLE . ".image_parent_id = ?";
//echo $query;
try {
    $stmt = $fsfcms_db_link->prepare($query);
    $stmt->execute(array($image_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $media[]    =   array   (
                                mediaId             =>  $row['id'],
                                mediaSlug           =>  $row['slug'],
                                mediaManufacturer   =>  $row['manufacturer'],
                                mediaName           =>  $row['name'],
                                mediaSpeed          =>  $row['speed'],
                                mediaType           =>  $row["type"],
                                mediaFullName       =>  $row['manufacturer'] . " " .  $row['name'] . " " .  $row['speed']
                                );
    }
    $output['media'] = $media;
    $output['status'] = 200;
    
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $output['error']    =   $exception->getMessage();
    }
    $output['status']       =   500;  
}

header('Content-Type: application/json');
echo json_encode($output);
?>