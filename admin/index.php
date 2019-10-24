<?php

$fsfcms_is_logged_in  = FALSE;

require "ac.php";
require "admin-functions.php";
require "cfg.php";
require "ground-control.php";
require "startDB.php";
require "../api/fsf_api_functions.php";
require "../includes/fsf_cms_functions.php";

$fsfcms_page_request        = trim($_SERVER['REQUEST_URI'], '/');
$fsfcms_page_request_parts  = explode("/",$fsfcms_page_request);

if ($fsfcms_is_logged_in)
  {
  if ($fsfp_ground_control == "logout")
    {
    $_SESSION = array();
    if (ini_get("session.use_cookies"))
      {
      $fsfcms_params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $fsfcms_params["path"], $fsfcms_params["domain"], $fsfcms_params["secure"], $fsfcms_params["httponly"]);
      }
      session_destroy();
      setcookie("fsfcms_login", "", time() - 42000);   
    } else  {
    setcookie("fsfcms_login", "yes", time() + 3600, "/");
    $fsfcms_is_logged_in  = TRUE;
    }
  }

// Set up some useful constants
define("FSFCMS_USER_ID",$_SESSION['fsfcms_s_internal_id']);

$fsfcms_author_info  = fsfcms_getAuthorInfoByID(FSFCMS_USER_ID);

define("FSFCMS_ADMIN_INCLUDE_PATH",fsfcms_admin_get_admin_include_path());
define("FSFCMS_AUTHOR_USER_NAME",$fsfcms_author_info['authorUserName']);
define("FSFCMS_THIS_SITE_TITLE",fsfcms_getSiteTitle());
define("FSFCMS_THIS_SITE_URL",fsfcms_getSiteURL());

// Set up some general template variables

$fsfcms_this_page_title = FSFCMS_THIS_SITE_TITLE . " / Administration";

// Set up the breadcrumbs and the author bar
$fsfcms_breadcrumbs = "<a href=\"" . FSFCMS_THIS_SITE_URL . "\">" . FSFCMS_THIS_SITE_TITLE . "</a>"; 

// Default to the dashboard if no pages are specified
if (count($fsfcms_page_request_parts) < 2)
  {
  $fsfcms_breadcrumbs     .=  " &raquo; Administration";
  require "dashboard.php";
  exit;
  } 
      
if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "login")
  {
  require "login.php";   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "dashboard")
  {
  $fsfcms_breadcrumbs     .=  " &raquo; Administration";
  require "dashboard.php"; 
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "content" && $fsfcms_page_request_parts[2] == "publications")
  {
  $fsfcms_this_page_title .=  " / Content / Publications";  
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/content\">Content</a> &raquo Publications";
  require "content.php";
  exit;   
  }
  
if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "content")
  {
  $fsfcms_this_page_title .=  " / Content";  
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; Content";
  require "content.php";
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "portfolio" && $fsfcms_page_request_parts[2] == "media")
  {
  $fsfcms_this_page_title .=  " / Portfolio / Media";
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/portfolio\">Portfolio</a> &raquo; Media";
  require "media.php";
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "portfolio" && $fsfcms_page_request_parts[2] == "cameras")
  {
  $fsfcms_this_page_title .=  " / Portfolio / Cameras";
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/portfolio\">Portfolio</a> &raquo; Cameras";
  require "cameras.php";
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "portfolio" && $fsfcms_page_request_parts[2] == "categories")
  {
  $fsfcms_this_page_title .=  " / Portfolio / Categories";
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/portfolio\">Portfolio</a> &raquo; Categories";
  require "categories.php";
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "portfolio")
  {
  $fsfcms_this_page_title .=  " / Portfolio";
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; Portfolio";
  require "portfolio.php";
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "users")
  {
  $fsfcms_this_page_title .=  " / Users";
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; Users";
  require "users.php";
  exit;   
  }

if ($fsfcms_page_request_parts[0] == "admin" && $fsfcms_page_request_parts[1] == "options")
  {
  $fsfcms_this_page_title .=  " / Options";
  $fsfcms_breadcrumbs     .=  " &raquo; <a href=\"" . FSFCMS_THIS_SITE_URL . "admin/dashboard\">Administration</a> &raquo; Options";
  require "options.php";
  exit;   
  }

// Setup the form, yo
$fsfcms_rem_user = $HTTP_COOKIE_VARS['fsfcms_remember_user'];
if ($fsfcms_rem_user == "yes")
  {
  $user_name_default      = $HTTP_COOKIE_VARS['fsfcms_remember_user_name'];
  $remember_user_default  = "checked";
  $login_focus            = "pass";
  } else  {
  $user_name_default      = "";
  $remember_user_default  = "";
  $login_focus            = "userName";
  }

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
  <link rel="stylesheet" href="/admin/admin-style.css" type="text/css" />
  <link rel="stylesheet" href="/admin/admin-color-schemes.css" type="text/css" />
  
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
<body onload="document.getElementById('loginForm').<?php echo $login_focus; ?>.focus();">
<div id="wrapper">
<div id="main-content">
  <h1>
    <?php echo $fsfcms_breadcrumbs; ?> Administration
  </h1>
  <div id="login-form-container">
      <form id="loginForm" method="post" action="login">
        <div id="login-form-user-info">
          <div class="form-field">
            <label for="userName">User Name</label><br />
            <input name="userName" id="userName" type="text" value="<?php echo $user_name_default; ?>" size="30" />
          </div>
          <div class="form-field">
            <label for="pass">Password</label><br />
            <input name="pass" id="pass" type="password" value="" size="30" />
          </div>      
        </div>
        <div id="login-form-checkbox">
          <input name="rememberUser" id="rememberUser" type="checkbox" <?php echo $remember_user_default; ?> />
          <label for="rememberUser">Remember my user name</label>
        </div>
        <p class="submit">
          <input type="submit" class="button" name="submit" value="login" />
        </p>
      </form>
  </div>  <!-- End Login Form Container -->
  <?php echo ($fsfcms_authentication_fail_flag == 1 ? $what_formatted : "");  ?>  
</div>    <!-- End Main Content -->
</div>    <!-- End Wrapper -->
</body>
</html>