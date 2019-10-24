<?php
require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

// Initialize Script Variables
$output	    = array();
$categories = array();

$fail_msg   = "No categories were found for this image. ";

// Initialize Get Variables
$image_id	= $_GET['imageId'];

$query	=   "SELECT "   .   FSFCMS_IMAGES_TABLE . ".title, " . FSFCMS_CATEGORIES_MAP_TABLE . ".category_id, " . 
                            FSFCMS_CATEGORY_NAMES_TABLE . ".category_name, " . FSFCMS_CATEGORY_NAMES_TABLE . ".category_slug FROM " . 
                            FSFCMS_IMAGES_TABLE . ", " . FSFCMS_CATEGORIES_MAP_TABLE . ", " . FSFCMS_CATEGORY_NAMES_TABLE . 
                            " WHERE " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_MAP_TABLE . ".parent_id AND " . 
                            FSFCMS_CATEGORIES_MAP_TABLE . ".category_id = " . FSFCMS_CATEGORY_NAMES_TABLE . ".id AND " . 
                            FSFCMS_IMAGES_TABLE . ".id = ? ORDER BY " . FSFCMS_CATEGORY_NAMES_TABLE . ".category_name ASC";

try {
    $stmt = $fsfcms_db_link->prepare($query);
    $stmt->execute(array($image_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[]   =   array	(
                                    categoryId	    =>  $row['category_id'],
                                    categorySlug	=>  $row['category_slug'],
                                    categoryName	=>  $row['category_name']
                                    );
    }
    $output['categories']   = $categories;
    $output['status']       = 200;
    
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $output['error']    =   $exception->getMessage();
    }
    $output['error']    =   $exception->getMessage();
    $output['status']       =   500;  
}

header('Content-Type: application/json');
echo json_encode($output);
?>