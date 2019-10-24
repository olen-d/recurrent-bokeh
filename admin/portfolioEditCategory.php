<?php
if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
$fsfcms_category_form_action        = "/admin/portfolio/categories/updateCategory";
$fsfcms_category_form_submit_value  = "Update Category";
$fsfcms_category_id                 = $fsfcms_page_request_parts[4];

$fsfcms_category_info_json          = fsf_port_getCategoryInfo($fsfcms_category_id);
$fsfcms_category_info               = json_decode($fsfcms_category_info_json,true);
$fsfcms_category_id                 = $fsfcms_category_info['categoryId'];
$fsfcms_category_priority           = $fsfcms_category_info['categoryPriority'];
$fsfcms_category_name               = $fsfcms_category_info['categoryName'];
$fsfcms_category_slug               = $fsfcms_category_info['categorySlug'];
$fsfcms_category_description        = $fsfcms_category_info['categoryDescription'];
$fsfcms_category_added              = $fsfcms_category_info['categoryAdded'];

require FSFCMS_ADMIN_INCLUDE_PATH . "category-form.php";
?>