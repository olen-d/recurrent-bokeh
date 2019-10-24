<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
$fsfcms_camera_form_action        = "/admin/portfolio/cameras/addCamera";
$fsfcms_camera_form_submit_value  = "Add Camera";
$fsfcms_camera_id                 = "";
$fsfcms_camera_manufacturer       = "";
$fsfcms_camera_model              = "";
$fsfcms_camera_slug               = "";
$fsfcms_camera_description        = "";
$fsfcms_category_added            = "";
?>
<script type='text/javascript'>
  <!-- BEGIN
  function cameraToSlug()
    {
    var cameraSlug        = "";
    var manufacturerField = document.getElementById('cameraManufacturer');
    var modelField        = document.getElementById('cameraModel');
    var cameraSlugField   = document.getElementById('cameraSlug');
  
    var cameraSlug = manufacturerField.value + " " + modelField.value;
    var cameraSlug = cameraSlug.trim();
    var cameraSlug = cameraSlug.replace(/[^\w\s\-]/g, '')                                           // Remove punctuation
    .trim()
    .replace(/\s+/g, '-')                                                                           // Change spaces to dashes
    .replace(/\-\-+/g, '-')                                                                         // Replace multiple dashes with a single dash
    .toLowerCase()
    .substring(0,64)
    .trim();

    cameraSlugField.value  = cameraSlug; 
    }
  // End -->
</script>
<?php require FSFCMS_ADMIN_INCLUDE_PATH . "camera-form.php"; ?>