<?php
$fsfcms_this_page = "authors";
$fsfcms_author  = 0;

if(count($fsfcms_page_request_parts) > 1)
  {
  $fsfcms_author_info         = array();

  $fsfcms_author_slug         = $fsfcms_page_request_parts[1];

  $fsfcms_author_info_json    = fsf_cms_getUserInfo($fsfcms_author_slug);
  $fsfcms_author_info         = json_decode($fsfcms_author_info_json,true);
  $fsfcms_author_info_status  = array_pop($fsfcms_author_info);
  if ($fsfcms_author_info_status != 200)
    {
    echo "<p>EPIC FAIL</p>";
    } else  {
    $fsfcms_author_flag  = 1;
    }  
  } else  {
  $fsfcms_author_flag  = 0;
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
			<?php echo ($fsfcms_author_flag == 1 ? $fsfcms_author_info['firstName'] . " " . $fsfcms_author_info['lastName'] : "Authors"); ?> &#149; <?php echo fsfcms_getSiteTitle(); ?>
		</title>
    <!-- CUSTOM FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Neuton:300,400' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css' />



		<!--  STYLES    -->
    <link rel="stylesheet" href="/templates/taco-flavor/site-style.css" type="text/css" />

		<!--  SCRIPTS   -->    
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
		<div id="<?php echo ($fsfcms_author_flag == 1 ? "thumbnails" : "authors"); ?>">
      <?php
        if($fsfcms_author_flag == 1)
          {         
          $authors_paged = fsfcms_getSiteGlobalPaged();
          if ($authors_paged == "no")
            { 
            echo fsf_port_getAuthorImageThumbnails($fsfcms_author_info['userId'],"","",$fsfcms_author_info['authorSlug']);
            } elseif($authors_paged == "yes")  {
            $authors_current_page = 1;
            if($fsfcms_page_request_parts[2] == "page")
              {
              if(isset($fsfcms_page_request_parts[3]))
                {                    
                $authors_current_page = $fsfcms_page_request_parts[3];
                }
              }
            if($authors_current_page < (fsfcms_getTotalImagesNumberByAuthor($fsfcms_author_info['userId'])/fsfcms_getSiteGlobalItemsPerPage()))
              {
              $authors_prev_page_link = "<div id=\"authors-prev-page\"><a href=\"/" . $fsfcms_this_page . "/" . $fsfcms_author_info['authorSlug'] . "/page/" . ($authors_current_page + 1) . "\">&laquo; previous posts</a></div>";
              } else  {
              $authors_prev_page_link = "";
              }
            if($authors_current_page != 1)
              {
              $authors_next_page_link = "<div id=\"authors-next-page\"><a href=\"/" . $fsfcms_this_page . "/" . $fsfcms_author_info['authorSlug'] . "/page/" . ($authors_current_page - 1) . "\">next posts &raquo;</a></div>";
              } else  {
              $authors_next_page_link = "";
              }
            echo fsf_port_getAuthorImageThumbnails($fsfcms_author_info['userId'],$authors_current_page,fsfcms_getSiteGlobalItemsPerPage(),$fsfcms_author_info['authorSlug']);            
            echo "<div id=\"authors-paged-nav\">" . $authors_prev_page_link;
            echo $authors_next_page_link . "</div>";
            }
          } else  {          
          $fsfcms_authors_json  = fsf_port_getAuthorsCleanURL();
          $fsfcms_authors       = json_decode($fsfcms_authors_json,true);

          // Loop through the list of authors
          $fsfcms_author_status = array_pop($fsfcms_authors);
          if ($fsfcms_author_status['status'] == 200)
            {
            foreach($fsfcms_authors as $fsfcms_author)
              {
              $fsfcms_author_id             = $fsfcms_author['userId'];
              $fsfcms_author_first_name     = $fsfcms_author['firstName'];
              $fsfcms_author_middle_name    = $fsfcms_author['middleName'];
              $fsfcms_author_last_name      = $fsfcms_author['lastName'];
              $fsfcms_author_biography      = $fsfcms_author['biography'];
              $fsfcms_author_images_posted  = $fsfcms_author['authorImagesPosted'];
              $fsfcms_author_clean_URL      = $fsfcms_author['authorCleanURL'];
            
              $fsfcms_author_image_ids_json = fsf_port_getAuthorImageIDs($fsfcms_author_id);
              $fsfcms_author_image_ids      = json_decode($fsfcms_author_image_ids_json);
              $fsfcms_author_images_counter = 0;
              $fsfcms_author_images_max     = 3;
              foreach($fsfcms_author_image_ids as $fsfcms_author_image_id)
                {
                $fsfcms_author_images_counter++;
                if ($fsfcms_author_images_counter > $fsfcms_author_images_max)
                  {
                  break;
                  }
                $fsfcms_author_image_thumbnail_json = fsf_port_getImageThumbnailByID($fsfcms_author_image_id);
                $fsfcms_author_image_thumbnail      = json_decode($fsfcms_author_image_thumbnail_json,true);
                $fsfcms_author_image_clean_URL_json = fsf_port_getImageLinkCleanURL($fsfcms_author_image_id);
                $fsfcms_author_image_clean_URL      = json_decode($fsfcms_author_image_clean_URL_json,true);
                echo "<div class=\"thumbnails-authors-border\"><div class=\"thumbnail-images\"><a href=\"/" . $fsfcms_author_clean_URL . "/" . $fsfcms_author_image_clean_URL['imageLink'] . "\"><img src =\"" . $fsfcms_author_image_thumbnail['thumbnailURL'] . "\" width=\"" . $fsfcms_author_image_thumbnail['thumbnailWidth'] . "\" height=\"" . $fsfcms_author_image_thumbnail['thumbnailHeight'] . "\"  alt=\"" . $fsfcms_author_image_clean_URL['imageTitle'] . "\" title=\"" . $fsfcms_author_image_clean_URL['imageTitle'] . "\" /></a></div></div>";
                }
              echo "<h2><a href=\"/" . $fsfcms_author_clean_URL . "\"><span class=\"lower\">" . $fsfcms_author_first_name . "</span>&nbsp;<span class=\"lower\">" . $fsfcms_author_last_name . "</span></a></h2>";
              echo "<p class=\"author-biography-p\">" . $fsfcms_author_biography . "</p>";
              }
            } else  {
            echo "<p>EPIC FAIL</p>";
            }
          }
      ?>
		</div>  <!-- end thumbnails -->
		
		<div id="footer">
    <p class="navigation">
      Bored? Browse the <a href="/archives">archives</a><?php echo ($fsfcms_author_flag == 1 ? ", view the latest images from various <a href=\"/authors\">authors</a>" : ""); ?>, check out photographs taken with different <a href="/cameras">cameras</a>, see the newest images organized into <a href="/categories">categories</a>, or explore sundry <a href="/keywords">keywords</a>.
    </p>
		<p>
			<?php echo fsfcms_getSiteCopyright(); ?> <br />
      <!-- SLR 680 site design by Olen Daelhousen. -->
		</p>
		</div>
		</div>  <!-- End Wrapper -->
	</body>
</html>
