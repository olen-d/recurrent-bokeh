<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "media-form-post.php";

// Timestamp. Note: Everything is stored in UTC and displayed according to the user set time zone.
      
$fsfcms_media_added_datetime = date("Y-m-d H:i:s", time());      
           
// Now insert all the associated data into the DB

$fsfcms_media_insert_query = "INSERT INTO " . $fsfcms_media_table . 
                                " (id, manufacturer, name, speed, type, slug, media_added)
                                VALUES                                                      
                                ('', '" . $fsfcms_media_manufacturer . "', '" . $fsfcms_media_name . "', '" . 
                                $fsfcms_media_speed . "', '" . $fsfcms_media_type . "', '" . $fsfcms_media_slug . "', '" .
                                $fsfcms_media_added_datetime . "')";

// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfcms_media_insert_query);
?>
