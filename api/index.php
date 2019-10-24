<?php

$fsfcms_is_logged_in  = FALSE;

require "../admin/ac.php";
require "../admin/admin-functions.php";
require "../admin/cfg.php";
require "../admin/ground-control.php";
require "../admin/startDB.php";
require "fsf_api_functions.php";
require "../includes/fsf_cms_functions.php";

$fsfcms_page_request        = trim($_SERVER['REQUEST_URI'], '/');
$fsfcms_page_request_parts  = explode("/",$fsfcms_page_request);

define("FSFCMS_THIS_SITE_TITLE",fsfcms_getSiteTitle());
define("FSFCMS_THIS_SITE_URL",fsfcms_getSiteURL());

// Set up some general template variables

$fsfcms_this_page_title = FSFCMS_THIS_SITE_TITLE . " / Application Programming Interface (API)";

// Set up the breadcrumbs and the author bar
$fsfcms_breadcrumbs = "<a href=\"" . FSFCMS_THIS_SITE_URL . "\">" . FSFCMS_THIS_SITE_TITLE . "</a>"; 



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <title>
    <?php echo $fsfcms_this_page_title; ?>  
  </title>

  <!--  STYLES          -->
  <link rel="stylesheet" href="../admin/admin-style.css" type="text/css" />
  <link rel="stylesheet" href="../admin/admin-color-schemes.css" type="text/css" />
  <link rel="stylesheet" href="api-style.css" type="text/css" />  
  <!--  JAVASCRIPT      -->
		<script type='text/javascript'>
			<!-- BEGIN

      // 

      


			// End -->
		</script>
  <!--  CUSTOM FONTS    -->
  <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
  <link href='http://fonts.googleapis.com/css?family=Archivo+Narrow:400,700' rel='stylesheet' type='text/css' />
  
</head>
<body onload="document.getElementById('apiAccessForm').nickname.focus();">
<div id="wrapper">
<div id="main-content">
  <h1>
    <?php echo $fsfcms_breadcrumbs; ?> Application Programming Interface (API)
  </h1>
  <div id="api-form-container">
      <form id="apiAccessForm" method="post" action="requestApiAccess">
        <div id="api-form-user-info">
          <div class="form-field">
            <label for="nickname">Application Name</label><br />
            <input name="applicationName" id="applicationName" type="text" value="" size="30" />
          </div>
          <div class="form-field">
            <label for="companyName">Company Name</label><br />
            <input name="companyName" id="companyName" type="text" value="" size="30" />
          </div>
          <div class="form-field">
            <label for="siteUrl">Website URL</label><br />
            <input name="siteUrl" id="siteUrl" type="text" value="" size="30" />
          </div>   
          <div class="form-field">
            <label for="contactEmail">Contact Email</label><br />
            <input name="contactEmail" id="contactEmail" type="text" value="" size="30" />
          </div> 
        </div>
        <div id="login-form-checkbox">
          <input name="rememberUser" id="rememberUser" type="checkbox" <?php echo $remember_user_default; ?> />
          <label for="rememberUser">Remember my user name</label>
        </div>
        <p class="submit">
          <input type="submit" class="button" name="submit" value="register" />
        </p>
      </form>
  </div>  <!-- End Login Form Container -->
  <?php echo ($fsfcms_authentication_fail_flag == 1 ? $what_formatted : "");  ?>  
</div>    <!-- End Main Content -->
</div>    <!-- End Wrapper -->
</body>
</html>