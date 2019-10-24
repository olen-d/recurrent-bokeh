<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

$fsfcms_image_post_unix_ts      = time();
$fsfcms_image_form_action       = "/admin/portfolio/uploadImage";
//$fsfcms_image_form_on_submit    = "return validateNewImageForm();";
$fsfcms_image_form_on_submit  = "";
$fsfcms_image_form_submit_value = "Upload Image";  

$fsfcms_image_id              = "";
$fsfcms_image_keywords_output = "";
$fsfcms_image_title           = "";
$fsfcms_image_title_slug      = "";
$fsfcms_image_caption         = "";

// Build the list of categories form

$fsfcms_image_categories_json     = fsf_port_getCategoriesList();
$fsfcms_image_categories          = json_decode($fsfcms_image_categories_json,true);
$fsfcms_category_checkbox_id          = 0;

if(array_pop($fsfcms_image_categories) == 200)
  {
  foreach($fsfcms_image_categories as $fsfcms_image_category)
    {
    $fsfcms_image_categories_output .=  "<input type=\"checkbox\" name=\"imageCategories[]\" id=\"category" . $fsfcms_category_checkbox_id . "\" value=\"" . $fsfcms_image_category['categoryId'] . "\"/><label for=\"category" . $fsfcms_category_checkbox_id . "\">" . $fsfcms_image_category['categoryName'] . "</label><br />";
    $fsfcms_category_checkbox_id++;
    }
  }

// Build the list of authors and preselect the person currently logged in

$fsfcms_image_users_json      = fsfcms_cms_getUsersList();
$fsfcms_image_users           = json_decode($fsfcms_image_users_json,true);
$fsfcms_image_users_status    = array_pop($fsfcms_image_users);

if($fsfcms_image_users_status == 200)
  {
  $fsfcms_author_checkbox_id            = 0;

  foreach($fsfcms_image_users as $fsfcms_image_user)
    {
    $fsfcms_image_authors_box_checked = "";
    if($fsfcms_image_user['userName'] != "SuperAdministrator")  //  Skip the SuperAdministrator
      {
      (FSFCMS_USER_ID == $fsfcms_image_user['userId'] ? $fsfcms_image_authors_box_checked = "checked=\"checked\"" : $fsfcms_image_authors_box_checked = "");
      $fsfcms_image_authors_output .= "<input type=\"checkbox\" name=\"imageAuthors[]\" id=\"author" . $fsfcms_author_checkbox_id . "\" value=\"" . $fsfcms_image_user['userId'] . "\" " . $fsfcms_image_authors_box_checked . "/><label for=\"author" . $fsfcms_author_checkbox_id . "\">" . $fsfcms_image_user['firstName'] . "&nbsp;" . $fsfcms_image_user['lastName'] . "</label><br />";
      $fsfcms_author_checkbox_id++;
      }
    }
  }
// Generate the list of cameras
$fsfcms_image_cameras_json   = fsf_port_getCamerasList();
$fsfcms_image_cameras        = json_decode($fsfcms_image_cameras_json,true);

$fsfcms_image_cameras_output = "<option value=\"-99\">Choose a camera...</option>";
foreach($fsfcms_image_cameras as $fsfcms_image_camera)
  {
  $fsfcms_image_cameras_output .= "<option value=\"" . $fsfcms_image_camera['cameraId'] . "\">" . $fsfcms_image_camera['cameraFullName'] . "</option>";
  }

// Generate the list of media
$fsfcms_image_media_list_json   = fsf_port_getMediaList();
$fsfcms_image_media_list        = json_decode($fsfcms_image_media_list_json,true);

$fsfcms_image_media_output  = "<option value=\"-99\">Choose media...</option>"; 
foreach($fsfcms_image_media_list as $fsfcms_image_media)
  {
  $fsfcms_image_media_output .= "<option value=\"" . $fsfcms_image_media['mediaId'] . "\">" . $fsfcms_image_media['mediaManufacturer'] . " " . $fsfcms_image_media['mediaName'] . " " . $fsfcms_image_media['mediaSpeed'] . "</option>";
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

function validateNewImageForm()
  {
  var valid = true;
  
  var fileInput = document.getElementById('imageFile');
  if(fileInput.value == ""  || fileInput.value.length == 0)
    {
    valid = false;
    document.getElementById('imageFileLabel').style.color='#ff0000';
    }
  
  var imageTitleInput       = document.getElementById('imageTitle');
  var imageTitleInputValue  = imageTitleInput.value.trim();
  if(imageTitleInputValue == ""  || imageTitleInputValue.length == 0)
    {
    valid = false;
    document.getElementById('imageTitleLabel').style.color='#ff0000';
    }

  //  TODO: Upgrade the validation to actually check for a valid slug string, eg. all lowercase, no spaces or special characters.
  var imageTitleSlugInput       = document.getElementById('imageTitleSlug');
  var imageTitleSlugInputValue  = imageTitleSlugInput.value.trim();
  if(imageTitleSlugInputValue == ""  || imageTitleSlugInputValue.length == 0)
    {
    valid = false;
    document.getElementById('imageTitleSlugLabel').style.color='#ff0000';
    }

  var authorChecked = false;
  var authorsInput  = document.getElementsByName('imageAuthors[]');
  var totalAuthors  = authorsInput.length;

  for(var i = 0;i < totalAuthors; i++)
    {
    if(authorsInput[i].checked)
      {
      authorChecked  = true;
      break;
      }
    }
    if(!authorChecked)
      {
      document.getElementById('imageAuthorsLabel').style.color='#ff0000';
      valid = false;
      }
  
  var imageCameraInput  = document.getElementById('imageCamera');
  if(imageCameraInput.options[imageCameraInput.selectedIndex].value == -99)
    {
    document.getElementById('imageCameraLabel').style.color='#ff0000';
    valid = false;
    }

  var imageMediaInput  = document.getElementById('imageMedia');
  if(imageMediaInput.options[imageMediaInput.selectedIndex].value == -99)
    {
    document.getElementById('imageMediaLabel').style.color='#ff0000';
    valid = false;
    }

  return valid;
  }

			// End -->
		</script>
<?php require FSFCMS_ADMIN_INCLUDE_PATH . "image-form.php"; ?>