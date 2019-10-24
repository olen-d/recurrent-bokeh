<?php
require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

//
//
//  Version:  1.0
//  Author:   Olen Daelhousen
//
//  Requires: Image id number, passed as "imageId"
//
//  Output:
//  Index
//    Keyword ID
//    Keyword
//    Keyword Slug ...

// Initialize Script Variables
$output	    = array();
$fail_msg	= "No keywords associated with the specified image were found. ";   // REM TO INTERNATIONALIZE THIS

// Initialize Get Variables
$image_id       = $_GET['imageId'];

//  Prepare the statement and access the database
$query	=   "SELECT " . FSFCMS_KEYWORDS_TABLE . ".id AS keyword_id, keyword, keyword_slug FROM " . 
            FSFCMS_KEYWORDS_TABLE . " LEFT JOIN " . FSFCMS_KEYWORDS_MAP_TABLE . " ON " . FSFCMS_KEYWORDS_TABLE . ".id = " . 
            FSFCMS_KEYWORDS_MAP_TABLE . ".keyword_id WHERE " . 
            FSFCMS_KEYWORDS_MAP_TABLE . ".image_parent_id = ? ORDER BY keyword ASC";

try {
    $stmt = $fsfcms_db_link->prepare($query);
    $stmt->execute(array($image_id));
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $keywords[]   =   array	(
                                keywordId   =>  $row['keyword_id'],
                                keywordSlug =>  $row['keyword_slug'],
                                keyword     =>  $row['keyword']
                                );
    }
    $output['keywords'] = $keywords;
    $output['status']   = 200;
    
} catch(PDOException $exception) {
    if($fsfcms_is_logged_in) {
        $output['error']    =   $exception->getMessage();
    }
    $output['error']        =   $exception->getMessage();
    $output['status']       =   500;  
}

header('Content-Type: application/json');
echo json_encode($output);
?>