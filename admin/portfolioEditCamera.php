<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
$fsfcms_camera_form_action        = "/admin/portfolio/cameras/updateCamera";
$fsfcms_camera_form_submit_value  = "Update Camera";
$fsfcms_camera_id                 = $fsfcms_page_request_parts[4];

$fsfcms_camera_info_json          = fsf_port_getCameraInfo($fsfcms_camera_id);
$fsfcms_camera_info               = json_decode($fsfcms_camera_info_json,true);
$fsfcms_camera_id                 = $fsfcms_camera_info['cameraId'];
$fsfcms_camera_manufacturer       = $fsfcms_camera_info['cameraManufacturer'];
$fsfcms_camera_model              = $fsfcms_camera_info['cameraName'];
$fsfcms_camera_slug               = $fsfcms_camera_info['cameraSlug'];
$fsfcms_camera_description        = $fsfcms_camera_info['cameraDescription'];
$fsfcms_camera_added              = $fsfcms_camera_info['cameraAdded'];

require FSFCMS_ADMIN_INCLUDE_PATH . "camera-form.php";
?>