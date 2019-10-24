<?php

$fsfcms_this_page  = "categories";

if(count($fsfcms_page_request_parts) > 1)
  {
  $fsfcms_category_info     = array();
  $fsfcms_category_slug     = $fsfcms_page_request_parts[1];
  $fsfcms_category_name     = fsf_port_getCategoryNameBySlug($fsfcms_category_slug); 
  $fsfcms_category_flag     = 1;
  } else  {
  $fsfcms_category_flag     = 0;
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
			<?php echo ($fsfcms_category_flag == 1 ? ucwords($fsfcms_category_name) : "Categories"); ?> &#149; <?php echo fsfcms_getSiteTitle(); ?>
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
		<div id="<?php echo ($fsfcms_category_flag == 1 ? "thumbnails" : "categories"); ?>">
      <?php
        if($fsfcms_category_flag == 1)
          {
          $categories_paged = fsfcms_getSiteGlobalPaged();         
          if ($categories_paged == "no")
            { 
            echo fsf_port_getCategoryImageThumbnails($fsfcms_category_slug,"","");
            } elseif($categories_paged == "yes")  {
            $categories_current_page = 1;
            $category_slug   = $fsfcms_page_request_parts[1];
            if($fsfcms_page_request_parts[2] == "page")
              {
              if(isset($fsfcms_page_request_parts[3]))
                {                    
                $categories_current_page = $fsfcms_page_request_parts[3];
                }
              }
            if($categories_current_page < (fsfcms_getTotalImagesNumberByCategory($fsfcms_category_slug)/fsfcms_getSiteGlobalItemsPerPage()))
              {
              $categories_prev_page_link = "<div id=\"categories-prev-page\"><a href=\"/" . $fsfcms_this_page . "/" . $category_slug . "/page/" . ($categories_current_page + 1) . "\">&laquo; previous posts</a></div>";
              } else  {
              $categories_prev_page_link = "";
              }
            if($categories_current_page != 1)
              {
              $categories_next_page_link = "<div id=\"categories-next-page\"><a href=\"/" . $fsfcms_this_page . "/" . $category_slug . "/page/" . ($categories_current_page - 1) . "\">next posts &raquo;</a></div>";
              } else  {
              $categories_next_page_link = "";
              }
            echo fsf_port_getCategoryImageThumbnails($fsfcms_category_slug,$categories_current_page,fsfcms_getSiteGlobalItemsPerPage());            
            echo "<div id=\"categories-paged-nav\">" . $categories_prev_page_link;
            echo $categories_next_page_link . "</div>";
            }
          } else  {          
          $fsfcms_categories_json  = fsf_port_getCategoriesCleanURL();
          $fsfcms_categories       = json_decode($fsfcms_categories_json,true);
                            
          // Loop through the list of categories
          foreach($fsfcms_categories as $fsfcms_category)
            {
            $fsfcms_category_id           = $fsfcms_category['categoryId'];
            $fsfcms_category_name         = $fsfcms_category['categoryName'];
            $fsfcms_category_slug         = $fsfcms_category['categorySlug'];
            $fsfcms_category_description  = $fsfcms_category['categoryDescription'];
            $fsfcms_category_clean_URL    = "categories/" . $fsfcms_category_slug;
            
            $fsfcms_category_image_ids_json = fsf_port_getCategoryImageIDs($fsfcms_category_id);
            $fsfcms_category_image_ids      = json_decode($fsfcms_category_image_ids_json);
              $fsfcms_category_images_counter = 0;
              $fsfcms_category_images_max     = 3;
              foreach($fsfcms_category_image_ids as $fsfcms_category_image_id)
              {
              $fsfcms_category_images_counter++;
              if ($fsfcms_category_images_counter > $fsfcms_category_images_max)
                {
                break;
                }
              $fsfcms_category_image_thumbnail_json = fsf_port_getImageThumbnailByID($fsfcms_category_image_id);
              $fsfcms_category_image_thumbnail      = json_decode($fsfcms_category_image_thumbnail_json,true);
              $fsfcms_category_image_clean_URL_json = fsf_port_getImageLinkCleanURL($fsfcms_category_image_id);
              $fsfcms_category_image_clean_URL      = json_decode($fsfcms_category_image_clean_URL_json,true);
              echo "<div class=\"thumbnails-categories-border\"><div class=\"thumbnail-images\"><a href=\"/" . $fsfcms_category_clean_URL . "/" . $fsfcms_category_image_clean_URL['imageLink'] . "\"><img src =\"" . $fsfcms_category_image_thumbnail['thumbnailURL'] . "\" width=\"" . $fsfcms_category_image_thumbnail['thumbnailWidth'] . "\" height=\"" . $fsfcms_category_image_thumbnail['thumbnailHeight'] . "\"  alt=\"" . $fsfcms_category_image_clean_URL['imageTitle'] . "\" title=\"" . $fsfcms_category_image_clean_URL['imageTitle'] . "\" /></a></div></div>";
              }
            echo "<h2><a href=\"/" . $fsfcms_category_clean_URL . "\"><span class=\"lower\">" . $fsfcms_category_name . "</span></a></h2>";
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