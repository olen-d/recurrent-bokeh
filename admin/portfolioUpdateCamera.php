<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "camera-form-post.php";

// Timestamp. Note: Everything is stored in UTC and displayed according to the user set time zone.
      
$fsfcms_camera_updated_datetime = date("Y-m-d H:i:s", time());      
           
// Now insert all the associated data into the DB

$fsfcms_camera_update_query = "UPDATE " . $fsfcms_cameras_table . 
                                " SET manufacturer = '" . $fsfcms_camera_manufacturer . "', model = '" . $fsfcms_camera_model . 
                                "', slug = '" . $fsfcms_camera_slug . "', description = '" . $fsfcms_camera_description . "' WHERE id = " .
                                $fsfcms_camera_id . " LIMIT 1";

// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfcms_camera_update_query);
?>
