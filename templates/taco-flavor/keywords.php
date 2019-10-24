<?php

$fsfcms_this_page  = "keywords";

if(count($fsfcms_page_request_parts) > 1)
  {
  $fsfcms_keyword_info    = array();
  $fsfcms_keyword_slug    = $fsfcms_page_request_parts[1];
  // TODO: Sanitize the keyword slug
  $fsfcms_keyword_info    = fsf_port_getKeywordAttributes($fsfcms_keyword_slug);
  if(array_pop($fsfcms_keyword_info) == 200)
    {
    $fsfcms_keyword_name  = $fsfcms_keyword_info['keyword'];
    $fsfcms_keyword_slug  = $fsfcms_keyword_info['keywordSlug'];  
    } //  TODO: Fail if the status is not 200 and return some sort of intelligent error
  $fsfcms_keyword_flag    = 1;
  } else  {
  $fsfcms_keyword_flag    = 0;
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
			<?php echo ($fsfcms_keyword_flag == 1 ? ucwords($fsfcms_keyword_name) : "Keywords"); ?> &#149; <?php echo fsfcms_getSiteTitle(); ?>
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
		<div id="<?php echo ($fsfcms_keyword_flag == 1 ? "thumbnails" : "keywords"); ?>">
      <?php
      if($fsfcms_keyword_flag == 1)
        {
        $keywords_paged = fsfcms_getSiteGlobalPaged();         
        if ($keywords_paged == "no")
          { 
          echo fsfcms_port_getImageThumbnailsByKeyword($fsfcms_keyword_slug,"","");
          } elseif($keywords_paged == "yes")  {
            $keywords_current_page = 1;
            $keyword_slug   = $fsfcms_page_request_parts[1];
            if($fsfcms_page_request_parts[2] == "page")
              {
              if(isset($fsfcms_page_request_parts[3]))
                {                    
                $keywords_current_page = $fsfcms_page_request_parts[3];
                }
              }
            if($keywords_current_page < (fsfcms_getTotalImagesNumberByKeyword($fsfcms_keyword_slug)/fsfcms_getSiteGlobalItemsPerPage()))
              {
              $keywords_prev_page_link = "<div id=\"keywords-prev-page\"><a href=\"/" . $fsfcms_this_page . "/" . $keyword_slug . "/page/" . ($keywords_current_page + 1) . "\">&laquo; previous posts</a></div>";
              } else  {
              $keywords_prev_page_link = "";
              }
            if($keywords_current_page != 1)
              {
              $keywords_next_page_link = "<div id=\"keywords-next-page\"><a href=\"/" . $fsfcms_this_page . "/" . $keyword_slug . "/page/" . ($keywords_current_page - 1) . "\">next posts &raquo;</a></div>";
              } else  {
              $keywords_next_page_link = "";
              }
            echo fsfcms_port_getImageThumbnailsByKeyword($fsfcms_keyword_slug,$keywords_current_page,fsfcms_getSiteGlobalItemsPerPage());
            //echo fsf_port_getKeywordImageThumbnails($fsfcms_keyword_slug,$keywords_current_page,fsfcms_getSiteGlobalItemsPerPage());            
            echo "<div id=\"keywords-paged-nav\">" . $keywords_prev_page_link;
            echo $keywords_next_page_link . "</div>";
            } 
        } else  {
        echo fsf_port_getCloudKeywordsLogarithmic(50);
        } ?>
		</div>  <!-- end thumbnails -->
		
		<div id="footer">
    <p class="navigation">
      Bored? Browse the <a href="/archives">archives</a>, view the latest images from various <a href="/authors">authors</a>, check out photographs taken with different <a href="/cameras">cameras</a><?php echo ($fsfcms_keyword_flag == 1 ? "," : ", or"); ?> see the newest images organized into <a href="/categories">categories</a><?php echo ($fsfcms_keyword_flag == 1 ? ", or explore sundry <a href=\"/keywords\">keywords</a>." : "."); ?>
    </p>
		<p>
			<?php echo fsfcms_getSiteCopyright(); ?> <br />
      <!-- SLR 680 site design by Olen Daelhousen. -->
		</p>
		</div>
		</div>  <!-- End Wrapper -->
	</body>
</html>
