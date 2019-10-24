<?php

if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
if (!$fsfcms_is_logged_in)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000654";
  exit;
  }

//  Set up useful items
$fsfcms_port_total_images     = fsfcms_getTotalImagesNumber();
$total_images_filtered    = $fsfcms_port_total_images; 
$total_page_request_parts = count($fsfcms_page_request_parts);
$page_request_path        = "/admin/portfolio";

// Check for filters
          
if(count($fsfcms_page_request_parts) > 4)
  {
  if($fsfcms_page_request_parts[2] == "filter")
    {
    $filter_id  = $fsfcms_page_request_parts[4];
    switch($fsfcms_page_request_parts[3])
      {
      case "category":
      $filter = "category";
      $total_images_filtered  = fsfcms_getTotalImagesNumberByCategory($filter_id);
      break;
      }
    $page_request_path      .=  "/filter/" . $filter . "/" . $filter_id;
    }
  } else  {
  $filter     = "";
  $filter_id  = "";
  }
?>
<html>
  <head>
    <title>
    <?php echo $fsfcms_this_page_title; ?>
    </title>

    <!--  STYLES          -->
    <link rel="stylesheet" href="/admin/admin-style.css" type="text/css" />
    <link rel="stylesheet" href="/admin/admin-color-schemes.css" type="text/css" />
      
    <!--  CUSTOM FONTS    -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Archivo+Narrow:400,700' rel='stylesheet' type='text/css'>

    <!--  JAVASCRIPT      -->
    <script>
    function confirmImageDelete(imageTitle)
      {
      return window.confirm("The image " + imageTitle + " will be deleted.")
      }
    </script>
    
  </head>
  <body>
    <div id="wrapper">
      <div id="title-bar">
        <h1>
          <?php echo $fsfcms_breadcrumbs; ?>
        </h1>        
      </div>
    <div id="author-bar">
      <p>
      Hola, <?php echo FSFCMS_AUTHOR_USER_NAME; ?>!<!-- REM Make the username a link to the profile -->     <a href="/admin/index.php?gc=logout">Log Out</a>   
      </p>
    </div>
    <?php require "top-menu.php";
          require "portfolio-menu.php";
    ?>
    <div id="page-content">    
    <!-- Remember to add JS and PHP validation and set required fields -->
    <?php
    if ($fsfcms_page_request_parts[2] == "newImage")
      {
      require "portfolioNewImage.php";
      exit;
      } elseif ($fsfcms_page_request_parts[2] == "editImage") {
      require "portfolioEditImage.php";
      exit;
      } elseif ($fsfcms_page_request_parts[2] == "uploadImage") {
      require "portfolioUploadImage.php";
      exit;
      } elseif ($fsfcms_page_request_parts[2] == "updateImage") {
      require "portfolioUpdateImage.php";
      exit;
      } elseif ($fsfcms_page_request_parts[2] == "deleteImage") {
        $fsfcms_image_id  = $fsfcms_page_request_parts[3];
        //  TODO  Delete micro thumbnail (when implemented)
        $fsfcms_delete_status = array();
        
        $fsfcms_api_options = array();
        $fsfcms_api_options['option']   = "portThumbsPath";
        $fsfcms_api_options['apiFile']  = "fsf.admin.getOption.php";

        $fsfcms_thumbs_path_json        = fsf_cms_accessAPI($fsfcms_api_options);
        $fsfcms_thumbs_path_array       = json_decode($fsfcms_thumbs_path_json,true);
        $fsfcms_thumbs_path             = $fsfcms_thumbs_path_array['option'];
  
        $fsfcms_api_options = array();
        $fsfcms_api_options['option']   = "portImagePath";
        $fsfcms_api_options['apiFile']  = "fsf.admin.getOption.php";

        $fsfcms_images_path_json        = fsf_cms_accessAPI($fsfcms_api_options);
        $fsfcms_images_path_array       = json_decode($fsfcms_images_path_json,true);
        $fsfcms_images_path             = $fsfcms_images_path_array['option'];

        $fsfcms_api_options = array();
        $fsfcms_api_options['imageId']  = $fsfcms_image_id;
        $fsfcms_api_options['apiFile']  = "fsf.port.getImage.php";
        
        $fsfcms_image_json              = fsf_cms_accessAPI($fsfcms_api_options);
        $fsfcms_image_array             = json_decode($fsfcms_image_json,true);
        $fsfcms_image_file_name         = $fsfcms_image_array['filename'];  

        $fsfcms_thumb_file                      = $fsfcms_thumbs_path . "thumb_" . $fsfcms_image_file_name;
        $fsfcms_delete_status['thumbnailFile']  = fsfcms_delete_file($fsfcms_thumb_file);

        $fsfcms_image_file                      = $fsfcms_images_path . $fsfcms_image_file_name;
        $fsfcms_delete_status['imageFile']      = fsfcms_delete_file($fsfcms_image_file);

        $fsfcms_image_authors_delete_query  = "DELETE FROM " . FSFCMS_AUTHORS_TABLE . " WHERE image_parent_id = " . $fsfcms_image_id;
        if(mysql_query($fsfcms_image_authors_delete_query))
          {
          $fsfcms_delete_status['authors']  = 200;
          } else  {
          $fsfcms_delete_status['authors']  = 500;
          }

        $fsfcms_image_categories_delete_query  = "DELETE FROM " . FSFCMS_CATEGORIES_TABLE . " WHERE parent_id = " . $fsfcms_image_id;
        if(mysql_query($fsfcms_image_categories_delete_query))
          {
          $fsfcms_delete_status['categories']  = 200;
          } else  {
          $fsfcms_delete_status['categories']  = 500;
          }

        $fsfcms_image_keywords_delete_query  = "DELETE FROM " . FSFCMS_KEYWORDS_TABLE . " WHERE image_parent_id = " . $fsfcms_image_id;
        if(mysql_query($fsfcms_image_keywords_delete_query))
          {
          $fsfcms_delete_status['keywords']  = 200;
          } else  {
          $fsfcms_delete_status['keywords']  = 500;
          }

        $fsfcms_image_delete_query  = "DELETE FROM " . FSFCMS_IMAGES_TABLE . " WHERE id = " . $fsfcms_image_id . " LIMIT 1";
        if(mysql_query($fsfcms_image_delete_query))
          {
          $fsfcms_delete_status['image']  = 200;
          } else  {
          $fsfcms_delete_status['image']  = 500;
          }        
      //  TODO: Make this a fancy error report, just print the array for now.
      print_r($fsfcms_delete_status);
      exit;
      }
    ?>
      <div id="portfolio-images-uploaded">
      <?php
         
        $fsfcms_port_images_per_page  = 5;
        
        $fsfcms_port_published_images = fsfcms_getPublishedImagesNumber();
        $fsfcms_port_published_images_formatted = number_format($fsfcms_port_published_images);
        if($fsfcms_port_total_images != 1)
          {
          $fsfcms_port_image_plural = "images";
          $fsfcms_port_image_tobe   = "are";
          } else  {
          $fsfcms_port_image_plural = "image";
          $fsfcms_port_image_tobe   = "is";
          }
        //  Determine pagination
        $page_index = $total_page_request_parts - 2;
        if($fsfcms_page_request_parts[$page_index] == "page")
          {
          $page_number_index  = $total_page_request_parts - 1;
          $fsfcms_port_page   = $fsfcms_page_request_parts[$page_number_index];
          } else  {
          $fsfcms_port_page = 1;
          }
         
        $fsfcms_port_most_recent_image_json   = fsf_port_getImages("DESC", 1);
        $fsfcms_port_most_recent_image        = json_decode($fsfcms_port_most_recent_image_json,TRUE);
        $fsfcms_port_most_recent_image_posted = $fsfcms_port_most_recent_image[0]['postedDateLong'] . " at " . $fsfcms_port_most_recent_image[0]['postedTime12Hour']; 

          if($fsfcms_port_page < ($total_images_filtered/$fsfcms_port_images_per_page))
            {
            $fsfcms_port_prev_page_link = "<div class=\"portfolio-prev-page\"><a href=\"" . $page_request_path . "/page/" . ($fsfcms_port_page + 1) . "\">&laquo; previous images</a></div>";
            } else  {
            $fsfcms_port_prev_page_link = "";
            }
          if($fsfcms_port_page != 1)
            {
            $fsfcms_port_next_page_link = "<div class=\"portfolio-next-page\"><a href=\"" . $page_request_path . "/page/" . ($fsfcms_port_page - 1) . "\">next images &raquo;</a></div>";
            } else  {
            $fsfcms_port_next_page_link = "";
            }
          if($fsfcms_port_total_images > $fsfcms_port_published_images)
            {
            $fsfcms_port_queue_images = $fsfcms_port_total_images - $fsfcms_port_published_images;
            if($fsfcms_port_queue_images != 1)
              {
              $fsfcms_port_queue_image_plural = "images";
              $fsfcms_port_queue_image_tobe   = "are";
              } else  {
              $fsfcms_port_queue_image_plural = "image";
              $fsfcms_port_queue_image_tobe   = "is";
              }
            $fsfcms_port_queue_info = "There " . $fsfcms_port_queue_image_tobe . " " . number_format($fsfcms_port_queue_images) . " " . $fsfcms_port_queue_image_plural . " set to post in the future. ";
            $fsfcms_port_last_image_tobe  = "will be";
            } else  {
            $fsfcms_port_queue_info = "";
            $fsfcms_port_published_images_formatted = "all";
            $fsfcms_port_last_image_tobe  = "was";
            }

          
          echo "<div id=\"portfolio-info\"><p class=\"first\">There " . $fsfcms_port_image_tobe . " " . number_format($fsfcms_port_total_images) . " total " . $fsfcms_port_image_plural . " in the database and " . $fsfcms_port_published_images_formatted . " of them have been published. " . $fsfcms_port_queue_info . "The last image " . $fsfcms_port_last_image_tobe . " posted on " . $fsfcms_port_most_recent_image_posted . ". </p></div>";
          echo "<div id=\"edit-specific-image\">Enter the identification number or permanent link of a specific image to edit:&nbsp;<form action=\"/admin/portfolio/editImage/getImage\" method=\"post\"><input type=\"text\" name=\"edit-specific-image-value\" id=\"edit-specific-image-value\" width = \"10\" />&nbsp;<input type=\"submit\" value=\"Edit Image\" /></form>";
          echo "</div>";
          echo "<div id=\"filter-portfolio\">Filter&nbsp;by " . fsfcms_categoriesDropdown("/admin/portfolio/filter/category");
          echo "</div>";
          echo "<div class=\"portfolio-paged-nav\">" . $fsfcms_port_prev_page_link;
          echo $fsfcms_port_next_page_link . "</div><!-- End Portfolio Paged Nav -->";

        $fsfcms_get_images_all_data_json  = fsfcms_get_images_all_data($fsfcms_port_page,$fsfcms_port_images_per_page,$filter,$filter_id);
        $fsfcms_get_images_all_data       = json_decode($fsfcms_get_images_all_data_json,TRUE);
        ?>


                                                                                
        <?php
        //  Set up some key variables for the image display loop
        $fsfcms_port_server_timezone                   =  fsfcms_getServerTimeZone();
        $fsfcms_port_server_timezone_off               =  $fsfcms_port_server_timezone['serverTimeZoneOffset'];

        foreach($fsfcms_get_images_all_data as $fsfcms_get_image_all_data)
          {
          $fsfcms_port_image_thumb_json = fsf_port_getImageThumbnailByID($fsfcms_get_image_all_data['id']);
          $fsfcms_port_image_thumb      = json_decode($fsfcms_port_image_thumb_json,TRUE);
          ?>
          <div class="portfolio-images-uploaded-item">  
            <div class="portfolio-thumb-container" style="width:<?php echo $fsfcms_port_image_thumb['thumbnailWidth']; ?>px;">          
              <div class="portfolio-thumb-container-image">
                <img src="<?php echo $fsfcms_port_image_thumb['thumbnailURL']; ?>" width="<?php echo $fsfcms_port_image_thumb['thumbnailWidth']; ?>" height="<?php echo $fsfcms_port_image_thumb['thumbnailHeight']; ?>" />
                <p class="portfolio-image-id">
                  <?php echo str_pad($fsfcms_get_image_all_data['id'],4,"0",STR_PAD_LEFT); ?>
                </p>
              </div>
              <div class="portfolio-image-file-meta">
                <div class="portfolio-image-file-meta-col2">
                  <?php echo $fsfcms_get_image_all_data['filename']; ?><br />
                  <?php echo round($fsfcms_get_image_all_data['imageFileSize'] / 1024, 0); ?>&nbsp;KB<br />
                  <?php echo $fsfcms_get_image_all_data['width']; ?>&nbsp;x&nbsp;<?php echo $fsfcms_get_image_all_data['height']; ?>&nbsp;px<br />
                </div>
                <div class="portfolio-image-file-meta-col1">
                  File Name:<br />
                  File Size:<br />
                  Image Size:<br /> 
                </div>
            </div>            <!--    End Portfolio Image File Meta     -->
          </div>              <!--    End Portfolio Thumb Container     -->
          <div class="portfolio-images-uploaded-text">
            <?php
            $fsfcms_port_image_UNIX_timestamp   = strtotime($fsfcms_get_image_all_data['postedDate']);
            $fsfcms_port_image_timestamp        = $fsfcms_port_image_UNIX_timestamp + $fsfcms_port_server_timezone_off * 3600;
            $fsfcms_port_image_postedDateLong   = date("l, F jS, Y",$fsfcms_port_image_timestamp);
            $fsfcms_port_image_postedTime12Hour = date("g:i a",$fsfcms_port_image_timestamp);
            ?>         
            <div class="portfolio-image-delete">
              <a href="/admin/portfolio/deleteImage/<?php echo $fsfcms_get_image_all_data['id']; ?>" onclick="return confirmImageDelete('" <?php echo $fsfcms_get_image_all_data['title']; ?>"')">delete</a>
            </div>
            <div class="portfolio-image-edit">
              <a href="/admin/portfolio/editImage/<?php echo $fsfcms_get_image_all_data['id']; ?>">edit</a>
            </div>
            <div class="portfolio-image-preview">
              <a href="/<?php echo date("Y/m/",$fsfcms_port_image_UNIX_timestamp) . $fsfcms_get_image_all_data['titleSlug']; ?>" target="_blank">preview</a>
            </div>
            <span class="images-uploaded-title"><?php echo $fsfcms_get_image_all_data['title']; ?></span><br />
            <span class="images-uploaded-meta"><?php echo $fsfcms_port_image_postedDateLong; ?>&nbsp;at&nbsp;<?php echo $fsfcms_port_image_postedTime12Hour; ?></span><br />
            <span class="images-uploaded-meta"><?php echo $fsfcms_get_image_all_data['authorFirstName']; ?>&nbsp;<?php echo $fsfcms_get_image_all_data['authorLastName']; ?></span>
            <p>
              <?php echo $fsfcms_get_image_all_data['caption']; ?>
            </p>
            <p>
              <span class="images-uploaded-meta"><?php echo $fsfcms_get_image_all_data['cameraManufacturer']; ?>&nbsp;<?php echo  $fsfcms_get_image_all_data['cameraName']; ?></span><br />
              <span class="images-uploaded-meta"><?php echo $fsfcms_get_image_all_data['mediaName']; ?>
            </p>
            <p class="portfolio-images-uploaded-meta-categories">
              Categories:&nbsp<span class="italicize"><?php echo str_replace(",", ", ", $fsfcms_get_image_all_data['imageCategories']); ?></span>
            </p>
            <p class="portfolio-images-uploaded-meta-keywords">
              Keywords:&nbsp<span class="italicize"><?php echo str_replace("_", " ", str_replace(",", ", ", $fsfcms_get_image_all_data['imageKeywords'])); ?></span>
            </p>
          </div>        <!--  End Portfolio Images Uploaded Text  -->
        </div>          <!--  End Portfolio Images Uploaded Item  -->
          <?php
          }             //  End Image Display Loop
          ?>
        <div class="portfolio-paged-nav">
          <?php echo $fsfcms_port_prev_page_link; echo $fsfcms_port_next_page_link; ?>
        </div>
      </div>            <!-- End Portfolio Images Uploaded  -->
    </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->
  </body>
</html>