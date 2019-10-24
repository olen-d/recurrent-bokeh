<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
$fsfcms_media_form_action         = "/admin/portfolio/media/addMedia";
$fsfcms_media_form_submit_value   = "Add Media";
$fsfcms_media_id                  = "";
$fsfcms_media_manufacturer        = "";
$fsfcms_media_name                = "";
$fsfcms_media_speed               = "";
$fsfcms_media_type                = "";
$fsfcms_media_slug                = "";
$fsfcms_media_added               = "";
?>
<script type='text/javascript'>
  <!-- BEGIN
  function mediaToSlug()
    {
    var mediaSlug         = "";
    var manufacturerField = document.getElementById('mediaManufacturer');
    var nameField         = document.getElementById('mediaName');
    var speedField        = document.getElementById('mediaSpeed');
    var slugField         = document.getElementById('mediaSlug');
  
    var mediaSlug = manufacturerField.value + " " + nameField.value + " " + speedField.value;
    var mediaSlug = mediaSlug.trim();
    var mediaSlug = mediaSlug.replace(/[^\w\s\-]/g, '')                                             // Remove punctuation except dashes
    .trim()
    .replace(/\s+/g, '-')                                                                           // Change spaces to dashes
    .replace(/\-\-+/g, '-')                                                                         // Replace multiple dashes with a single dash
    .toLowerCase()
    .substring(0,64)
    .trim();

    slugField.value  = mediaSlug; 
    }
  // End -->
</script>
<?php require FSFCMS_ADMIN_INCLUDE_PATH . "media-form.php"; ?>