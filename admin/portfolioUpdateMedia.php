<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "media-form-post.php";

// Timestamp. Note: Everything is stored in UTC and displayed according to the user set time zone.
      
$fsfcms_media_updated_datetime = date("Y-m-d H:i:s", time());      
           
// Now insert all the associated data into the DB

$fsfcms_media_update_query  = "UPDATE " . $fsfcms_media_table . 
                              " SET manufacturer = '" . $fsfcms_media_manufacturer . "', name = '" . $fsfcms_media_name . 
                              "', speed = '" . $fsfcms_media_speed . "', type = '" . $fsfcms_media_type . "', slug = '" . $fsfcms_media_slug . 
                              "' WHERE id = " . $fsfcms_media_id . " LIMIT 1";

// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfcms_media_update_query);
?>
