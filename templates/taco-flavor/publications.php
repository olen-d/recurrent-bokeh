<?php

$fsfcms_this_page  = "publications";

/*
if(count($fsfcms_page_request_parts) > 1)
  {
  $fsfcms_category_info     = array();
  $fsfcms_category_slug     = $fsfcms_page_request_parts[1];
  $fsfcms_category_name     = fsf_port_getCategoryNameBySlug($fsfcms_category_slug); 
  $fsfcms_category_flag     = 1;
  } else  {
  $fsfcms_category_flag     = 0;
  }
*/
$fsfcms_publication_cover_thumb_path = "/pubs/covers/";		  
?>
<?php
if(!@include "declarations.php")
  {
  echo "<html>";
	echo "<head>";
	}
?>
		<title>
			<?php echo ($fsfcms_publication_flag == 1 ? ucwords($fsfcms_publication_name) : "Publications"); ?> &#149; <?php echo fsfcms_getSiteTitle(); ?>
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
		<div id="<?php echo ($fsfcms_publication_flag == 1 ? "publication" : "publications"); ?>">
      <?php
        if($fsfcms_publication_flag == 1)
          {
/*          
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
*/
          } else  {          
          $fsfcms_publications_json  = fsf_cms_getPublications("all");
          $fsfcms_publications       = json_decode($fsfcms_publications_json,true);
                   //  print_r($fsfcms_publications_json);       
          // Loop through the list of publications
          $i  = 0;
          $j  = 0;
          $hack = "";
          $hack2 = "";
          foreach($fsfcms_publications as $fsfcms_publication)
            {
            $fsfcms_publication_id                  = $fsfcms_publication['id'];
            $fsfcms_publication_title               = $fsfcms_publication['title'];
            $fsfcms_publication_subtitle            = $fsfcms_publication['subtitle'];
            $fsfcms_publication_author_first_name   = $fsfcms_publication['authorFirstName'];
            $fsfcms_publication_author_middle_name  = $fsfcms_publication['authorMiddleName'];
            $fsfcms_publication_author_last_name    = $fsfcms_publication['authorLastName'];
            $fsfcms_publication_title_slug          = $fsfcms_publication['titleSlug'];
            $fsfcms_publication_volume              = $fsfcms_publication['volume'];
            $fsfcms_publication_issue               = $fsfcms_publication['issue'];
            $fsfcms_publication_publish_date_ts     = $fsfcms_publication['publishDate'];
            $fsfcms_publication_copyright           = $fsfcms_publication['copyright'];
            $fsfcms_publication_edition             = $fsfcms_publication['edition'];
            $fsfcms_publication_sold                = $fsfcms_publication['sold'];
            $fsfcms_publication_description         = $fsfcms_publication['description'];
            $fsfcms_publication_width               = $fsfcms_publication['width'];
            $fsfcms_publication_height              = $fsfcms_publication['height'];
            $fsfcms_publication_units               = $fsfcms_publication['units'];
            $fsfcms_publication_pages               = $fsfcms_publication['pages'];
            $fsfcms_publication_binding             = $fsfcms_publication['binding'];
            $fsfcms_publication_price               = $fsfcms_publication['price'];
            $fsfcms_publication_cover_thumb_fn      = $fsfcms_publication['coverThumbFileName'];
            $fsfcms_publication_purchase_link       = $fsfcms_publication['purchaseLink'];




            /*
            $fsfcms_publication_clean_URL    = "categories/" . $fsfcms_category_slug;
            
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
              echo "<div class=\"thumbnails-categories-border\"><div class=\"thumbnail-images\"><a href=\"/" . $fsfcms_category_image_clean_URL['imageLink'] . "\"><img src =\"" . $fsfcms_category_image_thumbnail['thumbnailURL'] . "\" width=\"" . $fsfcms_category_image_thumbnail['thumbnailWidth'] . "\" height=\"" . $fsfcms_category_image_thumbnail['thumbnailHeight'] . "\"  alt=\"" . $fsfcms_category_image_clean_URL['imageTitle'] . "\" title=\"" . $fsfcms_category_image_clean_URL['imageTitle'] . "\" /></a></div></div>";
              }
              */
            echo  "
                  <div class=\"publications-wrapper\"" . $hack . $hack2 . ">
                  <div class=\"publication-container\">
                  <a href=\"" . $fsfcms_publication_purchase_link . "\"><img class=\"publication-cover\" alt=\"The cover of " . $fsfcms_publication_title . ": " . $fsfcms_publication_subtitle . "\" src=\"" . $fsfcms_publication_cover_thumb_path . $fsfcms_publication_cover_thumb_fn . "\" /></a>
                  <div class=\"publication-brief\">
                  <p>Volume&nbsp;" . $fsfcms_publication_volume . "&nbsp;Issue&nbsp;" . $fsfcms_publication_issue . "</p>
                  <p>Published&nbsp;" . date("F Y",$fsfcms_publication_publish_date_ts) . "</p>
                  <p>by&nbsp;<!--<a href=\"/" .$fsfcms_publication_author_clean_URL . "\">-->" . $fsfcms_publication_author_first_name . "&nbsp;" . $fsfcms_publication_author_last_name . "<!--</a>--></p>
                  <h2 class=\"publication-title\"><span class=\"collection-title\"><a href=\"" . $fsfcms_publication_purchase_link . "\">" . $fsfcms_publication_title . "
                  <span class=\"sub-title\">" . $fsfcms_publication_subtitle . "</a></h2>
                  <p class=\"publication-description\">" . $fsfcms_publication_description . "</p>
                  <p>" . trim(trim($fsfcms_publication_width,"0"),".") . "&nbsp;x&nbsp;" . trim(trim($fsfcms_publication_height,"0"),".") . "&nbsp;" . $fsfcms_publication_units . "</p>
                  <p>" . $fsfcms_publication_pages . "&nbsp;pages</p>
                  <p>" . $fsfcms_publication_binding . "</p>
                  <p>$" . $fsfcms_publication_price . "</p>
                  </div>
                  </div><!-- End Publication Container -->
                  </div><!--  End Publications Wrapper  -->
                  ";
                        $i++;
                        $j++;
      if($i == 2)
        {
        $hack = "style=\"clear:both;\"";
        $i    = 0;
        } else  {
        $hack = "";
        }
              if($j == 1)
        {
        $hack2 = "style=\"float:right;\"";
        $j    = 0;
        } else  {
        $hack2 = "";
        }
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