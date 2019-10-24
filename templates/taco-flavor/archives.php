<?php 
  $fsfcms_this_page = "archives"; 
  //  TODO: 
  //
  //    1.  Fix the title to include monthly or not monthly archives
  //    2.  Bring most of the llogic between monthly/paged/all up here at the top to seperate it from the template
  //    3.  Clean up multiple calls to the API & CMS functions - put them in variables to reduce overhead

  $fsfcms_getImageThumbnails_options  = array();

  $fsfcms_server_time_zone  = fsfcms_getServerTimeZone();
  $fsfcms_server_time_zone_offset = $fsfcms_server_time_zone['serverTimeZoneOffset']; 
   
  if(!@include "declarations.php")
    {
    echo "<html>";
	 echo "<head>";
	 }
?>
		<title>
			Archives &#149; <?php echo fsfcms_getSiteTitle(); ?>
		</title>
    <!--  CUSTOM FONTS  -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Neuton:300,400' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css' />



		<!--  STYLES        -->
    <link rel="stylesheet" href="/templates/taco-flavor/site-style.css" type="text/css" />
 
    <!--  JAVASCRIPT    -->   
		<script type='text/javascript'>
			<!-- BEGIN

      window.onload = init;
      
      function init()
        {
        var archivesPagedCurrent  = document.getElementById('archives-paged-current');
        if (archivesPagedCurrent)
          { 
          archivesPagedCurrent.addEventListener('click',function(){toggleItemVisibility('archives-paged-list')},false);
          }
        var archivesMonthlyCurrent = document.getElementById('archives-monthly-current');
        if(archivesMonthlyCurrent)
          {
          archivesMonthlyCurrent.addEventListener('click',function(){toggleItemVisibility('archives-monthly-list')},false);
          }
        }

      // Functions to show and hide things   
      function toggleItemVisibility(itemId)
        {
        var item = document.getElementById(itemId)
        if (item.style.display == 'block')
          item.style.display = 'none';
        else
          item.style.display = 'block'; 
        }

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
		<div id="thumbnails">
		  <?php
        $archives_paged           = fsfcms_getSiteGlobalPaged();
        
		    //  Check to see if this is a monthly archive
        $archives_month_regex     = "/\b\d{4}\/(0[1-9]|1[012])/";
        $archives_is_month_url    = preg_match($archives_month_regex,$fsfcms_page_request); 
        if($archives_is_month_url)
          {
          //$fsfcms_archives_monthly_flag                         = TRUE; //  REM Delete this if it turns out not to be needed
          $fsfcms_archives_view_text                            = "You are currently viewing the archives organized by month. <a href=\"/archives/\">View archives paged</a>.";
          
          $fsfcms_archives_current_year                         = $fsfcms_page_request_parts[1];
          $fsfcms_archives_current_month                        = $fsfcms_page_request_parts[2];
          $fsfcms_archives_current_month_slug                   = $fsfcms_archives_current_year . "/" . $fsfcms_archives_current_month;
          
          $fsfcms_getImageThumbnails_year_month                 = $fsfcms_archives_current_year . $fsfcms_archives_current_month;
          $fsfcms_getImageThumbnails_options['yearMonth']       = $fsfcms_getImageThumbnails_year_month;
          $fsfcms_getImageThumbnails_options['timeZoneOffset']  = $fsfcms_server_time_zone_offset;
          echo fsf_port_getArchiveImageThumbnails($fsfcms_getImageThumbnails_options);

          //  Get the dropdown list from the API
          $fsfcms_archives_monthly_options                      = array();
          $fsfcms_archives_monthly_options['timeZoneOffset']    = $fsfcms_server_time_zone_offset;
          $fsfcms_archives_monthly_options['currentMonth']      = $fsfcms_archives_current_month_slug; 
          
          $fsfcms_archives_monthly_results  = fsf_cms_getArchivesMonthly_dropDown($fsfcms_archives_monthly_options);

          //  Monthly archives are always paged by month
          if($fsfcms_archives_current_month_slug != $fsfcms_archives_monthly_results['lastMonth'])
            {
            $fsfcms_previous_slug     = $fsfcms_archives_monthly_results['previousMonth']; 
            $archives_prev_page_link  = "<div id=\"archives-prev-page\"><a href=\"/" . $fsfcms_this_page . "/" .  $fsfcms_previous_slug . "\"><!--&laquo;-->&#9664; previous posts</a></div>";
            } else  {
            $archives_prev_page_link = "<div id=\"archives-prev-page\">&nbsp;</div>";
            }
          if($fsfcms_archives_current_month_slug != $fsfcms_archives_monthly_results['firstMonth'])
            {
            $fsfcms_next_slug         = $fsfcms_archives_monthly_results['nextMonth'];
            $archives_next_page_link  = "<div id=\"archives-next-page\"><a href=\"/" . $fsfcms_this_page . "/" . $fsfcms_next_slug . "\">next posts <!--&raquo;-->&#9654;</a></div>";
            } else  {
            $archives_next_page_link = "<div id=\"archives-next-page\">&nbsp</div>";
            }

          echo "<div id=\"archives-paged-nav\">" . $archives_prev_page_link;
          echo "<div id=\"archives-monthly-dropdown\">" . $fsfcms_archives_monthly_results['output'] . "</div>";
          echo $archives_next_page_link . "</div>";
          echo "<div id=\"archives-view-select\">" . $fsfcms_archives_view_text . "</div>";

          //  Done with monthly archives
          } elseif  ($archives_paged == "no") {
          echo fsf_port_getArchiveImageThumbnails($fsfcms_getImageThumbnails_options); 
          } elseif($archives_paged == "yes")  {
          $fsfcms_archives_view_text                            = "You are currently viewing the archives organized by page. <a href=\"/archives/" . fsf_cms_MostRecentArchiveMonthlySlug($fsfcms_server_time_zone_offset). "\">View archives by month</a>.";

          $archives_current_page = 1;
          if($fsfcms_page_request_parts[1] == "page")
            {
            if(isset($fsfcms_page_request_parts[2]))
              {                    
              $archives_current_page = $fsfcms_page_request_parts[2];
              }
            }
          if($archives_current_page < (fsfcms_getTotalImagesNumber()/fsfcms_getSiteGlobalItemsPerPage()))
            {
            $archives_prev_page_link = "<div id=\"archives-prev-page\"><a href=\"/" . $fsfcms_this_page . "/page/" . ($archives_current_page + 1) . "\"><!--&laquo;-->&#9664; previous posts</a></div>";
            } else  {
            $archives_prev_page_link = "<div id=\"archives-prev-page\">&nbsp;</div>";
            }
          if($archives_current_page != 1)
            {
            $archives_next_page_link = "<div id=\"archives-next-page\"><a href=\"/" . $fsfcms_this_page . "/page/" . ($archives_current_page - 1) . "\">next posts <!--&raquo;--> &#9654;</a></div>";
            } else  {
            $archives_next_page_link = "<div id=\"archives-next-page\">&nbsp</div>";
            }
          $fsfcms_getImageThumbnails_options['page']  = $archives_current_page;
          $fsfcms_getImageThumbnails_options['items'] = fsfcms_getSiteGlobalItemsPerPage();
          echo fsf_port_getArchiveImageThumbnails($fsfcms_getImageThumbnails_options);            
          echo "<div id=\"archives-paged-nav\">" . $archives_prev_page_link;
          $fsfcms_archives_paged_options                    = array();
          $fsfcms_archives_paged_options['thumbsPerPage']   = fsfcms_getSiteGlobalItemsPerPage();
          $fsfcms_archives_paged_options['timeZoneOffset']  = $fsfcms_server_time_zone_offset;
          $fsfcms_archives_paged_options['totalImages']     = fsfcms_getTotalImagesNumber();
          $fsfcms_archives_paged_options['currentPage']     = $archives_current_page;
          echo "<div id=\"archives-paged-dropdown\">" . fsf_cms_getArchivesPaged_dropDown($fsfcms_archives_paged_options) . "</div>";
          echo $archives_next_page_link . "</div>";
          echo "<div id=\"archives-view-select\">" . $fsfcms_archives_view_text . "</div>";
          }
          ?>
		</div>  <!-- end thumbnails -->	
		<div id="footer">
		<p class="navigation">
		  Bored? View the latest images from various <a href="/authors">authors</a>, check out photographs taken with different <a href="/cameras">cameras</a>, see the newest images organized into <a href="/categories">categories</a>, or explore sundry <a href="/keywords">keywords</a>.
		</p>
		<p>
			<?php echo fsfcms_getSiteCopyright(); ?> <br />
      <!-- SLR 680 site design by Olen Daelhousen. -->
		</p>
		</div>
		</div>  <!-- End Wrapper -->
	</body>
</html>
