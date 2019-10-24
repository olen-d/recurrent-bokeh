<?php
//  TODO:   Set up an option to get authors by slug

require "../../admin/cfg.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output     = array();
$authors    = array();

$fail_msg   = "No authors were found for this image. ";

$image_id   = $_GET['imageId'];

//  Get the list of authors
$query  =   "SELECT " . FSFCMS_USERS_TABLE . ".id as user_id, username, name_first, name_middle, name_last, author_slug, biography FROM " .
                        FSFCMS_AUTHORS_TABLE . " INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".user_id = " . 
                        FSFCMS_USERS_TABLE . ".id INNER JOIN " . FSFCMS_IMAGES_TABLE . " ON " . FSFCMS_AUTHORS_TABLE . ".image_parent_id = " . 
                        FSFCMS_IMAGES_TABLE . ".id WHERE " . FSFCMS_IMAGES_TABLE . ".id = ?";

try {
    $stmt   = $fsfcms_db_link->prepare($query);
    $stmt->execute(array($image_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $authors[]  =   array   (
                                authorId            =>  $row['user_id'],
                                authorSlug          =>  $row['author_slug'],
                                authorUserName      =>  $row['username'],
                                authorFirstName     =>  $row['name_first'],
                                authorMiddleName    =>  $row['name_middle'],
                                authorLastName      =>  $row['name_last'],
                                authorBio           =>  $row['biography']
                                );
    }
    $output['authors'] = $authors;
    $output['status'] = 200;
} catch(PDOException $exception) {
    $output['status'] = 500;
}        

header('Content-Type: application/json');
echo json_encode($output);
?>