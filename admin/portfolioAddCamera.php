<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "camera-form-post.php";

// Timestamp. Note: Everything is stored in UTC and displayed according to the user set time zone.
      
$fsfcms_camera_added_datetime = date("Y-m-d H:i:s", time());      
           
// Now insert all the associated data into the DB

$fsfcms_camera_insert_query = "INSERT INTO " . $fsfcms_cameras_table . 
                                " (id, manufacturer, model, slug, description, camera_added)
                                VALUES                                                      
                                ('', '" . $fsfcms_camera_manufacturer . "', '" . $fsfcms_camera_model . "', '" . 
                                $fsfcms_camera_slug . "', '" . $fsfcms_camera_description . "', '" . 
                                $fsfcms_camera_added_datetime . "')";

// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfcms_camera_insert_query);
?>
