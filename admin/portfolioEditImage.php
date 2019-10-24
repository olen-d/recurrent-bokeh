 <?php

if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
if (!$fsfcms_is_logged_in)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000654";
  exit;
  }

$fsfcms_image_form_action       = "/admin/portfolio/updateImage";
$fsfcms_image_form_submit_value = "Update Image";
$fsf_port_getImage_parameters   = array();

if ($fsfcms_page_request_parts[3] == "getImage")
  {
  $fsfcms_specific_image_value  = $_POST['edit-specific-image-value'];
  $fsfcms_is_permalink_regex    = "/\b\d{4}\/(0[1-9]|1[012])\/[a-zA-Z0-9-]+/";
  $fsfcms_is_permalink          = preg_match($fsfcms_is_permalink_regex,$fsfcms_specific_image_value,$fsfcms_permalink_matches,PREG_OFFSET_CAPTURE);
  if ($fsfcms_is_permalink)
    {
    $fsfcms_permalink           = $fsfcms_permalink_matches[0][0];
    $fsfmcs_permalink_parts     = explode("/",$fsfcms_permalink);
    
    $fsf_port_getImage_parameters['lookup']       = "URL";
    $fsf_port_getImage_parameters['yearMonth']    = $fsfmcs_permalink_parts[0] . $fsfmcs_permalink_parts[1];
    $fsf_port_getImage_parameters['slug']         = $fsfmcs_permalink_parts[2];    
    } elseif (is_numeric($fsfcms_specific_image_value)) {
    $fsf_port_getImage_parameters['lookup']       = "id";
    $fsf_port_getImage_parameters['imageId']      = $fsfcms_specific_image_value;
    }
  } else  {
  $fsf_port_getImage_parameters['lookup']         = "id";
  $fsf_port_getImage_parameters['imageId']        = $fsfcms_page_request_parts[3];
  }

//  Get the image data

$fsfcms_image_data_json         = fsf_port_getImage($fsf_port_getImage_parameters);
$fsfcms_image_data              = json_decode($fsfcms_image_data_json,true);
$fsfcms_image_id                = $fsfcms_image_data['id'];      
$fsfcms_image_title             = $fsfcms_image_data['title'];
$fsfcms_image_title_slug        = $fsfcms_image_data['titleSlug'];
$fsfcms_image_caption           = trim($fsfcms_image_data['caption']);
$fsfcms_image_authors           = $fsfcms_image_data['authors'];
$fsfcms_image_camera_slug       = $fsfcms_image_data['cameraSlug']; 
$fsfcms_image_media_slug        = $fsfcms_image_data['mediaSlug'];
$fsfcms_image_post              = $fsfcms_image_data['postedDate'];
$fsfcms_image_post_unix_ts      = $fsfcms_image_data['postedDateUnixTimestamp'];

// Build the list of authors and preselect the image authors

$fsfcms_image_users_json      = fsfcms_cms_getUsersList();
$fsfcms_image_users           = json_decode($fsfcms_image_users_json,true);
$fsfcms_image_users_status    = array_pop($fsfcms_image_users);

if($fsfcms_image_users_status == 200)
  {
  $fsfcms_author_checkbox_id             = 0;

  foreach($fsfcms_image_users as $fsfcms_image_user)
    {
    if($fsfcms_image_user['userName'] != "SuperAdministrator")  //  Skip the SuperAdministrator
      {
      $fsfcms_image_authors_box_checked = "";
      (/*$fsfcms_image_user_id == $fsfcms_image_authors['userId']*/ array_key_exists($fsfcms_image_user['userId'],$fsfcms_image_authors) ? $fsfcms_image_authors_box_checked = "checked=\"checked\"" : $fsfcms_image_authors_box_checked = "");
      $fsfcms_image_authors_output .= "<input type=\"checkbox\" name=\"imageAuthors[]\" id=\"author" . $fsfcms_author_checkbox_id . "\" value=\"" . $fsfcms_image_user['userId'] . "\" " . $fsfcms_image_authors_box_checked . "/><label for=\"author" . $fsfcms_author_checkbox_id . "\">" . $fsfcms_image_user['firstName'] . "&nbsp;" . $fsfcms_image_user['lastName'] . "</label><br />";
      $fsfcms_author_checkbox_id++;
      }
    }  
  } else  {
  // TODO: No Authors Found! Put together some sort of reasonable error & output
  }
         
// Get the categories for the image
//  TODO: Update $fsfcms_image_assoc_categories to error trap
$fsfcms_image_assoc_categories   = fsf_port_getImageCategoriesNoLinks($fsfcms_image_id);

  foreach($fsfcms_image_assoc_categories as $fsfcms_image_assoc_category)
    {
    $fsfcms_image_assoc_categories_output[$fsfcms_image_assoc_category['categoryId']] = "checked=\"checked\" ";    
    }

  $fsfcms_image_categories_json    = fsf_port_getCategoriesList();
  $fsfcms_image_categories         = json_decode($fsfcms_image_categories_json,true);
  $fsfcms_category_checkbox_id     = 0;

if(array_pop($fsfcms_image_categories) == 200)
  {
  foreach($fsfcms_image_categories as $fsfcms_image_category)
    {
    $fsfcms_image_categories_box_checked = "";
    $fsfcms_image_categories_box_checked = $fsfcms_image_assoc_categories_output[$fsfcms_image_category['categoryId']];
    $fsfcms_image_categories_output .= "<input type=\"checkbox\" name=\"imageCategories[]\" id=\"category" . $fsfcms_category_checkbox_id . "\" value=\"" . $fsfcms_image_category['categoryId'] . "\" " . $fsfcms_image_categories_box_checked . "/><label for=\"category" . $fsfcms_category_checkbox_id . "\">" . $fsfcms_image_category['categoryName'] . "</label><br />";
    $fsfcms_category_checkbox_id++;
    }
  } else  {
  //  Categories failed
  //  TODO: Report error
  //  TODO: Offer recovery path
  }

// Get the keywords for the image
$fsfcms_image_keywords     = fsf_port_getImageKeywordsNoLinks($fsfcms_image_id);

foreach($fsfcms_image_keywords as $fsfcms_image_keyword)
  {
  $fsfcms_image_keywords_output .= $fsfcms_image_keyword['keyword'] . ", ";    
  }
$fsfcms_image_keywords_output = rtrim($fsfcms_image_keywords_output,", ");

// Generate the list of cameras
$fsfcms_image_cameras_json   = fsf_port_getCamerasList();
$fsfcms_image_cameras        = json_decode($fsfcms_image_cameras_json,true);

foreach($fsfcms_image_cameras as $fsfcms_image_camera)
  {
  ($fsfcms_image_camera_slug == $fsfcms_image_camera['cameraSlug'] ? $fsfcms_image_camera_item_selected = " selected" : $fsfcms_image_camera_item_selected = "");
  $fsfcms_image_cameras_output .= "<option value=\"" . $fsfcms_image_camera['cameraId'] . "\"" . $fsfcms_image_camera_item_selected . ">" . $fsfcms_image_camera['cameraFullName'] . "</option>";
  }

// Generate the list of media
$fsfcms_image_media_list_json   = fsf_port_getMediaList();
$fsfcms_image_media_list        = json_decode($fsfcms_image_media_list_json,true);

foreach($fsfcms_image_media_list as $fsfcms_image_media)
  {
  ($fsfcms_image_media_slug == $fsfcms_image_media['mediaSlug'] ? $fsfcms_image_media_item_selected = " selected" : $fsfcms_image_media_item_selected = "");
  $fsfcms_image_media_output .= "<option value=\"" . $fsfcms_image_media['mediaId'] . "\"" . $fsfcms_image_media_item_selected . ">" . $fsfcms_image_media['mediaManufacturer'] . " " . $fsfcms_image_media['mediaName'] . " " . $fsfcms_image_media['mediaSpeed'] . "</option>";
  }
?>
		<script type='text/javascript'>
			<!-- BEGIN
function titleToSlug()
  {
  var titleSlug       = "";
  var titleField      = document.getElementById('imageTitle');
  var titleSlugField  = document.getElementById('imageTitleSlug');
  
  var titleSlug = titleField.value;
  var titleSlug = titleSlug.trim();
  var titleSlug = titleSlug.replace(/\b(a|after|although|an|and|as|at|be|both|but|by|from|for|if|in|nor|of|on|or|over|so|the|though|to|up|via|when|while|would|yet)\b/gi,'')
  .replace(/[^\w\s]/g, '')                                                                        // Remove punctuation
  .trim()
  .replace(/\s+/g, '-')                                                                           // Change spaces to dashes
  .replace(/\-\-+/g, '-')                                                                         // Replace multiple dashes with a single dash
  .toLowerCase()
  .substring(0,64)
  .trim();

  titleSlugField.value  = titleSlug; 
  }

function onSpecificDateChange()
  {
  var specificDateRadioButton = document.getElementById('radio6');
  specificDateRadioButton.checked = true;
  }

			// End -->
		</script>
<?php require FSFCMS_ADMIN_INCLUDE_PATH . "image-form.php"; ?>