<?php

require "../../admin/cfg.php";
require "../../admin/ac.php";
require "../../../../www-includes/slr680.com.includes/startDBp.php";

$image_path =   array();

try {
    $image_path_query  = "SELECT value FROM " . FSFCMS_CONFIG_TABLE . " WHERE setting = 'portImageURL' LIMIT 1";
    $image_path_stmt   = $fsfcms_db_link->query($image_path_query);
    $image_path_stmt->setFetchMode(PDO::FETCH_NUM); 
    $image_path_row    = $image_path_stmt->fetch();
    $image_path['URL']      =   $image_path_row[0];
    $image_path['status']   =   200;

}   catch(PDOException $exception) {

    if($fsfcms_is_logged_in)    {
        $image_path['error']    =   $exception->getMessage();
    }
    $image_path['status']       =   500;  
}

header('Content-Type: application/json');
echo json_encode($image_path);
?>