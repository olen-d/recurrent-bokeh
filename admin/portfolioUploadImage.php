<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

$fsfcms_admin_this_page       = "portfolioUploadImage";
$fsfp_image_uploaded_datetime = date("Y-m-d H:i:s", time());

//
require "portfolio-functions.php";

//  Get the contents of the form
require FSFCMS_ADMIN_INCLUDE_PATH . "image-form-post.php";
require FSFCMS_ADMIN_INCLUDE_PATH . "image-form-validate.php";

if (count($fsfifv_errors) <= 0)
  {

  // Upload the file
  if (isset($_FILES))
    {
    // TODO: Move this to an include or function, since portfolio update image also uses it
    $fsfp_target_path             =   fsfcms_getPortImagePath();
    $fsfp_target_path             .=  $fsfp_uploaded_image_filename;
    $fsfp_thumbs_path             =   fsfcms_getPortThumbsPath();
    if (move_uploaded_file($_FILES['imageFile']['tmp_name'],$fsfp_target_path))
      {
      chmod($fsfp_target_path, 0644);
      }
    } elseif($fsfcms_admin_this_page == "portfolioUploadImage") {
    echo "Fatal Error: No file was provided.";
    exit;
    } 

  // Now insert all the associated data into the DB

  // $fsfp_insert_query  = "INSERT INTO " . $fsfcms_images_table . 
  //                       "(id, filename, title, title_slug, caption, camera_id, media_id, uploaded, post, comment_status)
  //                       VALUES
  //                       ('', '" . $fsfp_uploaded_image_filename . "', '" . $fsfp_image_title . "', '" .$fsfp_image_title_slug . "', '" . 
  //                       $fsfp_image_caption . "', " . $fsfp_image_camera . ", " . $fsfp_image_media . ", '" . 
  //                       $fsfp_image_uploaded_datetime . "', '" . $fsfp_image_post_datetime . "', '" . $fsfp_image_comments . "')";
  
    $fsfp_insert_query  = "INSERT INTO " . $fsfcms_images_table . 
                        "(id, filename, title, title_slug, caption, uploaded, post, comment_status)
                        VALUES
                        ('', '" . $fsfp_uploaded_image_filename . "', '" . $fsfp_image_title . "', '" .$fsfp_image_title_slug . "', '" . 
                        $fsfp_image_caption . "', '" . $fsfp_image_uploaded_datetime . "', '" . $fsfp_image_post_datetime . "', '" . 
                        $fsfp_image_comments . "')";

  // REMEMBER TO ERROR TRAP THIS
  mysql_query($fsfp_insert_query);
  $fsfp_image_parent_id = mysql_insert_id();

  // Now perform the insert to the author table - the author defaults to whatever user is currently logged in
  // REMEMBER TO FIGURE OUT [1] WHO CAN CHANGE AUTHORS AND [2] HOW TO IMPLEMENT THAT FUNCTIONALITY.
  $fsfp_image_authors_insert_values = "";
  if (count($fsfp_image_authors) < 1)
    {
    $fsfp_image_authors[] = $fsfcms_userID;   //  Default to the currently logged in author if no checkbox is selected
    }
  foreach ($fsfp_image_authors as $fsfp_image_author)
    {
    $fsfp_image_author_trimmed = trim($fsfp_image_author);
    $fsfp_image_authors_insert_values .= "('', " . $fsfp_image_parent_id . ", " . $fsfp_image_author_trimmed . "),";
    }
        
  // Important - drop the last comma off the string
  $fsfp_image_authors_insert_values = rtrim($fsfp_image_authors_insert_values, ",");             
  $fsfp_insert_authors_query = "INSERT INTO " . $fsfcms_authors_table . " VALUES " . $fsfp_image_authors_insert_values;
  mysql_query($fsfp_insert_authors_query);
         
  // Now perform the insert to the categories table, if no category is selected, the default is "Uncatagorized"
      
  $fsfp_image_categories_insert_values = "";
  if (count($fsfp_image_categories) < 1)
    {
    $fsfp_image_categories[] = $fsfp_default_category_id;
    }
  foreach ($fsfp_image_categories as $fsfp_image_category)
    {
    $fsfp_image_category_trimmed = trim($fsfp_image_category);
    $fsfp_image_categories_insert_values .= "('', " . $fsfp_image_parent_id . ", '" . $fsfp_image_category_trimmed . "'),";
    }
        
  // Important - drop the last comma off the string
  $fsfp_image_categories_insert_values = rtrim($fsfp_image_categories_insert_values, ",");
      
  // Finally, insert the values into the DB
  $fsfp_insert_categories_query = "INSERT INTO " . $fsfcms_categories_table . " VALUES " . $fsfp_image_categories_insert_values;
  mysql_query($fsfp_insert_categories_query);      
      
//
//  KEYWORDS
//
//  Requires:
//    1. portfolio-functions.php
//
  
  $fsfp_keywords_array    = processKeywords($fsfp_image_keywords);
  $fsfp_existing_keywords = getExistingKeywords($fsfp_keywords_array);   
  $fsfp_new_keywords      = array_diff($fsfp_keywords_array,$fsfp_existing_keywords);

  if(count($fsfp_new_keywords) > 0)
    {
    insertNewKeywords($fsfp_new_keywords);
    }
  $fsfp_updated_keywords  = getExistingKeywords($fsfp_keywords_array);     //echo "<p>Doritos: ";print_r($fsfp_updated_keywords);                               
  insertKeywordsMap($fsfp_updated_keywords,$fsfp_image_parent_id );
     
  // Now make the thumbnail
  fsfcms_make_image_thumbnail($fsfp_target_path, $fsfp_thumbs_path . "thumb_" . $fsfp_uploaded_image_filename);      

  // Make the short link    $fsfp_image_parent_id
  $fsfcms_make_image_shortlink_result_json  = fsf_admin_port_makeImageShortLinkCleanURL($fsfp_image_parent_id);
  $fsfcms_make_image_shortlink_result       = json_decode($fsfcms_make_image_shortlink_result_json,true);                 

  // echo "Error: " . $_FILES["imageFile"]["error"];
  } else  {
  $fsfcms_image_post_unix_ts      = $fsfp_image_post_unix_ts;
  $fsfcms_image_form_action       = "/admin/portfolio/uploadImage";
  //$fsfcms_image_form_on_submit    = "return validateNewImageForm();";
  $fsfcms_image_form_on_submit    = "";
  $fsfcms_image_form_submit_value = "Upload Image";

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

  $fsfcms_image_cameras_output = "<option value=\"-99\">Choose a camera...</option>";
  foreach($fsfcms_image_cameras as $fsfcms_image_camera)
    {
    ($fsfp_image_camera == $fsfcms_image_camera['cameraId'] ? $fsfcms_image_camera_item_selected = " selected" : $fsfcms_image_camera_item_selected = "");
    $fsfcms_image_cameras_output .= "<option value=\"" . $fsfcms_image_camera['cameraId'] . "\"" . $fsfcms_image_camera_item_selected . ">" . $fsfcms_image_camera['cameraFullName'] . "</option>";
    }

  // Generate the list of media
  $fsfcms_image_media_list_json   = fsf_port_getMediaList();
  $fsfcms_image_media_list        = json_decode($fsfcms_image_media_list_json,true);

  $fsfcms_image_media_output  = "<option value=\"-99\">Choose media...</option>"; 
  foreach($fsfcms_image_media_list as $fsfcms_image_media)
    {
    ($fsfp_image_media == $fsfcms_image_media['mediaId'] ? $fsfcms_image_media_item_selected = " selected" : $fsfcms_image_media_item_selected = "");
    $fsfcms_image_media_output .= "<option value=\"" . $fsfcms_image_media['mediaId'] . "\"" . $fsfcms_image_media_item_selected . ">" . $fsfcms_image_media['mediaManufacturer'] . " " . $fsfcms_image_media['mediaName'] . " " . $fsfcms_image_media['mediaSpeed'] . "</option>";
    }
  
  require FSFCMS_ADMIN_INCLUDE_PATH . "image-form.php";
  }
?>
</div>  <!--  End Page Content  -->