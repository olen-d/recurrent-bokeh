<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "category-form-post.php";

// Timestamp. Note: Everything is stored in UTC and displayed according to the user set time zone.
      
$fsfcms_category_added_datetime = date("Y-m-d H:i:s", time());      
           
// Now insert all the associated data into the DB

$fsfcms_category_insert_query = "INSERT INTO " . $fsfcms_category_names_table . 
                                " (id, category_priority, category_name, category_slug, category_description, category_added)
                                VALUES                                                      
                                ('', '" . $fsfcms_category_priority . "', '" . $fsfcms_category_name . "', '" . 
                                $fsfcms_category_slug . "', '" . $fsfcms_category_description . "', '" . 
                                $fsfcms_category_added_datetime . "')";

// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfcms_category_insert_query);
?>
