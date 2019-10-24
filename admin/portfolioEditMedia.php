<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
$fsfcms_media_form_action        = "/admin/portfolio/media/updateMedia";
$fsfcms_media_form_submit_value  = "Update Media";
$fsfcms_media_id                 = $fsfcms_page_request_parts[4];
$fsfcms_media_info_json          = fsf_port_getMediaInfo($fsfcms_media_id);
$fsfcms_media_info               = json_decode($fsfcms_media_info_json,true);
$fsfcms_media_id                 = $fsfcms_media_info['mediaId'];
$fsfcms_media_manufacturer       = $fsfcms_media_info['mediaManufacturer'];
$fsfcms_media_name              = $fsfcms_media_info['mediaName'];
$fsfcms_media_speed             = $fsfcms_media_info['mediaSpeed'];
$fsfcms_media_type              = $fsfcms_media_info['mediaType'];
$fsfcms_media_slug               = $fsfcms_media_info['mediaSlug'];
$fsfcms_media_added              = $fsfcms_media_info['mediaAdded'];
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