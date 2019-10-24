<?php

require "../admin/ac.php";

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDB.php";

  // Initialize Script Variables
  $fsfcms_getTemplates_output           = array();

  // Set up the DB queries
  $fsfcms_getTemplates_query  = "SELECT id, template_name, template_slug FROM " . $fsfcms_templates_table;
  $fsfcms_getTemplates_result = mysql_query($fsfcms_getTemplates_query);

  if($fsfcms_getTemplates_result)
    {
    if(mysql_num_rows($fsfcms_getTemplates_result) > 0)
      {
      while($fsfcms_getTemplates_row = mysql_fetch_assoc($fsfcms_getTemplates_result))
        {
        $fsfcms_getTemplates_output[] = array(
                                              templateId    =>  $fsfcms_getTemplates_row['id'],
                                              templateName  =>  $fsfcms_getTemplates_row['template_name'],
                                              templateSlug  =>  $fsfcms_getTemplates_row['template_slug'],
                                              );
        }
      } else  {
      // fail
      }
    } else  {
    //fail
    }

  header('Content-Type: application/json');
  echo json_encode($fsfcms_getTemplates_output);
  } else  {
  header("HTTP/1.0 403 Forbidden"); // Remember to update this to 401 Unauthorized, which is actually correct.
  echo "<h1>HTTP/1.0 403 Forbidden</h1><p>You do not have permission to access this content. Please log in and try again. </p>";  // Remember to update this to point the user to the correct login page.  
  }
?>