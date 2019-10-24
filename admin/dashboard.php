<?php

if ($fsfcms_is_logged_in)
  {
  // Get the images
  $fsfcms_port_images_json    = fsf_port_getImages("DESC",5);
  $fsfcms_port_images         = json_decode($fsfcms_port_images_json,TRUE);
  $fsfcms_port_images_status  = array_pop($fsfcms_port_images);
  } else  {
  header( 'Location: http://www.slr680.com/admin/index.php' );
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
    <?php 
      require "top-menu.php";
      require "dashboard-menu.php";
    ?>
    <div id="images-uploaded">
      <?php
      if($fsfcms_port_images_status == "200")
        {
        foreach($fsfcms_port_images as $fsfcms_port_image)
          {
          $fsfcms_port_image_thumb_json = fsf_port_getImageThumbnailByID($fsfcms_port_image['id']);
          $fsfcms_port_image_thumb      = json_decode($fsfcms_port_image_thumb_json,TRUE);

          echo "<div class=\"images-uploaded-item\">";          
          echo "<a href=\"/admin/portfolio/editImage/" . $fsfcms_port_image['id'] . "\"><img src=\"" . $fsfcms_port_image_thumb['thumbnailURL'] . "\" width=\"" . $fsfcms_port_image_thumb['thumbnailWidth'] . "\" height=\"" . $fsfcms_port_image_thumb['thumbnailHeight']. "\" /></a>";
          echo "<div class=\"images-uploaded-text\"><span class=\"images-uploaded-title\">" . $fsfcms_port_image['title'] . "</span><br />";
          echo "<span class=\"images-uploaded-meta\">" . $fsfcms_port_image['postedDateLong'] . " at " . $fsfcms_port_image['postedTime12Hour'] . "</span><br />";
          echo "<span class=\"images-uploaded-meta\">" . $fsfcms_port_image['authorFirstName'] . " " . $fsfcms_port_image['authorLastName'] . "</span>";
          echo "<p>" . $fsfcms_port_image['caption'] . "</p>";
          echo "<p><span class=\"images-uploaded-meta\">" . $fsfcms_port_image['cameraFullName'] . "</span><br />";
          echo "<span class=\"images-uploaded-meta\">" . $fsfcms_port_image['mediaName'] . "</p></div></div>";
          }
        }
       ?>           
    </div>
    <div id="image-statistics">
      <div class="image-number">
        <?php  echo fsfcms_getTotalImagesNumber(); ?>
      </div>
      <div class="image-number-text">
        Total Images
       </div>
      <div class="image-number">
        <?php  echo fsfcms_getPublishedImagesNumber(); ?>
      </div>
      <div class="image-number-text">
        Published
      </div>        
    </div>  <!-- End Image Statistics -->
    <div id="server-statistics">
      <div class="server-number">
        <?php
          $fsfcms_CPUload = fsfcms_status_CPUload();
          echo round($fsfcms_CPUload[0]*100,0) . "%";
        ?>
      </div>
      <div class="server-number-text">
        CPU Load
       </div>
        <div class="server-number">
        <?php
          $fsfcms_RAM = fsfcms_status_RAM();
          echo round($fsfcms_RAM['used']/$fsfcms_RAM['total']*100,0) . "%";
        ?>
      </div>
      <div class="server-number-text">
        Memory Used
       </div>
        <div class="server-number">
        <?php
          echo round($fsfcms_RAM['swapUsed']/$fsfcms_RAM['swapTotal']*100,0) . "%";
        ?>
      </div>
      <div class="server-number-text">
        Swap Used
       </div>      
       <div class="server-number">
        <?php
          $fsfcms_IO = fsfcms_status_IO();
          echo round($fsfcms_IO['iowait'],0) . "%";
        ?>
      </div>
      <div class="server-number-text">
        IO Wait
       </div>
      </div>  <!-- End Server Statistics -->    
    <div id="db-statistics">
      <div class="db-number">
        <?php
          $fsfcms_DB = fsfcms_status_DB();
          echo round($fsfcms_DB['threadsConnected'] / $fsfcms_DB['maxConnections'] * 100,0) . "%";
        ?>
      </div>
      <div class="db-number-text">
        DB Connections
       </div>
        <div class="db-number">
        <?php
          echo $fsfcms_DB['slowQueries'];
        ?>
      </div>
      <div class="db-number-text">
        Slow Queries
       </div>
       </div> <!-- End DB Statistics -->
  </div>  <!-- End Wrapper -->
</body>
</html>             
