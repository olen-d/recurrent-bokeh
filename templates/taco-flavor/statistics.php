<?php
  $fsfcms_this_page = "statistics"; 
  
  $fsfcms_server_time_zone  = fsfcms_getServerTimeZone();
  $fsfcms_server_time_zone_offset = $fsfcms_server_time_zone['serverTimeZoneOffset'];

  //  TODO: Split the charts out into microservices i.e. fsfms_chart_donut; fsfms_chart_pie; fsfms_chart_column; fsfcms_chart_bar; fsfms_chart_line
  //  TODO: Inputs are $chart_data and $options
  //  TODO: Consider how combo charts will work

  function drawDonut($chart_data,$max_slices,$color_scheme,$chart_title)
    {
    if(array_pop($chart_data) == 200)
      {
      $donut_result         = array();
      $donut_result['type'] = "donut";
      $total_attributes = 0;
      $total_items      = count($chart_data);
      
      $start_offset = 25;     //  First slice starts at 12:00
      $stroke_dashoffset  = $start_offset;
      $total_length = 0;
      $cx           = 36;
      $cy           = 36;
      $r            = 15.91549430918954;
      $ci           = 0;
      $stroke_width = 8; // 8
      $offset       = 12; //12
      $max_label_chars  = 11; // 11
      $donut_label_line_height  = 3;
      $max_slices_flag  = FALSE;

      if($total_items > $max_slices)
        {
        $max_slices_flag  = TRUE;
        $other_items  = array_splice($chart_data,$max_slices - 1);
        //ksort($other_items);
        foreach($other_items as $other_item)
          {
          $total_other_items +=  $other_item['v'];
          }        
        $total_attributes = $total_other_items;
        $donut_result['type']     = "combo";
        $donut_result['other']    = $other_items;
        }

      foreach($chart_data as $value)
        {
        $total_attributes +=   $value['v'];
        }

      if($max_slices_flag)
        {
        $chart_data[] = array(  "l" =>  "Others",
                                "v" =>  $total_other_items,
                                "s" =>  "others"
                                );      
        }

      foreach($chart_data as $value)
        {
        $stroke_length  = $value['v'] / $total_attributes * 100;
        $stroke_remain  = 100 - $stroke_length;
        $seperator_stroke_dashoffset = $stroke_dashoffset + 0.25;    
        $donut .=  "<circle class=\"donut-segment\" cx=\"" . $cx . "\" cy=\"". $cy . "\" r=\"" . $r . "\" fill=\"transparent\" stroke=\"" . $color_scheme[$ci] . "\" stroke-width=\"". $stroke_width . "\" stroke-dasharray=\"" . $stroke_length . " " . $stroke_remain . "\" stroke-dashoffset=\"" . $stroke_dashoffset . "\"></circle>";
        $donut .=  "<circle class=\"donut-seperator\" cx=\"" . $cx . "\" cy=\"". $cy . "\" r=\"" . $r . "\" fill=\"transparent\" stroke=\"#fff\" stroke-width=\"". $stroke_width . "\" stroke-dasharray=\"0.25 99.75\" stroke-dashoffset=\"" . $seperator_stroke_dashoffset . "\"></circle>";
        $label_length   =  $stroke_length / 2 + $total_length;
        $degs           = 360 * ($label_length/100) - 90;
        $label_x  = ($r + $offset) * cos(deg2rad($degs)) + $cx;
        $label_y  = ($r + $offset) * sin(deg2rad($degs)) + $cx;
        $anchor_value = "middle";
        $cur_name   = $value['l'];
        $cur_count  = $value['v'];
        $donut_label        = "";
        $cur_line           = "";
        $donut_label_text   = array($cur_count);
        $donut_label_words  = explode(" ",$cur_name);

        foreach($donut_label_words AS $donut_label_word)
          {       
          if(strlen($cur_line) < $max_label_chars)
            {
            $cur_line  .=  $donut_label_word . " ";
            } else  {
            array_push($donut_label_text, $cur_line);
            $cur_line   = $donut_label_word;
            }
          }
        array_push($donut_label_text, $cur_line);

        $total_lines        = count($donut_label_text);
        $label_dy           = $total_lines / 2 * $donut_label_line_height / 2 * -1;
        $label_dyi          = $donut_label_line_height;
      
        foreach($donut_label_text AS $dlt_value)
          {
          $donut_label .= "<tspan x=\"" . $label_x . "\" y=\"" . $label_y . "\" dy=\"". $label_dy . "\">" . $dlt_value . "</tspan>";
          $label_dy += $label_dyi;
          }

        $donut .= "<text x=\"" . $label_x . "\" y=\"" . $label_y . "\" text-anchor=\"" . $anchor_value . "\" class=\"donut-data-label\">" . $donut_label . "</text>";

        $total_length =  $total_length + $stroke_length;
        $stroke_dashoffset  =  100 - $total_length + $start_offset;
        $ci++;
        }
      $seperator_stroke_dashoffset  = $start_offset + 0.25;
      $donut .=  "<circle class=\"donut-seperator\" cx=\"" . $cx . "\" cy=\"". $cy . "\" r=\"" . $r . "\" fill=\"transparent\" stroke=\"#fff\" stroke-width=\"". $stroke_width . "\" stroke-dasharray=\"0.25 99.75\" stroke-dashoffset=\"" . $seperator_stroke_dashoffset . "\"></circle>";        



      $donut  .=  "<g class=\"donut-hole-text\">";
      $donut  .=  "<text x=\"50%\" y=\"50%\" transform=\"translate(0 1)\" class=\"donut-hole-number\">";
      $donut  .=  $total_items;
      $donut  .=  "</text>";
      $donut  .=  "<text x=\"50%\" y=\"50%\" transform=\"translate(0 5)\" class=\"donut-hole-label\">";
      $donut  .=  $chart_title;
      $donut  .=  "</text>";
      $donut  .=  "</g>";

      $donut_result['donut'] = $donut;

      if($max_slices_flag)
        {
        
        }
      } else  {
      // TODO:  Fail gracefully
      }
    return $donut_result;
    }      

  function drawDonutSegments($chart_data,$color_scheme)
    {
    $chart_data   = json_decode($chart_data,TRUE);
    $segments     = "";
    $start_offset = 25;     //  First slice starts at 12:00
    $stroke_dashoffset  = $start_offset;
    $total_length = 0;
    $cx           = 36;
    $cy           = 36;
    $r            = 15.91549430918954;
    $ci           = 0;
    $stroke_width = 8;
    $offset       = 12;
    $max_label_chars  = 11;
    $donut_label_line_height  = 3;
    $condensed_counts = $chart_data['condensedCounts'];
    $condensed_names  = $chart_data['condensedNames'];
    $total_attributes = $chart_data['totalAttributes'];
    //$segments = "doritos " . $total_attributes;
//    $segments = $chart_data['condensedCounts'];
    
    foreach($condensed_counts AS $value)
      {
      //$value  = $chart_data['condensedCounts'][$i];
      $stroke_length  = $value / $total_attributes * 100;
      $stroke_remain  = 100 - $stroke_length;
      $seperator_stroke_dashoffset = $stroke_dashoffset + 0.25;    
      $segments .=  "<circle class=\"donut-segment\" cx=\"" . $cx . "\" cy=\"". $cy . "\" r=\"" . $r . "\" fill=\"transparent\" stroke=\"" . $color_scheme[$ci] . "\" stroke-width=\"". $stroke_width . "\" stroke-dasharray=\"" . $stroke_length . " " . $stroke_remain . "\" stroke-dashoffset=\"" . $stroke_dashoffset . "\"></circle>";
      $segments .=  "<circle class=\"donut-seperator\" cx=\"" . $cx . "\" cy=\"". $cy . "\" r=\"" . $r . "\" fill=\"transparent\" stroke=\"#fff\" stroke-width=\"". $stroke_width . "\" stroke-dasharray=\"0.25 99.75\" stroke-dashoffset=\"" . $seperator_stroke_dashoffset . "\"></circle>";
      $label_length   =  $stroke_length / 2 + $total_length;
      $degs           = 360 * ($label_length/100) - 90;
      //$label_y = $label_y + 5;
      $label_x  = ($r + $offset) * cos(deg2rad($degs)) + $cx;
      $label_y  = ($r + $offset) * sin(deg2rad($degs)) + $cx;

      /*
      if ($label_x > $cx - 2 && $label_x < $cx + 2)
        {
        $label_y  = $label_y +2;
        }
      */
      $anchor_value = "middle";
      if($label_x - $cx < 0)
        {
        //$anchor_value = "end";
        //$label_x += -5;
        } else  {
        //$anchor_value = "start";
        //$label_x += 5;
        }
      $cur_name   = $condensed_names[$ci];
      $cur_count  = $condensed_counts[$ci];
      
      /*
      if(strlen($cur_name) + strlen($cur_count) > $max_label_chars)
        {
        $donut_label = $cur_name . "<tspan x=\"" . $label_x . "\" dy=\"3px\">(" . $cur_count . ")</tspan>";
        $label_y = $label_y - 1;
        } else  {
        $donut_label = $cur_name . " (" . $cur_count . ")";
        }
      */

      
      $donut_label        = "";
      $cur_line           = "";
      $donut_label_text   = array($cur_count);
      $donut_label_words  = explode(" ",$cur_name);  //echo "<p>Doritos:</p><pre>"; print_r($donut_label_words); echo "</pre>";
      foreach($donut_label_words AS $donut_label_word)
        {        //echo "<p>Cheeseburger: " . $donut_label_word . "</p>";
        if(strlen($cur_line) < $max_label_chars)
          {                   //echo "<p>Pumpkin pie: " . strlen($donut_label_word) . " " . . "</p>";
           $cur_line  .=  $donut_label_word . " ";    //echo "<p>Burritos: " . $cur_line . "</p>";
          } else  {
          array_push($donut_label_text, $cur_line);
          $cur_line   = $donut_label_word;      // echo "<p>It worked: " . $cur_line . "</p>";
          }
        }
      array_push($donut_label_text, $cur_line);
      //echo "<p>Tacos:</p><pre>"; print_r($donut_label_text); echo "</pre>";
      //echo "<p>Ninjas: " . $cur_line . "</p>";
      //echo "<p>Doritos:</p><pre>"; print_r($donut_label_text); echo "</pre>";
      $total_lines        = count($donut_label_text);
      $label_dy           = $total_lines / 2 * $donut_label_line_height / 2 * -1;
      $label_dyi          = $donut_label_line_height;
      
      foreach($donut_label_text AS $value)
        {
        $donut_label .= "<tspan x=\"" . $label_x . "\" y=\"" . $label_y . "\" dy=\"". $label_dy . "\">" . $value . "</tspan>";
        $label_dy += $label_dyi;
        }

      $segments .= "<text x=\"" . $label_x . "\" y=\"" . $label_y . "\" text-anchor=\"" . $anchor_value . "\" class=\"donut-data-label\">" . $donut_label . "</text>";

      $total_length =  $total_length + $stroke_length;
      $stroke_dashoffset  =  100 - $total_length + $start_offset;
      $ci++;
      }
    $seperator_stroke_dashoffset  = $start_offset + 0.25;
    $segments .=  "<circle class=\"donut-seperator\" cx=\"" . $cx . "\" cy=\"". $cy . "\" r=\"" . $r . "\" fill=\"transparent\" stroke=\"#fff\" stroke-width=\"". $stroke_width . "\" stroke-dasharray=\"0.25 99.75\" stroke-dashoffset=\"" . $seperator_stroke_dashoffset . "\"></circle>";        
 
    return $segments;
    } 

  function drawColumn($chart_data,$color_scheme,$chart_title)
    {                                           //print_r($chart_data);
    //$chart_data   = json_decode($chart_data,TRUE);
    //$column_color = "#cfcfcf";
    $counts = array();
    $column_color = $color_scheme[0];
    $columns = "";
    $chart_height = 72;
    $plot_area_top_margin = 15;
    $plot_area_width     = 62;
    $column_margin      = 1;

    foreach($chart_data as $value)
      {
      $total_attributes +=   $value['v'];
      $counts[] = $value['v'];  //  REM: Clean this up to just sum counts in the future.
      }

    $columns_total      = count($chart_data); 
    $column_width       = $plot_area_width / $columns_total - $column_margin;
    $data_max           = max($counts);
    $x_axis_offset       = 4;
    $y_scale            = ($chart_height - $plot_area_top_margin - $x_axis_offset) / $data_max;
    $column_x           = 0;
    $column_y           = 0;
    $column_middle_x     = 0;
    $col_data_point_text  = "";
    $value_y     = $chart_height - $x_axis_offset + 3.5;
    $label_y     = $chart_height - $x_axis_offset - 2;
    $col_label_point_y          = 1;
    $col_label_point_left_margin = 10;
    $col_label_point_x = $x_axis_offset - $col_label_point_left_margin;


    array_multisort($chart_data,SORT_ASC);
    foreach ($chart_data AS $value)
      {       
      $column_height  = $y_scale * $value['v'];
      $column_y       = $chart_height - $column_height - $x_axis_offset;

      $columns .= "<rect fill=\"" . $column_color . "\" x=\"" . $column_x . "\" y=\"" . $column_y . "\" width=\"" . $column_width . "\" height=\"" . $column_height . "\"></rect>";

      $col_data_value   = $value['v'];
      $col_data_label   = $value['l'];
      $value_x       = $column_x + $column_width / 2;
      $label_x       =  $value_x + 0.8;
      $value_anchor_value = "middle";
      $label_anchor_value = "left";
 
      $columns .= "<text x=\"" . $value_x . "\" y=\"" . $value_y . "\" text-anchor=\"" . $value_anchor_value . "\" class=\"column-data-label\">" . $col_data_value . "</text>";
      $columns .= "<text x=\"" . $label_x . "\" y=\"" . $label_y . "\" text-anchor=\"" . $label_anchor_value . "\" transform=\"rotate(270 " . $label_x . " " . $label_y . ")\" class=\"column-data-name\">" . $col_data_label . "</text>";

      $column_x += $column_width + $column_margin;    
      }
 
    return $columns;   
    }

//  Some cruft to get the first and last image dates. TODO: Change these into API calls

$fsfcms_first_image_URL               = ltrim(fsf_port_getFirstImageCleanURL(),"/");
$fsfcms_first_image_URL_parts         = explode("/",$fsfcms_first_image_URL);
$fsfcms_first_image_year_month        = $fsfcms_first_image_URL_parts[0] . $fsfcms_first_image_URL_parts [1];
$fsfcms_first_image_slug              = $fsfcms_first_image_URL_parts[2];
$first_image_parameters = array();
$first_image_parameters['lookup'] = "URL";
$first_image_parameters['yearMonth'] = $fsfcms_first_image_year_month;
$first_image_parameters['slug'] = $fsfcms_first_image_slug;
$fsfcms_first_image_json              = fsf_port_getImage($first_image_parameters);
$fsfcms_first_image                   = json_decode($fsfcms_first_image_json,TRUE);
        
$fsfcms_first_image_id                = $fsfcms_first_image['id'];
$fsfcms_first_image_title             = $fsfcms_first_image['title'];
$fsfcms_first_image_posted_date_long  = date("F Y",$fsfcms_first_image['postedDateUnixTimestamp']);

$fsfcms_last_image_json = fsf_port_getImage("");
$fsfcms_last_image      = json_decode($fsfcms_last_image_json,true);
$fsfcms_last_image_id                = $fsfcms_last_image['id'];
$fsfcms_last_image_title             = $fsfcms_last_image['title'];
$fsfcms_last_image_posted_date_long  = date("F Y",$fsfcms_last_image['postedDateUnixTimestamp']);
$fsfcms_last_image_link_json = fsf_port_getImageLinkCleanURL($fsfcms_last_image_id);
$fsfcms_last_image_link       = json_decode($fsfcms_last_image_link_json,true);
$fsfcms_last_image_URL = $fsfcms_last_image_link['imageLink'];

?>
<?php
if(!@include "declarations.php")
  {
  echo "<html>";
	echo "<head>";
	}
?>
		<title>
			Statistics &#149; <?php echo fsfcms_getSiteTitle(); ?>
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
		<div id="stats-container">
		  <div id="stats-pages">
		    <?php
          echo fsf_cms_getStatsPages_dropDown();        
        ?>
		  </div>
		  <?php
      if(count($fsfcms_page_request_parts) > 1)
        {
        $fsfcms_page_slug = $fsfcms_page_request_parts[1];
        }
        switch($fsfcms_page_slug)
          {
          case "archives":
		        echo fsf_cms_getArchiveStats($fsfcms_server_time_zone_offset);
		        break;
          case "cameras":
		        echo fsf_cms_getCameraStats("");
		        break;
          default:
          case "categories":
            include $_SERVER['DOCUMENT_ROOT'] . "/chart_circular.php"; 

            $chart_data             = fsf_cms_getCameraStats("");
            $max_slices             = 5;
            $color_scheme           = array("#2222ee","#6666ee","#9999ee","#ddddff","#cfcfcf");
            ?>
           <div id="camerasContainer" style="padding-top:60px;margin-bottom:0px;background-color:#fff;">
              <div id="infoGraphicTitle">
                <h2><?php echo fsfcms_getTotalImagesNumber(); ?>&nbsp;Total Photographs Posted</h2>
                <p>
                <?php echo fsfcms_getSiteTitle(); ?> is a repository for Polaroid images posted between <?php echo $fsfcms_first_image_posted_date_long; ?> and <?php echo $fsfcms_last_image_posted_date_long; ?>. Following is a look at several statistics associated with the photographs. 
                </p> 
              </div>
              <div class="donutTitle">
                <h3 style="margin-bottom:0px;text-transform:none;">What cameras were the pictures taken with?</h3>
                              
              </div>
              <div class="donut-container">
              <svg width = "100%" height="100%" viewbox = "0 0 72 72">
                  <?php
                    $donut_result = drawDonut($chart_data,$max_slices,$color_scheme,"Cameras"); 
                    echo $donut_result['donut'];  
                  ?>
              </svg>
              </div>
            </div>  <!--  End Cameras Container  -->
            <?php
            $chart_data   = fsf_cms_getCategoryStats("");
            $max_slices   = 5;
            $color_scheme = array("#2222ee","#6666ee","#9999ee","#ddddff","#cfcfcf");
            ?>
            <div id="categoriesContainer" style="padding-top:60px;margin-bottom:0px;background-color:#fff;">
              <div class="comboTitle">
                <h3 style="margin-bottom:0px;text-transform:none;">How are the images categorized?</h3>
                              
              </div>
              <div class="donut-container">
              <svg width = "100%" height="100%" viewbox = "0 0 72 72">
                <!--<circle class="donut-hole" cx="36" cy="36" r="15.91549430918954" fill="#fff"></circle>  -->
                  <?php
                    $donut_result = drawDonut($chart_data,$max_slices,$color_scheme,"Categories"); 
                    echo $donut_result['donut'];
                  ?>
              </svg>
              </div>
              <div class="column-container">
                <svg width = "100%" height="100%" viewbox= "0 0 72 72">
                  <g class="columns">
                    <?php
                      if ($donut_result['type'] == "combo")
                        {
                        $chart_data = $donut_result['other'];
                        $color_scheme = array_splice($color_scheme,count($color_scheme) -1);
                        echo drawColumn($chart_data,$color_scheme,"Categories");
                        } 
                    ?>
                  </g>               
                </svg>
              </div> 
            <!--<div class="donutTitle" style="width:450px;float:left;">
            <h2 style="text-align:center;font-style:italic;">
              top categories
            </h2>
            </div>
            <div class="colTitle" style="width:400px;float:left">
            <h2 style="text-align:center;font-style:italic;">
              other categories
            </h2>            
            </div>
            <p style="clear:both;">
              Something about categories.
            </p>
            <div id="categoriesChart" style="background-color:#eeffff;">
            <canvas id="categoriesCanvas" width="900" height="300" style="border:none;">
              Your browser does not support the HTML5 canvas tag.
            </canvas>
            </div>  -->
            </div>  <!--  End Categories Container  -->
            <?  /*  echo "<div style=\"clear:left\"><p><pre>"; print_r(JSON_decode($chart_data,TRUE)); echo "</pre></p></div>"; */  ?>
    <!--  JAVASCRIPT    -->   
		<script type='text/javascript'>
			<!-- BEGIN
             // buildComboDonutCol(<?php echo fsf_cms_statsChartData($stats_data,$max_slices);?>,"categoriesCanvas","Categories",400);
			// End -->
		</script>
		        <?php
		        $fsfcms_media_stats_options['outputType'] = "nameOnly";
            $chart_data  = fsf_cms_getMediaStats($fsfcms_media_stats_options);
            $max_slices   = 5;
            $color_scheme = array("#2222ee","#6666ee","#9999ee","#ddddff","#cfcfcf");                       
            ?>
            <div id="mediaContainer" style="padding-top:60px;margin-bottom:0px;background-color:#fff;">
              <div class="donut-container">
              <svg width = "100%" height="100%" viewbox = "0 0 72 72">
                  <?php
                    $donut_result = drawDonut($chart_data,$max_slices,$color_scheme,"Media"); 
                    echo $donut_result['donut'];
                  ?> 
              </svg>
              </div>
              <div class="column-container">
                <svg width = "100%" height="100%" viewbox= "0 0 72 72">
                  <g class="columns">
                    <?php
                      $chart_data = $donut_result['other'];
                      $color_scheme = array_splice($color_scheme,count($color_scheme) -1);
                      echo drawColumn($chart_data,$color_scheme,"Media");
                    ?>
                  </g>               
                </svg>
              </div> 

            <!--
            <div id = "mediaContainer" style="padding-top:60px;background-color:#ddffdd;clear:left;";>
            <h2>
              media
            </h2>
            <div id ="mediaChart" style="background-color:#eeffee;"">
            <canvas id="mediaCanvas" width="900" height="300" style="border:none;">
              Your browser does not support the HTML5 canvas tag.
            </canvas>
            </div>
            </div>  -->
    <!--  JAVASCRIPT    -->   
		<script type='text/javascript'>
			<!-- BEGIN
             buildComboDonutCol(<?php echo fsf_cms_statsChartData($stats_data,$max_slices);?>,"mediaCanvas","Media",150);
			// End -->
		</script>                
            <?php 
		        //echo fsf_cms_processCategoryStats($fsfcms_category_stats);
		        break;
		      case "keywords":
		       
		        echo fsf_cms_getKeywordStats("");
		        break;
		      case "media":
		        $fsfcms_media_stats  = fsf_cms_getMediaStats("");
		        echo fsf_cms_processMediaStats($fsfcms_media_stats);
		        break;
		      }
      ?>
		</div>  <!-- end stats container -->	
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