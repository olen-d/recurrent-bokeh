<?php

$fsfcms_this_page  = "media";

if(count($fsfcms_page_request_parts) > 1)
  {
  $fsfcms_media_info     = array();
  $fsfcms_media_slug     = $fsfcms_page_request_parts[1];
  $fsfcms_media_name_json = fsf_port_getMediaNameBySlug($fsfcms_media_slug);
  $fsfcms_media_name     = json_decode($fsfcms_media_name_json, true); 
  $fsfcms_media_fullname  = $fsfcms_media_name['mediaManufacturer'] . " " . $fsfcms_media_name['mediaName'];
  $fsfcms_media_flag     = 1;
  } else  {
  $fsfcms_media_flag     = 0;
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
			<?php echo ($fsfcms_media_flag == 1 ? $fsfcms_media_fullname : "Media"); ?> &#149; <?php echo fsfcms_getSiteTitle(); ?>
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
		<div id="<?php echo ($fsfcms_media_flag == 1 ? "thumbnails" : "media"); ?>">
      <?php
        if($fsfcms_media_flag == 1)
          {         
          $media_paged = fsfcms_getSiteGlobalPaged();
          if ($media_paged == "no")
            {
            echo fsf_port_getMediaImageThumbnails($fsfcms_media_slug,"","");
            } elseif($media_paged == "yes")  { 
            $media_current_page = 1;
            $media_slug   = $fsfcms_page_request_parts[1];
            if($fsfcms_page_request_parts[2] == "page")
              {
              if(isset($fsfcms_page_request_parts[3]))
                {                    
                $media_current_page = $fsfcms_page_request_parts[3];
                }
              }
            if($media_current_page < (fsfcms_getTotalImagesNumberByMedia($fsfcms_media_slug)/fsfcms_getSiteGlobalItemsPerPage()))
              {
              $media_prev_page_link = "<div id=\"media-prev-page\"><a href=\"/" . $fsfcms_this_page . "/" . $media_slug . "/page/" . ($media_current_page + 1) . "\">&laquo; previous posts</a></div>";
              } else  {
              $media_prev_page_link = "";
              }
            if($media_current_page != 1)
              {
              $media_next_page_link = "<div id=\"media-next-page\"><a href=\"/" . $fsfcms_this_page . "/" . $media_slug . "/page/" . ($media_current_page - 1) . "\">next posts &raquo;</a></div>";
              } else  {
              $media_next_page_link = "";
              }
            echo fsf_port_getMediaImageThumbnails($fsfcms_media_slug,$media_current_page,fsfcms_getSiteGlobalItemsPerPage());            
            echo "<div id=\"media-paged-nav\">" . $media_prev_page_link;
            echo $media_next_page_link . "</div>";
            }
          } else  {          
          $fsfcms_media_json  = fsf_port_getMediaCleanURL();
          $fsfcms_media       = json_decode($fsfcms_media_json,true);
                            
          // Loop through the list of media
          foreach($fsfcms_media as $fsfcms_media_item)
            {
            $fsfcms_media_id              = $fsfcms_media_item['mediaId'];
            $fsfcms_media_manufacturer    = $fsfcms_media_item['mediaManufacturer'];
            $fsfcms_media_name            = $fsfcms_media_item['mediaName'];
            $fsfcms_media_speed           = $fsfcms_media_item['mediaSpeed'];
            $fsfcms_media_type            = $fsfcms_media_item['mediaType'];
            $fsfcms_media_slug            = $fsfcms_media_item['mediaSlug'];
            $fsfcms_media_clean_URL       = "media/" . $fsfcms_media_slug;
            
            $fsfcms_media_image_ids_json = fsf_port_getMediaImageIDs($fsfcms_media_id);
            $fsfcms_media_image_ids      = json_decode($fsfcms_media_image_ids_json);
              $fsfcms_media_images_counter = 0;
              $fsfcms_media_images_max     = 3;
              foreach($fsfcms_media_image_ids as $fsfcms_media_image_id)
              {
              $fsfcms_media_images_counter++;
              if ($fsfcms_media_images_counter > $fsfcms_media_images_max)
                {
                break;
                }
              $fsfcms_media_image_thumbnail_json = fsf_port_getImageThumbnailByID($fsfcms_media_image_id);
              $fsfcms_media_image_thumbnail      = json_decode($fsfcms_media_image_thumbnail_json,true);
              $fsfcms_media_image_clean_URL_json = fsf_port_getImageLinkCleanURL($fsfcms_media_image_id);
              $fsfcms_media_image_clean_URL      = json_decode($fsfcms_media_image_clean_URL_json,true);
              echo "<div class=\"thumbnails-media-border\"><div class=\"thumbnail-images\"><a href=\"/" . $fsfcms_media_image_clean_URL['imageLink'] . "\"><img src =\"" . $fsfcms_media_image_thumbnail['thumbnailURL'] . "\" width=\"" . $fsfcms_media_image_thumbnail['thumbnailWidth'] . "\" height=\"" . $fsfcms_media_image_thumbnail['thumbnailHeight'] . "\"  alt=\"" . $fsfcms_media_image_clean_URL['imageTitle'] . "\" title=\"" . $fsfcms_media_image_clean_URL['imageTitle'] . "\" /></a></div></div>";
              }
            echo "<h2><a href=\"/" . $fsfcms_media_clean_URL . "\"><span class=\"lower\">" . $fsfcms_media_manufacturer . " " . $fsfcms_media_name . "</span></a></h2>";
            }
          }
      ?>
		</div>  <!-- end thumbnails -->
		
		<div id="footer">
    <p class="navigation">
      Bored? Browse the <a href="/archives">archives</a>, view the latest images from various <a href="/authors">authors</a>, check out photographs taken with different <a href="/cameras">cameras</a><?php echo ($fsfcms_category_flag == 1 ? ", see the newest images organized into <a href=\"/categories\">categories</a>" : ""); ?>, or explore sundry <a href="/keywords">keywords</a>.
    </p>
		<p>
			<?php echo fsfcms_getSiteCopyright(); ?> <br />
      <!-- SLR 680 site design by Olen Daelhousen. -->
		</p>
		</div>
		</div>  <!-- End Wrapper -->
	</body>
</html>