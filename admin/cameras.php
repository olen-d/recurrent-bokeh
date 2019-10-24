<?php

if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
if (!$fsfcms_is_logged_in)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000654";
  exit;
  }

$fsfcms_cameras_output  = "";
$fsfcms_cameras_json    = fsf_port_getCamerasList();
$fsfcms_cameras         = json_decode($fsfcms_cameras_json,true);

foreach ($fsfcms_cameras as $fsfcms_camera)
  {          
  $fsfcms_cameras_output  .=  "<div class=\"portfolio-cameras-item\"><div class=\"portfolio-cameras-text\"><div class=\"portfolio-camera-delete\"><a href=\"/admin/portfolio/cameras/deleteCamera/" . $fsfcms_camera['cameraId'] . "\" onclick=\"return confirmCameraDelete('" . $fsfcms_camera['cameraFullName'] . "')\">delete</a></div><div class=\"portfolio-camera-edit\"><a href=\"/admin/portfolio/cameras/editCamera/" . $fsfcms_camera['cameraId'] . "\">edit</a></div><span class=\"portfolio-cameras-camera-name\">" . $fsfcms_camera['cameraFullName'] . "</span><br />" . 
                              "<span class=\"portfolio-cameras-meta\">Camera ID:&nbsp;" . $fsfcms_camera['cameraId'] . "</span><br />" .
                              "<p>" . $fsfcms_camera['cameraDescription'] . "</p></div></div>";
  }
?>
<html>
  <head>
    <title>
      <?php echo $fsfcms_this_page_title; ?> 
    </title>

    <!--  STYLES          -->
    <link rel="stylesheet" href="/admin/admin-style.css" type="text/css" />
    <link rel="stylesheet" href="/admin/admin-color-schemes.css" type="text/css" />
    
    <!--  CUSTOM FONTS    -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Archivo+Narrow:400,700' rel='stylesheet' type='text/css'>

  </head>
  <body>
    <div id="wrapper">
      <div id="title-bar">
        <h1>
          <?php echo $fsfcms_breadcrumbs; ?>
        </h1>        
      </div>
      <div id="author-bar">
        <p>
          Hola, <?php echo FSFCMS_AUTHOR_USER_NAME; ?>!<!-- REM Make the username a link to the profile -->     <a href="/admin/index.php?gc=logout">Log Out</a>   
        </p>
      </div>
      <?php require "top-menu.php";
          require "cameras-menu.php";
      ?>
      <div id="page-content">    
        <?php
        if ($fsfcms_page_request_parts[3] == "newCamera")
          {
          require "portfolioNewCamera.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "addCamera") {
          require "portfolioAddCamera.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "editCamera") {
          require "portfolioEditCamera.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "updateCamera") {
          require "portfolioUpdateCamera.php";
          exit;
          }
        ?>
        <div id="portfolio-cameras"> 
          <?php echo $fsfcms_cameras_output; ?>
        </div>
      </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->
  </body>
</html>