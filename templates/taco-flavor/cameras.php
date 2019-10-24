<?php

$fsfcms_this_page  = "cameras";

if(count($fsfcms_page_request_parts) > 1)
  {
  $fsfcms_camera_slug       = $fsfcms_page_request_parts[1];
  $fsfcms_camera_info_json  = fsf_port_getCameraInfoBySlug($fsfcms_camera_slug);
  $fsfcms_camera_info       = json_decode($fsfcms_camera_info_json,true);
  $fsfcms_camera_name       = $fsfcms_camera_info['cameraFullName']; 
  $fsfcms_camera_flag     = 1;
  } else  {
  $fsfcms_camera_flag     = 0;
  }
		  
?>
<?php
if(!@include "declarations.php")
  {
  echo "<html>";
	echo "<head>";
	}
?>
		<title>
			<?php echo ($fsfcms_camera_flag == 1 ? $fsfcms_camera_name : "Cameras"); ?> &#149; <?php echo fsfcms_getSiteTitle(); ?>
		</title>
    <!-- CUSTOM FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Neuton:300,400' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css' />



		<!--  STYLES        -->
    <link rel="stylesheet" href="/templates/taco-flavor/site-style.css" type="text/css" />

		<!--  JAVASCRIPT    -->    
		<script type='text/javascript'>
			<!-- BEGIN

			// End -->
		</script>

	</head>
	<body>
	 <div id="wrapper">
    <div id="site-name-box">
  		<h1 class="site-name">
	 	   	polaroid <span class="site-name-contrast"><a href="<?php echo fsfcms_getSiteURL(); ?>">slr 680</a></span>
	   	</h1>
	   	<p>
			The penultimate example of SX-70 technology.
		  </p>
	  </div>
    <div id="content">
      <?php echo fsf_cms_getHeaderContent($fsfcms_this_page); ?>
		</div>
		<div id="<?php echo ($fsfcms_camera_flag == 1 ? "thumbnails" : "cameras"); ?>">
      <?php
        if($fsfcms_camera_flag == 1)
          {         
          $cameras_paged = fsfcms_getSiteGlobalPaged();
          if ($cameras_paged == "no")
            {
            echo fsf_port_getCameraImageThumbnails($fsfcms_camera_slug,"","");
            } elseif($cameras_paged == "yes")  {
            $cameras_current_page = 1;
            $camera_slug   = $fsfcms_page_request_parts[1];
            if($fsfcms_page_request_parts[2] == "page")
              {
              if(isset($fsfcms_page_request_parts[3]))
                {                    
                $cameras_current_page = $fsfcms_page_request_parts[3];
                }
              }
            if($cameras_current_page < (fsfcms_getTotalImagesNumberByCamera($fsfcms_camera_slug)/fsfcms_getSiteGlobalItemsPerPage()))
              {
              $cameras_prev_page_link = "<div id=\"cameras-prev-page\"><a href=\"/" . $fsfcms_this_page . "/" . $camera_slug . "/page/" . ($cameras_current_page + 1) . "\">&laquo; previous posts</a></div>";
              } else  {
              $cameras_prev_page_link = "";
              }
            if($cameras_current_page != 1)
              {
              $cameras_next_page_link = "<div id=\"cameras-next-page\"><a href=\"/" . $fsfcms_this_page . "/" . $camera_slug . "/page/" . ($cameras_current_page - 1) . "\">next posts &raquo;</a></div>";
              } else  {
              $cameras_next_page_link = "";
              }
            echo fsf_port_getCameraImageThumbnails($fsfcms_camera_slug,$cameras_current_page,fsfcms_getSiteGlobalItemsPerPage());            
            echo "<div id=\"cameras-paged-nav\">" . $cameras_prev_page_link;
            echo $cameras_next_page_link . "</div>";
            }
          } else  {          
          $fsfcms_cameras_json  = fsf_port_getCamerasCleanURL();
          $fsfcms_cameras       = json_decode($fsfcms_cameras_json,true);
                            
          // Loop through the list of cameras
          foreach($fsfcms_cameras as $fsfcms_camera)
            {
            $fsfcms_camera_full_name      = $fsfcms_camera['cameraFullName'];
            $fsfcms_camera_count          = $fsfcms_camera['cameraCount'];
            $fsfcms_camera_slug           = $fsfcms_camera['cameraSlug'];
            $fsfcms_camera_clean_URL      = $fsfcms_camera['cameraCleanURL'];
            
            $fsfcms_camera_image_ids_json = fsf_port_getCameraImageIDs($fsfcms_camera_slug);
            $fsfcms_camera_image_ids      = json_decode($fsfcms_camera_image_ids_json);
              $fsfcms_camera_images_counter = 0;
              $fsfcms_camera_images_max     = 3;
              foreach($fsfcms_camera_image_ids as $fsfcms_camera_image_id)
              {
              $fsfcms_camera_images_counter++;
              if ($fsfcms_camera_images_counter > $fsfcms_camera_images_max)
                {
                break;
                }
              $fsfcms_camera_image_thumbnail_json = fsf_port_getImageThumbnailByID($fsfcms_camera_image_id);
              $fsfcms_camera_image_thumbnail      = json_decode($fsfcms_camera_image_thumbnail_json,true);
              $fsfcms_camera_image_clean_URL_json = fsf_port_getImageLinkCleanURL($fsfcms_camera_image_id);
              $fsfcms_camera_image_clean_URL      = json_decode($fsfcms_camera_image_clean_URL_json,true);
              echo "<div class=\"thumbnails-cameras-border\"><div class=\"thumbnail-images\"><a href=\"/" .  $fsfcms_camera_clean_URL . "/" . $fsfcms_camera_image_clean_URL['imageLink'] . "\"><img src =\"" . $fsfcms_camera_image_thumbnail['thumbnailURL'] . "\" width=\"" . $fsfcms_camera_image_thumbnail['thumbnailWidth'] . "\" height=\"" . $fsfcms_camera_image_thumbnail['thumbnailHeight'] . "\"  alt=\"" . $fsfcms_camera_image_clean_URL['imageTitle'] . "\" title=\"" . $fsfcms_camera_image_clean_URL['imageTitle'] . "\" /></a></div></div>";
              }
            echo "<h2><a href=\"/" . $fsfcms_camera_clean_URL . "\"><span class=\"lower\">" . $fsfcms_camera_full_name . "</span></a></h2>";
            }
          }
      ?>
		</div>  <!-- end thumbnails -->
		
		<div id="footer">
    <p class="navigation">
      Bored? Browse the <a href="/archives">archives</a>, view the latest images from various <a href="/authors">authors</a><?php echo ($fsfcms_camera_flag == 1 ? ", check out photographs taken with different <a href=\"/cameras\">cameras</a>" : ""); ?>, see the newest images organized into <a href="/categories">categories</a>, or explore sundry <a href="/keywords">keywords</a>.
    </p>
		<p>
			<?php echo fsfcms_getSiteCopyright(); ?> <br />
      <!-- SLR 680 site design by Olen Daelhousen. -->
		</p>
		</div>
		</div>  <!-- End Wrapper -->
	</body>
</html>
