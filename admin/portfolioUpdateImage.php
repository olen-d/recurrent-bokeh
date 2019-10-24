<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

$fsfcms_admin_this_page       = "portfolioUpdateImage";
//
//
// REMEMBER TO ADD ABILITY TO REUPLOAD AN IMAGE AND ALSO UPDATE THE SHORTLINK! //Don't forget to TRIM all this shit as well
//
//

//
require "portfolio-functions.php";

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "image-form-post.php";
require FSFCMS_ADMIN_INCLUDE_PATH . "image-form-validate.php";

if (count($fsfifv_errors) <= 0)
  {
  $fsfcms_image_update_query = "UPDATE " . $fsfcms_images_table . " SET title='" . $fsfp_image_title . "', title_slug='" . $fsfp_image_title_slug . "', caption='" . $fsfp_image_caption . "', camera_id='" . $fsfp_image_camera . "', media_id=" . $fsfp_image_media . ", post='" . $fsfp_image_post_datetime . "', comment_status='" . $fsfp_image_comments . "' WHERE id = " . $fsfp_image_id . " LIMIT 1";

  mysql_query($fsfcms_image_update_query);

          //
          //
          // REMEMBER TO UPDATE THE SHORT LINK HERE!
          //
          //

  // Update the authors - the author defaults to whatever user is currently logged in
  // REMEMBER TO FIGURE OUT [1] WHO CAN CHANGE AUTHORS AND [2] HOW TO IMPLEMENT THAT FUNCTIONALITY.

  // Delete the old authors
  $fsfcms_delete_authors_query = "DELETE FROM " . $fsfcms_authors_table . " WHERE image_parent_id ="  . $fsfp_image_id;
  mysql_query($fsfcms_delete_authors_query);
          
  // Now perform the insert to the authors table, if no author is selected, the default is whoever is currently logged in
      
  $fsfp_image_authors_insert_values = "";
  if (count($fsfp_image_authors) < 1)
    {
    $fsfp_image_authors[] = $fsfcms_userID;   //  Default to the currently logged in author if no checkbox is selected
    }
      foreach ($fsfp_image_authors as $fsfp_image_author)
        {
        $fsfp_image_author_trimmed = trim($fsfp_image_author);
        $fsfp_image_authors_insert_values .= "('', " . $fsfp_image_id . ", '" . $fsfp_image_author_trimmed . "'),";
        }
        
      // Important - drop the last comma off the string
      $fsfp_image_authors_insert_values = rtrim($fsfp_image_authors_insert_values, ",");
      
      // Finally, insert the values into the DB
      $fsfp_insert_authors_query = "INSERT INTO " . $fsfcms_authors_table . " VALUES " . $fsfp_image_authors_insert_values;
      mysql_query($fsfp_insert_authors_query);





  
          //  Update the categories
          
          //  REMEMBER to figure out if anything changed and then do an update
            // Delete the old categories
            $fsfcms_delete_categories_query = "DELETE FROM " . $fsfcms_categories_table . " WHERE parent_id ="  . $fsfp_image_id;
            mysql_query($fsfcms_delete_categories_query);
          
          // Now perform the insert to the categories table, if no category is selected, the default is "Uncatagorized"
      
      $fsfp_image_categories_insert_values = "";
      if (count($fsfp_image_categories) < 1)
        {
        $fsfp_image_categories[] = $fsfp_default_category_id;
        }
      foreach ($fsfp_image_categories as $fsfp_image_category)
        {
        $fsfp_image_category_trimmed = trim($fsfp_image_category);
        $fsfp_image_categories_insert_values .= "('', " . $fsfp_image_id . ", '" . $fsfp_image_category_trimmed . "'),";
        }
        
      // Important - drop the last comma off the string
      $fsfp_image_categories_insert_values = rtrim($fsfp_image_categories_insert_values, ",");
      
      // Finally, insert the values into the DB
      $fsfp_insert_categories_query = "INSERT INTO " . $fsfcms_categories_table . " VALUES " . $fsfp_image_categories_insert_values;
      mysql_query($fsfp_insert_categories_query);            
          
//
//  KEYWORDS
//

          if($fsfp_image_keywords_original != $fsfp_image_keywords)
            {                   
                                    
            //  Delete the old keywords from the map table
            $fsfcms_delete_keywords_query = "DELETE FROM " . FSFCMS_KEYWORDS_MAP_TABLE . " WHERE image_parent_id ="  . $fsfp_image_id;
            mysql_query($fsfcms_delete_keywords_query);    
      
            $fsfp_keywords_array    = processKeywords($fsfp_image_keywords);
            $fsfp_existing_keywords = getExistingKeywords($fsfp_keywords_array);
            $fsfp_new_keywords      = array_diff($fsfp_keywords_array,$fsfp_existing_keywords);
            if(count($fsfp_new_keywords) > 0)
              {   
              insertNewKeywords($fsfp_new_keywords);     
              }
            $fsfp_updated_keywords  = getExistingKeywords($fsfp_keywords_array);                                     
            insertKeywordsMap($fsfp_updated_keywords,$fsfp_image_id );
            }
  } else  {
  $fsfcms_image_post_unix_ts      = $fsfp_image_post_unix_ts;
  $fsfcms_image_form_action       = "/admin/portfolio/updateImage";
  //$fsfcms_image_form_on_submit    = "return validateNewImageForm();";
  $fsfcms_image_form_on_submit    = "";
  $fsfcms_image_form_submit_value = "Update Image";
  
  $fsfcms_image_id  = $fsfp_image_id;

  //  Retrieve whatever the user did manage to fill out
  $fsfcms_image_title           = $fsfp_image_title;
  $fsfcms_image_title_slug      = $fsfp_image_title_slug;
  $fsfcms_image_keywords_output = $fsfp_image_keywords;
  $fsfcms_image_caption         = $fsfp_image_caption;

  // Build the list of authors and preselect the image authors

  $fsfcms_image_users_json      = fsfcms_cms_getUsersList();
  $fsfcms_image_users           = json_decode($fsfcms_image_users_json,true);
  $fsfcms_image_users_status    = array_pop($fsfcms_image_users);

  if($fsfcms_image_users_status == 200)
    {
    $fsfcms_author_checkbox_id             = 0;

    if (count($fsfp_image_authors) <= 0)
      {
      $fsfp_image_authors = array();
      }
      
    foreach($fsfcms_image_users as $fsfcms_image_user)
      {
      if($fsfcms_image_user['userName'] != "SuperAdministrator")  //  Skip the SuperAdministrator
        {
        $fsfcms_image_authors_box_checked = "";
        (in_array($fsfcms_image_user['userId'],$fsfp_image_authors) ? $fsfcms_image_authors_box_checked = "checked=\"checked\"" : $fsfcms_image_authors_box_checked = "");
        $fsfcms_image_authors_output .= "<input type=\"checkbox\" name=\"imageAuthors[]\" id=\"author" . $fsfcms_author_checkbox_id . "\" value=\"" . $fsfcms_image_user['userId'] . "\" " . $fsfcms_image_authors_box_checked . "/><label for=\"author" . $fsfcms_author_checkbox_id . "\">" . $fsfcms_image_user['firstName'] . "&nbsp;" . $fsfcms_image_user['lastName'] . "</label><br />";
        $fsfcms_author_checkbox_id++;
        }
      }  
    } else  {
    // TODO: No Authors Found! Put together some sort of reasonable error & output
    }
         
  // Get the categories
  if (count($fsfp_image_categories) > 0)
    {
    foreach($fsfp_image_categories as $fsfp_image_category_id)
      {
      $fsfp_image_categories_output[$fsfp_image_category_id] = "checked=\"checked\" ";    
      }
    }

  $fsfcms_image_categories_json    = fsf_port_getCategoriesList();
  $fsfcms_image_categories         = json_decode($fsfcms_image_categories_json,true);
  $fsfcms_category_checkbox_id     = 0;

  foreach($fsfcms_image_categories as $fsfcms_image_category)
    {
    $fsfcms_image_categories_box_checked = "";
    $fsfcms_image_categories_box_checked = $fsfp_image_categories_output[$fsfcms_image_category['categoryId']];
    $fsfcms_image_categories_output .= "<input type=\"checkbox\" name=\"imageCategories[]\" id=\"category" . $fsfcms_category_checkbox_id . "\" value=\"" . $fsfcms_image_category['categoryId'] . "\" " . $fsfcms_image_categories_box_checked . "/><label for=\"category" . $fsfcms_category_checkbox_id . "\">" . $fsfcms_image_category['categoryName'] . "</label><br />";
    $fsfcms_category_checkbox_id++;
    }

  // Generate the list of cameras
  $fsfcms_image_cameras_json   = fsf_port_getCamerasList();
  $fsfcms_image_cameras        = json_decode($fsfcms_image_cameras_json,true);

  foreach($fsfcms_image_cameras as $fsfcms_image_camera)
    {
    ($fsfp_image_camera == $fsfcms_image_camera['cameraId'] ? $fsfcms_image_camera_item_selected = " selected" : $fsfcms_image_camera_item_selected = "");
    $fsfcms_image_cameras_output .= "<option value=\"" . $fsfcms_image_camera['cameraId'] . "\"" . $fsfcms_image_camera_item_selected . ">" . $fsfcms_image_camera['cameraFullName'] . "</option>";
    }

  // Generate the list of media
  $fsfcms_image_media_list_json   = fsf_port_getMediaList();
  $fsfcms_image_media_list        = json_decode($fsfcms_image_media_list_json,true);
 
  foreach($fsfcms_image_media_list as $fsfcms_image_media)
    {
    ($fsfp_image_media == $fsfcms_image_media['mediaId'] ? $fsfcms_image_media_item_selected = " selected" : $fsfcms_image_media_item_selected = "");
    $fsfcms_image_media_output .= "<option value=\"" . $fsfcms_image_media['mediaId'] . "\"" . $fsfcms_image_media_item_selected . ">" . $fsfcms_image_media['mediaManufacturer'] . " " . $fsfcms_image_media['mediaName'] . " " . $fsfcms_image_media['mediaSpeed'] . "</option>";
    }
  
  require FSFCMS_ADMIN_INCLUDE_PATH . "image-form.php";
  }                
?>
</div>  <!--  End Page Content  -->