<?php

$fsfcms_this_page = "image_not_found";

?>

<html>
	<head>
		<title>
			Image Not Found &#149; <?php echo fsfcms_getSiteTitle(); ?>
		</title>
    <!-- CUSTOM FONTS -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Neuton:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>



		<!-- STYLES -->
    <link rel="stylesheet" href="/templates/taco-flavor/site-style.css" type="text/css" />
    
		<script language='javascript' type='text/javascript'>
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
		<div id="photographs-border">
		<div id="photographs">
      <img src="/fail/404_Image_Not_Found.jpg" title="Image Not Found" alt="Image Not Found" width="777px" height="800px" />
		<div id="photo-not-found-box">
    <div id="photo-not-found-information">
      Hi, I'm the brains behind this operation. I regret to inform you that I couldn't find the particular image you're looking for. It may have been moved, deleted, or set to post in the future. It's also possible you can't spell. Luckily, I have plenty of other images laying around for your perusal and enjoyment. Your options for exploring them include: start over at the <a href="/">home page</a>, browse SLR 680 beginning with the <a href="<?php echo fsf_port_getFirstImageCleanURL(); ?>">first photograph</a>, or visit the <a href="/archives">archives</a>.     
    </div>
    </div>  <!-- end photo-info-box -->
		</div>  <!-- end photographs -->
		</div>  <!-- end photographs-border -->
		
		<div id="footer">
		<p>
			<?php echo fsfcms_getSiteCopyright(); ?> <br />
      <!-- SLR 680 site design by Olen Daelhousen. -->
		</p>
		</div>
		</div>  <!-- End Wrapper -->
	</body>
</html>