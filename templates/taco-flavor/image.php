<?php
$fsfcms_this_page = "image";
$fsfcms_site_twitter_handle     = "@SLR_680";      //  Remember to update the settings to include this
$fsfcms_author_twitter_handle   = "@Olen_D";      //  Remember to update the settings to include this

//  Get the site contact information

$fsfcms_site_contact_info = fsfcms_getSiteContact();
$fsfcms_site_email  = $fsfcms_site_contact_info['siteEmail'];
$fsfcms_site_phone  = $fsfcms_site_contact_info['sitePhone'];
$fsfcms_site_first_name  = $fsfcms_site_contact_info['firstName'];
$fsfcms_site_middle_name  = $fsfcms_site_contact_info['middleName'];
$fsfcms_site_last_name  = $fsfcms_site_contact_info['lastName'];
//$fsfcms_site_email  = $fsfcms_site_contact_info['siteEmail']; 

$fsf_port_getImage_parameters = array();

// Get the current image and information
if($fsfcms_is_image_id)
  {
  $fsfcms_display_image       = $_GET['displayImage'];
  $fsf_port_getImage_parameters['lookup'] = "id";
  $fsf_port_getImage_parameters['imageId'] = $fsfcms_display_image;
  }

if(count($fsfcms_page_request_parts) > 1)
  {
    if ($fsfcms_is_image_url)
    {
    //  Requires $fsfcms_page_request_parts_count to be defined in the index page
    $fsfcms_image_year_offset         = $fsfcms_page_request_parts_count - 3;
    $fsfcms_image_month_offset        = $fsfcms_page_request_parts_count - 2;
    $fsfcms_image_slug_offset         = $fsfcms_page_request_parts_count - 1;  
    $fsfcms_image_year_month  = $fsfcms_page_request_parts[$fsfcms_image_year_offset] . $fsfcms_page_request_parts[$fsfcms_image_month_offset];
    $fsfcms_image_slug        = $fsfcms_page_request_parts[$fsfcms_image_slug_offset];
    $fsf_port_getImage_parameters['lookup']     = "URL";
    $fsf_port_getImage_parameters['yearMonth']  = $fsfcms_image_year_month;
    $fsf_port_getImage_parameters['slug']       = $fsfcms_image_slug;
    } 
  }

$fsfcms_current_image_json  = fsf_port_getImage($fsf_port_getImage_parameters);


$fsfcms_current_image_info                  = json_decode($fsfcms_current_image_json,true);     // The true decodes the JSON as an associative array.
//echo "<p>ninja " .$fsfcms_current_image_info['status'] . "</p>";
//$fsfcms_current_image_status = 200;
$fsfcms_current_image_status =  $fsfcms_current_image_info['status']; 
if($fsfcms_current_image_status == 200)
  {
  $fsfcms_current_image_id                    = $fsfcms_current_image_info['id'];
  $fsfcms_current_image_src                   = $fsfcms_current_image_info['URL'] . $fsfcms_current_image_info['filename'];
  $fsfcms_current_image_title                 = $fsfcms_current_image_info['title'];
  $fsfcms_current_image_caption               = $fsfcms_current_image_info['caption'];
  $fsfcms_current_image_camera                = $fsfcms_current_image_info['cameraFullName'];
  $fsfcms_current_image_camera_slug           = $fsfcms_current_image_info['cameraSlug'];
  $fsfcms_current_image_posted_date_unix_ts   = $fsfcms_current_image_info['postedDateUnixTimestamp'];
  $fsfcms_current_image_posted_date_time_formats = fsf_cms_format_datetime($fsfcms_current_image_posted_date_unix_ts);
  $fsfcms_current_image_posted_date_formatted = $fsfcms_current_image_posted_date_time_formats['dateFormatted'];
  $fsfcms_current_image_posted_time_formatted = $fsfcms_current_image_posted_date_time_formats['timeFormattedFuzzy']; 
  
  $fsfcms_current_image_authors_formatted = "";
  $fsfcms_current_image_authors = $fsfcms_current_image_info['authors'];
        
  foreach($fsfcms_current_image_authors as $fsfcms_current_image_author)
    {   //print_r($fsfcms_current_image_authors);exit;
    $fsfcms_current_image_author_id             = $fsfcms_current_image_author['userId'];
    $fsfcms_current_image_author_first_name     = $fsfcms_current_image_author['firstName'];
    $fsfcms_current_image_author_middle_name    = $fsfcms_current_image_author['middleName'];
    $fsfcms_current_image_author_last_name      = $fsfcms_current_image_author['lastName'];
    $fsfcms_current_image_author_slug           = $fsfcms_current_image_author['authorSlug'];
    
    $fsfcms_current_image_authors_formatted .= "<a href=\"/authors/" . $fsfcms_current_image_author_slug . "\">" . $fsfcms_current_image_author_first_name . " " . $fsfcms_current_image_author_last_name . "</a>, ";
    }
    $fsfcms_current_image_authors_formatted = trim($fsfcms_current_image_authors_formatted,", ");
  $fsfcms_current_image_width                 = $fsfcms_current_image_info['width'];
  $fsfcms_current_image_height                = $fsfcms_current_image_info['height'];
             
  $fsfcms_photo_info_box_top    = round($fsfcms_current_image_height / 5);

        
  $fsfcms_image_link = fsf_port_getImageLinkCleanURL($fsfcms_current_image_id);
  $fsfcms_image_link_info = json_decode($fsfcms_image_link,true);
  $fsfcms_image_link_clean_URL = $fsfcms_image_link_info['imageLink'];

  	}  elseif($fsfcms_current_image_status == 404)  {
  header("HTTP/1.0 404 Not Found");
  header("Location: http://www.slr680.com/image-not-found");  // REMEMBER TO CUSTOMISE THIS TO WORK WITH THE SITE URL VARIABLE AND TEMPLATING SYSTEM
  exit;
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
			<?php echo $fsfcms_current_image_title; ?> &#8226; <?php echo fsfcms_getSiteTitle(); ?>
		</title>
    <!-- CUSTOM FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Neuton:300,400' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css' />



		<!-- STYLES -->
    <link rel="stylesheet" href="/templates/taco-flavor/site-style.css" type="text/css" />
    
		<script type='text/javascript'>
			<!-- BEGIN
      
      window.onload = init;
      
      function init()
        {
        var hiddenItemPrefix  = "ah";
        var allAnnouncementTogglesExpand    = document.querySelectorAll('span.announcement-expand');
        var allAnnouncementTogglesCollapse  = document.querySelectorAll('span.announcement-collapse');
     
        for (var i =0; i < allAnnouncementTogglesExpand.length; i++)
          {
          var toggleID = hiddenItemPrefix + i;
          allAnnouncementTogglesExpand[i].addEventListener('click',function(){showHiddenItem(toggleID)},false);
          allAnnouncementTogglesCollapse[i].addEventListener('click',function(){hideHiddenItem(toggleID)},false);
          }
        }

      // Functions to show and hide the slide show photograph controls REMEMBER TO PUT THIS IN A SEPERATE FILE!

      function showPhotoInfo(containerID)
        {
        document.getElementById(containerID).style.display = 'block';  
        }

      function hidePhotoInfo(containerID)
        {
        document.getElementById(containerID).style.display = 'none';
        }

      // Functions to show and hide the additonal info   
        function showHiddenItem(hiddenItemID)
        {
        document.getElementById(hiddenItemID).style.display = 'block';  
        }

      function hideHiddenItem(hiddenItemID)
        {
        document.getElementById(hiddenItemID).style.display = 'none';
        }

			// End -->
		</script>
    <!-- Atom & RSS Feed Autodiscovery -->
    <link rel="alternate" href="http://www.slr680.com/feed/atom" title="<?php echo fsfcms_getSiteTitle(); ?> Image Feed (Atom 1.0)" type="application/atom+xml" />
    <link rel="alternate" href="http://www.slr680.com/feed/rss" title="<?php echo fsfcms_getSiteTitle(); ?> Image Feed (RSS 2.0)" type="application/rss+xml" />

    <!--  Twitter Cards REM: replace quotes with the ampersand thing  -->
    <meta name="twitter:card"         content="summary_large_image" />
    <meta name="twitter:site"         content="<?php echo $fsfcms_site_twitter_handle; ?>" />
    <meta name="twitter:creator"      content="<?php echo $fsfcms_author_twitter_handle;  ?>" />
    <meta name="twitter:title"        content="<?php echo $fsfcms_current_image_title; ?>" />
    <meta name="twitter:description"  content="<?php echo $fsfcms_current_image_caption; ?>" />
    <meta name="twitter:image:source" content="<?php echo $fsfcms_current_image_src; ?>" />          
	</head>
	<body>
	 <div id="wrapper">
    <header>
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
    </header>
		<article>
		<div id="photographs-border">
		<div id="photographs" onmouseover="showPhotoInfo('photo-info-box')" onmouseout="hidePhotoInfo('photo-info-box')">
      <?php echo "<img src=\"" . $fsfcms_current_image_src . "\" title=\"" . $fsfcms_current_image_title . "\" alt=\"" . $fsfcms_current_image_title . "\" width=\"" . $fsfcms_current_image_width . "\" height=\"" . $fsfcms_current_image_height . "\" />"; ?>
		<div id="photo-info-box" style="top:<?php echo $fsfcms_photo_info_box_top; ?>px;">

    <div id="nav-image">
		  <div id="nav-image-previous">
		    <?php echo fsf_port_getPreviousImageCleanURL($fsfcms_current_image_id); ?>
		  </div> 
    &nbsp; 
		  <div id="nav-image-next">
		    <?php echo fsf_port_getNextImageCleanURL($fsfcms_current_image_id); ?>
		  </div>
    </div>  <!-- end nav-image -->

    <div id="image-caption">
        <?php echo $fsfcms_current_image_caption; ?>
    </div>
    <div id="image-data">
        <span class="upper">Title:</span>&nbsp;<h2 class="post-title"><a href="/<?php echo $fsfcms_image_link_clean_URL; ?>" title="Permanent link to: <?php echo $fsfcms_current_image_title; ?>"><?php echo $fsfcms_current_image_title; ?></a></h2><br />
        <span class="upper">Author:</span>&nbsp;<?php echo $fsfcms_current_image_authors_formatted; ?><br />        
        <span class="upper">Camera:</span>&nbsp;<a href="<?php echo "/cameras/" . $fsfcms_current_image_camera_slug . "\">" . $fsfcms_current_image_camera; ?></a><br />
        <?php
        $fsfcms_image_category_links = fsf_port_getImageCategories($fsfcms_current_image_id);
        if ($fsfcms_image_category_links['multipleCategories'] == "NO")
          {
          $fsfcms_image_category_plural = "Category";
          } else  {
          $fsfcms_image_category_plural = "Categories";
          } 
        ?>
        <span class="upper"><?php echo $fsfcms_image_category_plural; ?>:</span>&nbsp;<?php echo $fsfcms_image_category_links['categoriesWithLinks'] ?><br />
        <span class="upper">Posted:</span>&nbsp;<?php echo $fsfcms_current_image_posted_date_formatted /*. " " . $fsfcms_current_image_posted_time_formatted*/; ?>
    </div>
    <div id="image-short-link">
      <span class="upper">Short Link:</span>&nbsp;<?php echo fsf_port_getImageShortLink($fsfcms_current_image_id); ?>
    </div>
    <div id="image-keywords">
      <span class="upper">Keywords:</span><br />
      <span class="italic"><?php echo fsf_port_getImageKeywords($fsfcms_current_image_id); ?></span>
    </div>
    </div>  <!-- end photo-info-box -->
		</div>  <!-- end photographs -->
		</div>  <!-- end photographs-border -->
		</article>
      <?php echo fsfcms_getAnnouncements_mostRecent(); ?>	
      <footer>
		    <div id="footer">
		      <nav>
		        <p class="navigation">
              Bored? Browse the <a href="/archives">archives</a>, view the latest images from various <a href="/authors">authors</a>, check out photographs taken with different <a href="/cameras">cameras</a>, see the newest images organized into <a href="/categories">categories</a>, explore sundry <a href="/keywords">keywords</a>, or see where pictures were taken on a <a href="/map">map</a>. Like physical objects? Order some <a href="/publications/">zines</a>. 
            </p>
            <p class="navigation">
              Contact <?php echo fsfcms_getSiteTitle(); ?> at <?php echo $fsfcms_site_phone ?> or <?php echo $fsfcms_site_email ?>. 
              <!--  Generous? <a href="https://shop.the-impossible-project.com/wishlist/75277">Buy</a> me some film.  -->
            </p>
          </nav>
          <p class="feeds">
            Lazy? Subscribe using <a href="/feed/atom">Atom</a> or <a href="/feed/rss">RSS</a>.
          </p>
		      <p>
            <?php echo fsfcms_getSiteCopyright(); ?> <br />
            <!-- SLR 680 site design by Olen Daelhousen. -->
          </p>
		    </div>
      </footer>
    </div>  <!-- End Wrapper -->
  </body>
</html>