<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
$fsfcms_category_form_action        = "/admin/portfolio/categories/addCategory";
$fsfcms_category_form_submit_value  = "Add Category";
$fsfcms_category_id                 = "";
$fsfcms_category_priority           = "";
$fsfcms_category_name               = "";
$fsfcms_category_slug               = "";
$fsfcms_category_description        = "";
$fsfcms_category_added              = "";

require FSFCMS_ADMIN_INCLUDE_PATH . "category-form.php";
?>