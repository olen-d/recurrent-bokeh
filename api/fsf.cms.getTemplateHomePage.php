<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getTemplateHomePage_output           = array();

if(isset($_GET['templateId']))
  {
  $fsfcms_getTemplateHomePage_template_id = $_GET['templateId'];
  // Remember to check that the template ID is a valid number and sanitize it.
   
  // Set up the DB queries
  $fsfcms_getTemplateHomePage_query  = "SELECT page_filename FROM " 
                                      . $fsfcms_pages_table . " INNER JOIN " . $fsfcms_config_table . 
                                      " ON " . $fsfcms_pages_table . ".page_slug = " . $fsfcms_config_table . 
                                      ".value WHERE setting = 'templateHomePage' AND template_id = " . $fsfcms_getTemplateHomePage_template_id . " LIMIT 1";

  $fsfcms_getTemplateHomePage_result = mysql_query($fsfcms_getTemplateHomePage_query);
  if($fsfcms_getTemplateHomePage_result)
    {
    if(mysql_num_rows($fsfcms_getTemplateHomePage_result) > 0)
      { 
    $fsfcms_getTemplateHomePage_row    = mysql_fetch_row($fsfcms_getTemplateHomePage_result);

    $fsfcms_getTemplateHomePage_output['templateHomePageFilename']    = $fsfcms_getTemplateHomePage_row[0];
    $fsfcms_getTemplateHomePage_output['status']  = 200;
      } else {
      $fsfcms_getTemplateHomePage_output['errorMessage']  = "Request could not be completed because no results were found in the database.";
      $fsfcms_getTemplateHomePage_output['status']        = 404;      
      }
    } else  {
    $fsfcms_getTemplateHomePage_output['errorMessage']  = "Request could not be completed because of a database error.";
    $fsfcms_getTemplateHomePage_output['status']        = 500;    
    }
  } else  {
  $fsfcms_getTemplateHomePage_output['errorMessage']  = "Request could not be completed because the template ID was not supplied.";
  $fsfcms_getTemplateHomePage_output['status']        = 500;  
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getTemplateHomePage_output);
?>