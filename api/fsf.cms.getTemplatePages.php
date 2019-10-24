<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTemplatePages_output           = array();

$fsfcms_getTemplatePages_template_id  = $_GET['templateId'];

// Set up the DB queries
$fsfcms_getTemplatePages_query  = "SELECT id, page_name, page_slug, page_filename FROM " . $fsfcms_pages_table . " WHERE template_id = " . $fsfcms_getTemplatePages_template_id . " AND visible = 'visible' ORDER BY page_name ASC";
$fsfcms_getTemplatePages_result = mysql_query($fsfcms_getTemplatePages_query);

if($fsfcms_getTemplatePages_result)
  {
  if(mysql_num_rows($fsfcms_getTemplatePages_result) > 0)
    {
    while($fsfcms_getTemplatePages_row = mysql_fetch_assoc($fsfcms_getTemplatePages_result))
      {
      $fsfcms_getTemplatePages_output[] = array(
                                            pageId        =>  $fsfcms_getTemplatePages_row['id'],
                                            pageName      =>  $fsfcms_getTemplatePages_row['page_name'],
                                            pageSlug      =>  $fsfcms_getTemplatePages_row['page_slug'],
                                            pageFilename  =>  $fsfcms_getTemplatePages_row['page_filename'],
                                            );
      }
    $fsfcms_getTemplatePages_output['status'] = 200;
    } else  {
    $fsfcms_getTemplatePages_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
    $fsfcms_getTemplatePages_output['status']        = 404; 
    }
  } else  {
  $fsfcms_getTemplatePages_output['errorMessage']  = "Request could not be completed because of a database error.";
  $fsfcms_getTemplatePages_output['status']        = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getTemplatePages_output);
?>