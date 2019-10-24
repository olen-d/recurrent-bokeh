<?php
  $fsfcms_this_page = "statistics"; 
  
  $fsfcms_server_time_zone  = fsfcms_getServerTimeZone();
  $fsfcms_server_time_zone_offset = $fsfcms_server_time_zone['serverTimeZoneOffset']; 
?>
<?php
if(!@include "declarations.php")
  {
  echo "<html>";
	echo "<head>";
	}
?>
		<title>
			Map &#149; <?php echo fsfcms_getSiteTitle(); ?>
		</title>
    <!--  CUSTOM FONTS  -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Neuton:300,400' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css' />



		<!--  STYLES        -->
    <link rel="stylesheet" href="/templates/taco-flavor/site-style.css" type="text/css" />
    <link rel="stylesheet" href="/templates/taco-flavor/hexagons.css" type="text/css" /> 
    <!--  JAVASCRIPT    -->   
		<script type='text/javascript'>
			<!-- // BEGIN

      function changeHexTileColorOver(hexId)
        {
        document.getElementById(hexId).style.backgroundColor  = "#ff8800";
        }

      function changeHexTileColorOut(hexId)
        {
        document.getElementById(hexId).style.backgroundColor  = "transparent";
        }


      // set up a function to get the images for the state
      

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
		<div id="map-container">

		  <?php echo fsf_cms_hex_grid_us(); ?>

		</div>  <!-- end map container -->	
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

    <!--  JAVASCRIPT    -->   
		<script type='text/javascript'>
			<!-- // BEGIN
      
      //  Global Variables
      var thumbPage         = 1;
      var totalCalloutItems = -99;
      
      //  Add event listeners
        
      <?php echo fsf_cms_hex_grid_us_js_event_listeners() ?>

      //  Load general functions here
      //  Infinite scroll a callout box
      function infiniteScrollCallout(slug,calloutItems,calloutItemsContainer,calloutContainer)
        {
        if(calloutItemsContainer.scrollHeight - calloutItemsContainer.scrollTop < calloutItemsContainer.clientHeight + 250)
          {
          calloutItemsContainer.removeEventListener("scroll",fn,false);
          thumbPage++;
          getCalloutItems(slug,calloutItems,calloutItemsContainer,calloutContainer);
          }
        }

      //  Relative time REM: Send this to a common file and then load it
      //  Relative time
      function relativeTime(postedUnixTimestamp)
        {                          
        var minute  = 60,
            hour    = minute  * 60,
            day     = hour    * 24,
            month   = day     * 30,
            year    = day     * 365;

        var suffix  = "s";    
        var currentDate       = new Date();     
        var currentTimestamp  = currentDate.getTime();
        
        var elapsedSeconds    = currentTimestamp / 1000 - postedUnixTimestamp;
        
        if(elapsedSeconds > 0)
          {
          tense = "ago";
          } else  {
          tense = "in the future";
          elapsedSeconds = elapsedSeconds * -1;
          }
 
        if(elapsedSeconds < 60)
          {
          relativeTimeOutput  = "Just now";
          } else if(elapsedSeconds < hour) {
          timeIncrement       = "minute";
          relativeTimeOutput  = Math.floor(elapsedSeconds / minute);
          if (relativeTimeOutput > 1)
            {
            relativeTimeOutput += " " + timeIncrement + suffix + " " + tense;
            } else  {
            relativeTimeOutput += " " + timeIncrement + " " + tense;
            }
          } else if(elapsedSeconds < day) {
          timeIncrement       = "hour";
          relativeTimeOutput  = Math.floor(elapsedSeconds / hour);
          if (relativeTimeOutput > 1)
            {
            relativeTimeOutput += " " + timeIncrement + suffix + " " + tense;
            } else  {
            relativeTimeOutput += " " + timeIncrement + " " + tense;
            }
          } else if(elapsedSeconds < month) {
          timeIncrement       = "day";
          relativeTimeOutput  = Math.floor(elapsedSeconds / day);
          if (relativeTimeOutput > 1)
            {
            relativeTimeOutput += " " + timeIncrement + suffix + " " + tense;
            } else  {
            relativeTimeOutput += " " + timeIncrement + " " + tense;
            }
          } else if(elapsedSeconds < year) {
          timeIncrement       = "month";
          relativeTimeOutput  = Math.floor(elapsedSeconds / month);
          if (relativeTimeOutput > 1)
            {
            relativeTimeOutput += " " + timeIncrement + suffix + " " + tense;
            } else  {
            relativeTimeOutput += " " + timeIncrement + " " + tense;
            }
          } else  {
          timeIncrement       = "year";
          relativeTimeOutput  = Math.floor(elapsedSeconds / year);
          if (relativeTimeOutput > 1)
            {
            relativeTimeOutput += " " + timeIncrement + suffix + " " + tense;
            } else  {
            relativeTimeOutput += " " + timeIncrement + " " + tense;
            }
          }
        return relativeTimeOutput;
        }
 
        //  Get the state information
        function getStateBySlug(stateSlug)
        {
        var httpRequest;
        var hostURL         = window.location.host;
        var baseURL         = "http://" + hostURL + "/api/";
        var apiFile         = "fsf.cms.getStateBySlug.php";
        var apiOptions      = "?stateSlug=" + stateSlug;
        var apiString       = baseURL + apiFile + apiOptions;        

        httpRequest = new XMLHttpRequest();
        httpRequest.open("GET",apiString);
        httpRequest.send();

        httpRequest.onreadystatechange = function()
          {        
          if (this.readyState == 4 && this.status == 200)
            {
            var stateJSON = JSON.parse(httpRequest.responseText);  
            if (stateJSON.status == 200)
              {
              var calloutItemNameContainer        = document.getElementById("callout-item-name");
              calloutItemNameContainer.innerHTML  = "<h2><a href=\"/keywords/" + stateJSON.stateSlug + "\">" + stateJSON.stateName + "</a></h2>";
              }
            }          
          }     
        }

        //  Get the total number of items
        function getTotalItemsBySlug(slug)
        {
        var httpRequest;
        var hostURL                     = window.location.host;
        var baseURL                     = "http://" + hostURL + "/api/";
        var apiFile                     = "fsf.port.getImageThumbnailsByKeyword.php";
        var apiOptions                  = "?keywordSlug=" + slug;
        var apiString                   = baseURL + apiFile + apiOptions;         

        httpRequest = new XMLHttpRequest();
        httpRequest.open("GET",apiString);
        httpRequest.send();

        httpRequest.onreadystatechange = function()
          {        
          if (this.readyState == 4 && this.status == 200)
            {
            var itemsJSON = JSON.parse(httpRequest.responseText);  
            if (itemsJSON.status == 200)
              {
              var itemKeys          = Object.keys(itemsJSON);
              var itemCount         = (itemKeys.length) - 1;
              if  (itemCount > 1)
                {
                countDescription  = "photographs";
                } else  {
                countDescription  = "photograph";
                }
                var calloutItemCountContainer         = document.getElementById("callout-item-count");
                calloutItemCountContainer.textContent = itemCount + " " + countDescription;
                totalCalloutItems                     = itemCount;  //  Important - used later to determine if the last page has been reached. 
              }
            }          
          }     
        }     

      // Set up the container, output the item information, and get the first page of images
      
      function getHexTileThumbs(hexId,stateSlug)
        {
        var calloutHeight   = 653;
        var calloutTop      = 250;
        var viewportHeight  = window.innerHeight;
        var windowScrollY   = window.pageYOffset; 
                        
        //  If a callout is open, close it
        var calloutClosed = 1;
        try {
          var currentCallout = document.getElementById("callout-container");
          document.body.removeChild(currentCallout);                                                
          } catch(err)  {
          var calloutClosed = 0;
          }

          if(calloutClosed  != 0)
            {
            thumbPage = 1;  //  Reset the infinite scroll to page 1
            }

        //  Create the container div for the map callout and set up the divs
        var calloutContainer            = document.createElement("div");
        calloutContainer.id             = "callout-container";
        document.body.appendChild(calloutContainer);
        calloutContainer.innerHTML      = "<div id=\"callout-container-close\"><div id=\"cc-close\">CLOSE [X]</div></div><div id=\"callout-item-info\"><div id=\"callout-item-name\"></div><div id=\"callout-item-count\"></div></div><div id=\"callout-items-container\"><div id=\"callout-items\"></div></div>";

        //  Get the info for the selected state
        getStateBySlug(stateSlug);

        //  Get the total number of images
        getTotalItemsBySlug(stateSlug);

        var calloutItemCountContainer   = document.getElementById("callout-item-count");
        var calloutItemsContainer       = document.getElementById("callout-items-container");
        var calloutItems                = document.getElementById("callout-items");
              
        //  Set the location of the callout container
        var hexMiddle                   = document.getElementById("hex6").getBoundingClientRect();
        var hexMapMiddle                = hexMiddle.right;    
        var currentHex                  = document.getElementById(hexId);
        var currentHexRect              = currentHex.getBoundingClientRect();
        var currentHexRectBottom        = currentHexRect.bottom;

        /*alert(currentHexRect.top + " " + currentHexRect.right + " " +currentHexRect.bottom + " " +currentHexRect.left);  */
        /* alert(Math.round(currentHexRect.left));*/
                
        if(currentHexRect.left > hexMapMiddle)
          {
          calloutHorizontalPos  = currentHexRect.right  - 476 + "px"; 
          } else  {
          calloutHorizontalPos  = currentHexRect.left   + 76  + "px";
          }

        if (calloutHeight + calloutTop > viewportHeight)
          {
          if (calloutHeight > viewportHeight)
            {
            calloutTop = 40 + windowScrollY + "px";
            } else  {
            viewportVerticalMiddle  = viewportHeight / 2;
            calloutVerticalMiddle   = calloutHeight / 2;
            calloutTop = viewportVerticalMiddle - calloutVerticalMiddle + windowScrollY;
            calloutBottom = calloutHeight + calloutTop;
            if(calloutBottom < currentHexRectBottom)
              {
              diff = Math.ceil(currentHexRectBottom - calloutBottom);
              calloutTop += diff;
              }
            calloutTop += "px";
            }
          } else  {
          calloutTop  = calloutTop + "px";
          } 
        calloutContainer.style.left = calloutHorizontalPos;
        calloutContainer.style.top      = calloutTop;

        var closeCalloutContainerLink   = document.getElementById("cc-close");
        closeCalloutContainerLink.addEventListener("click",function(){document.body.removeChild(calloutContainer);thumbPage=1;},false);
        getCalloutItems(stateSlug,calloutItems,calloutItemsContainer,calloutContainer);
        }

function  getCalloutItems(slug,calloutItems,calloutItemsContainer,calloutContainer)
  {
  var pageSize  = 3;

  //  Check to see if we're on the last page and exit if so
  if  (totalCalloutItems  !=  -99)
    { 
    var totalPages = Math.ceil(totalCalloutItems / pageSize) 
    if (thumbPage > totalPages)
      {
      return;
      }
    }

  //  Get the list of images for the state in JSON format
  var httpRequest;
  var hostURL                     = window.location.host;
  var baseURL                     = "http://" + hostURL + "/api/";
  var apiFile                     = "fsf.port.getImageThumbnailsByKeyword.php";
  var apiOptions                  = "?keywordSlug=" + slug + "&items=" + pageSize + "&page=" + thumbPage;
  var apiString                   = baseURL + apiFile + apiOptions;        

  httpRequest = new XMLHttpRequest();
  httpRequest.open("GET",apiString);
  httpRequest.send();
  httpRequest.onreadystatechange = function()
    {                                          
    if (this.readyState == 4 && this.status == 200)
      {
      var thumbsJSON = JSON.parse(httpRequest.responseText);
      if (thumbsJSON.status == 200)
        {

        //  Process the JSON and build the thumbnails
        var countDescription    = "";
        var thumbsKeys          = Object.keys(thumbsJSON);
        var thumbsCount         = (thumbsKeys.length) - 1;
        var thumbsOutput        = "";
        var postedUnixTimeStamp = 0;

        for (i = 0; i < thumbsCount; i++)
          {
          postedUnixTimestamp   = thumbsJSON[i].postedDateUnixTimestamp;
          postedTime            = relativeTime(postedUnixTimestamp);
          thumbsOutput  +=  "<div class=\"thumbnails-border\">" +
                            "<div class=\"thumbnails-image\">" +
                            "<a href=\"" + thumbsJSON[i].imageLink + "\">" +
                            "<img src=\"" + thumbsJSON[i].thumbnailURL + "\" width=\"" + thumbsJSON[i].thumbnailWidth + "\" height =\"" +
                            thumbsJSON[i].thumbnailHeight + "\" alt=\"" +
                            thumbsJSON[i].title + "\" title=\"" +
                            thumbsJSON[i].title + "\" /></a></div></div>" +
                            "<div class=\"thumbnails-title\"><h3><a href=\"" +
                            thumbsJSON[i].imageLink + "\">" + 
                            thumbsJSON[i].title + 
                            "</a></h3></div>" +
                            "<div class=\"thumbnails-posted-date\">" +
                            postedTime + "</div>";
          }
        calloutItems.insertAdjacentHTML("beforeend",thumbsOutput);
        calloutContainer.style.display  = "block";
        calloutItemsContainer.addEventListener("scroll",fn=function(){infiniteScrollCallout(slug,calloutItems,calloutItemsContainer,calloutContainer);},false); 
        }
      }
    }  
  }

			// End -->
		</script>
	</body>
</html>