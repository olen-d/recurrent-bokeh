<?php
$fsfcms_st_time_start = microtime(true);

require "admin/cfg.php";                    //  THERE IS NO REASON THE FRONT SIDE SHOULD NEED CFG.PHP, FIX THIS
require "admin/startDB.php";                //  AFTER REMOVING ALL DIRECT DB CALLS (EVERYTHING NEEDS TO HIT THE API) GET RID OF THIS AS WELL
require "api/fsf_api_functions.php";        //  OK
require "includes/fsf_cms_functions.php";   //  THIS SHOULD INCLUDE ONLY CUSTOM FUNCTIONS FOR THE TEMPLATES. EVERYTHING ELSE SHOULD BE UB THE ABOVE.

$fsfcms_page_request  = trim($_SERVER['REQUEST_URI'], '/');
$fsfcms_page_request_parts        = explode("/",$fsfcms_page_request);
$fsfcms_page_request_parts_count  = count($fsfcms_page_request_parts);

$fsfcms_is_image_id   = false;
$fsfcms_is_image_id   = isset($_GET['displayImage']);

// Get the template and its associated pages
//  TODO  Consider merging all this crap into fsf_cms_getCurrentTemplate()
//  TODO  Probably one API file, but maybe roll it all into one call. Think about cases wehre all items aren't needed
$fsfcms_current_template        = fsfcms_getCurrentTemplate();
$fsfcms_templates_path          = $fsfcms_current_template['templatesPath'] . $fsfcms_current_template['currentTemplate'];

$fsfcms_current_template_id             = fsfcms_getTemplateIDbySlug($fsfcms_current_template['currentTemplate']);
$fsfcms_current_template_page_filenames = fsfcms_getTemplatePageFilenames($fsfcms_current_template_id);

// Default behavior if no path is specified i.e. http://www.slr680.com/
if($fsfcms_page_request_parts[0] == null && !$fsfcms_is_image_id)
  {           
  $fsfcms_current_template_home_page        = fsfcms_getTemplateHomePage($fsfcms_current_template_id);
  $fsfcms_current_template_home_page_status = array_pop($fsfcms_current_template_home_page);
    if($fsfcms_current_template_home_page_status == 200)
      {
      $fsfcms_current_template_home_page_filename = $fsfcms_current_template_home_page['templateHomePageFilename'];
      } else  {
      header("HTTP/1.1 500 Internal Server Error");
      echo "<h1>HTTP/1.1 500 Internal Server Error</h1><p>" . $fsfcms_current_template_home_page['errorMessage'] . "</p>";
      exit;
      }  
  include($fsfcms_templates_path . "/" . $fsfcms_current_template_home_page_filename); // REMEMBER TO SET THE DEFAULT & TEMPLATE PATH IN THE CONFIG TABLE
  }

// Check for short links
//  TODO UPDATE THIS TO PULL A LIST OF VALID SHORT KEY PREFIXES FROM FSF_CMS_PAGES AND CHECK FOR A MATCH. STEP 1 IS TO WRITE AN API CALL TO PULL SHORT KEYS PREFIXES
if($fsfcms_page_request_parts[0] == "i" || $fsfcms_page_request_parts[0] == "p")
  {
  $fsfcms_redirect = fsf_cms_expandShortURL($fsfcms_page_request_parts[0], $fsfcms_page_request_parts[1]);

  header('HTTP/1.1 301 Moved Permanently');
	header("Location: $fsfcms_redirect");
  }
  
// Check for the image template

$fsfcms_is_image_url  = false;
//$fsfcms_year_regex    = "/^\d{4}$/";  // matches 0000 to 9999
//$fsfcms_month_regex   = "/^[1-9]$|1[0-2]$/";  // matches 1 - 12, no leading 0
//$fsfcms_month_regex   = "/^(0[1-9]|1[012])$/"; // matches 00 to 12
//echo "<pre>";print_r(preg_grep("/^\d{4}$/",$fsfcms_page_request_parts)); echo "</pre>";exit;

$fsfcms_image_regex = "/\b\d{4}\/(0[1-9]|1[012])\/\w{1,}/";


$fsfcms_is_image_url  = preg_match($fsfcms_image_regex,$fsfcms_page_request);

if($fsfcms_is_image_url || $fsfcms_is_image_id) 
  {       
  include($fsfcms_templates_path . "/image.php"); // REMEMBER TO SET THE TEMPLATE PATH IN THE CONFIG TABLE
  }
elseif(array_key_exists($fsfcms_page_request_parts[0],$fsfcms_current_template_page_filenames))
  {
  include ($fsfcms_templates_path . "/" . $fsfcms_current_template_page_filenames[$fsfcms_page_request_parts[0]]);
  }
elseif($fsfcms_page_request_parts[0] == "feed")
  {
  if($fsfcms_page_request_parts[1] == "rss")  // CONSIDER MAKING THIS MESS A SWITCH
    {                       
    include("feeds/rss.php");
    } elseif($fsfcms_page_request_parts[1] == "atom")
    {
    include("feeds/atom.php");
    } else  {
    // 404
    }
  }
elseif($fsfcms_page_request_parts[0] == "image-not-found")
  {
  include($fsfcms_templates_path . "/image-not-found.php"); // REMEMBER TO SET THE TEMPLATE PATH IN THE CONFIG TABLE 
  } else  {
  // Blow Up Your Video! Error 4 - 0 - 4 
  }

$fsfcms_st_time_end = microtime(true);
$fsfcms_st_time_gen = $fsfcms_st_time_end - $fsfcms_st_time_start;
if($fsfcms_page_request_parts[0] != "feed")
  {
  if($fsfcms_st_time_gen >=1)
    {
    echo "<div id=\"page-generation\"><span class=\"upper\">The Man kept a computer down for " . round($fsfcms_st_time_gen, 2) . " seconds to generate this page. </span>";
    }
  }
?>