<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

$insert = "INSERT INTO " . FSFCMS_CAMERAS_MAP_TABLE . "(id,image_parent_id,camera_id) VALUES ";

//  Set up the DB queries

$query  =   "SELECT id, camera_id FROM " . FSFCMS_IMAGES_TABLE . " ORDER BY id ASC";
$result =   mysql_query($query);

if(mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        //echo "<p>" . $row['id'] . " " . $row['camera_id'] . "</p>";
        $insert .= "(''," . $row['id'] . "," . $row['camera_id'] . "),";
    }
}

$insert = rtrim($insert,",");
$result2 = mysql_query($insert);

//echo "<p>" . $insert . "</p>";
echo "<p>Script completed.</p>";
?>