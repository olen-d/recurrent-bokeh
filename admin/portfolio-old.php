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
        $fsfcms_port_total_images     = fsfcms_getTotalImagesNumber();
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
        if($fsfcms_page_request_parts[2] == "page")
          {
          $fsfcms_port_page = $fsfcms_page_request_parts[3];
          } else  {
          $fsfcms_port_page = 1;
          }
         
        $fsfcms_port_most_recent_image_json   = fsf_port_getImages("DESC", 1);
        $fsfcms_port_most_recent_image        = json_decode($fsfcms_port_most_recent_image_json,TRUE);
        $fsfcms_port_most_recent_image_posted = $fsfcms_port_most_recent_image[0]['postedDateLong'] . " at " . $fsfcms_port_most_recent_image[0]['postedTime12Hour']; 
          if($fsfcms_port_page < ($fsfcms_port_total_images/$fsfcms_port_images_per_page))
            {
            $fsfcms_port_prev_page_link = "<div class=\"portfolio-prev-page\"><a href=\"/admin/portfolio/page/" . ($fsfcms_port_page + 1) . "\">&laquo; previous images</a></div>";
            } else  {
            $fsfcms_port_prev_page_link = "";
            }
          if($fsfcms_port_page != 1)
            {
            $fsfcms_port_next_page_link = "<div class=\"portfolio-next-page\"><a href=\"/admin/portfolio/page/" . ($fsfcms_port_page - 1) . "\">next images &raquo;</a></div>";
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
          // check for filters
          
        if(count($fsfcms_page_request_parts) > 4)
          {
          if($fsfcms_page_request_parts[2] == "filter")
            {
            switch($fsfcms_page_request_parts[3])
              {
              case "category":
              $filter = "category";
              break;
              }
            $filter_id  = $fsfcms_page_request_parts[4];
            }
          } else  {
          $filter     = "";
          $filter_id  = "";
          }
        $fsfcms_get_images_all_data_json  = fsfcms_get_images_all_data($fsfcms_port_page,$fsfcms_port_images_per_page,$filter,$filter_id);
        $fsfcms_get_images_all_data       = json_decode($fsfcms_get_images_all_data_json,TRUE);

        foreach($fsfcms_get_images_all_data as $fsfcms_get_image_all_data)
          {
          $fsfcms_port_image_thumb_json = fsf_port_getImageThumbnailByID($fsfcms_get_image_all_data['id']);
          $fsfcms_port_image_thumb      = json_decode($fsfcms_port_image_thumb_json,TRUE);

          echo "<div class=\"portfolio-images-uploaded-item\">";
          echo "<div class=\"portfolio-thumb-container\" style=\"width:" . $fsfcms_port_image_thumb['thumbnailWidth']. "px;\">";          
          echo "<div class=\"portfolio-thumb-container-image\">";
          echo "<img src=\"" . $fsfcms_port_image_thumb['thumbnailURL'] . "\" width=\"" . $fsfcms_port_image_thumb['thumbnailWidth'] . "\" height=\"" . $fsfcms_port_image_thumb['thumbnailHeight']. "\" />";
          echo "<p class=\"portfolio-image-id\">" . str_pad($fsfcms_get_image_all_data['id'],4,"0",STR_PAD_LEFT) . "</p>";
          echo "</div>";
          echo "<div class=\"portfolio-image-file-meta\">";

          echo "<div class=\"portfolio-image-file-meta-col2\">";
          echo $fsfcms_get_image_all_data['filename'] . "<br />";
          echo round($fsfcms_get_image_all_data['imageFileSize'] / 1024, 0) . " KB<br />";
          echo $fsfcms_get_image_all_data['width'] . " x " . $fsfcms_get_image_all_data['height'] . " px<br />";
          echo "</div>";

          echo "<div class=\"portfolio-image-file-meta-col1\">";
          echo "File Name:<br />";
          echo "File Size:<br />";
          echo "Image Size:<br />"; 
          echo "</div>";

          echo "</div>  <!--  End Portfolio Image File Meta -->";
          echo "</div>  <!--  End Portfolio Thumb Container  -->";
          echo "<div class=\"portfolio-images-uploaded-text\">";

          $fsfcms_port_server_timezone                   =  fsfcms_getServerTimeZone();
          $fsfcms_port_server_timezone_off               =  $fsfcms_port_server_timezone['serverTimeZoneOffset'];

          $fsfcms_port_image_UNIX_timestamp = strtotime($fsfcms_get_image_all_data['postedDate']);
          $fsfcms_port_image_timestamp = $fsfcms_port_image_UNIX_timestamp + $fsfcms_port_server_timezone_off * 3600;
          
          echo "<div class=\"portfolio-image-delete\"><a href=\"/admin/portfolio/deleteImage/" . $fsfcms_get_image_all_data['id'] . "\" onclick=\"return confirmImageDelete('" . $fsfcms_get_image_all_data['title'] . "')\">delete</a></div>";
          echo "<div class=\"portfolio-image-edit\"><a href=\"/admin/portfolio/editImage/" . $fsfcms_get_image_all_data['id'] . "\">edit</a></div>";
          echo "<div class=\"portfolio-image-preview\"><a href=\"/" . date("Y/m/",$fsfcms_port_image_UNIX_timestamp) . $fsfcms_get_image_all_data['titleSlug'] . "\" target=\"_blank\">preview</a></div>";
          echo  "<span class=\"images-uploaded-title\">" . $fsfcms_get_image_all_data['title'] . "</span><br />";

          

          $fsfcms_port_image_postedDateLong              = date("l, F jS, Y",$fsfcms_port_image_timestamp);
          $fsfcms_port_image_postedTime12Hour            = date("g:i a",$fsfcms_port_image_timestamp);

          echo "<span class=\"images-uploaded-meta\">" . $fsfcms_port_image_postedDateLong . " at " . $fsfcms_port_image_postedTime12Hour . "</span><br />";
          echo "<span class=\"images-uploaded-meta\">" . $fsfcms_get_image_all_data['authorFirstName'] . " " . $fsfcms_get_image_all_data['authorLastName'] . "</span>";
          echo "<p>" . $fsfcms_get_image_all_data['caption'] . "</p>";
          echo "<p><span class=\"images-uploaded-meta\">" . $fsfcms_get_image_all_data['cameraManufacturer'] . " " . $fsfcms_get_image_all_data['cameraName'] . "</span><br />";
          echo "<span class=\"images-uploaded-meta\">" . $fsfcms_get_image_all_data['mediaName'] . "</p>";
          echo "<p class=\"portfolio-images-uploaded-meta-categories\">Categories:&nbsp<span class=\"italicize\">" . str_replace(",", ", ", $fsfcms_get_image_all_data['imageCategories']) . "</span></p>";
          echo "<p class=\"portfolio-images-uploaded-meta-keywords\">Keywords:&nbsp<span class=\"italicize\">" . str_replace("_", " ", str_replace(",", ", ", $fsfcms_get_image_all_data['imageKeywords'])) . "</span></p></div></div>";

          }
          echo "<div class=\"portfolio-paged-nav\">" . $fsfcms_port_prev_page_link;
          echo $fsfcms_port_next_page_link . "</div><!-- End Portfolio Paged Nav -->";

          ?>
          </div>    <!-- End Images Uploaded  -->
          <?php
          
/*
        $fsfcms_portfolio_images_query  = "SELECT " . $fsfcms_images_table . ".id, title, caption, manufacturer, model, post FROM " . $fsfcms_images_table . ", " . $fsfcms_cameras_table . " WHERE " . 
                                          $fsfcms_images_table . ".camera_id = " . $fsfcms_cameras_table . ".id ORDER BY post DESC";
        
        $fsfcms_portfolio_images_result = mysql_query($fsfcms_portfolio_images_query);
        $fsfcms_portfolio_images_output = "<div id=\"portfolio-images-container\"><table class=\"portfolio-images-table\"><tr class=\"portfolio-table-header\"><th>ID</th><th>Title</th><th>Caption</th><th>Camera</th><th>Published</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
        while($fsfcms_portfolio_images_row = mysql_fetch_assoc($fsfcms_portfolio_images_result))
          {
          $fsfcms_portfolio_images_output .= "<tr>";
          $fsfcms_portfolio_image_id      = $fsfcms_portfolio_images_row['id'];
          $fsfcms_portfolio_images_output .= "<td class=\"center\">" . $fsfcms_portfolio_image_id . "</td>";
          $fsfcms_portfolio_images_output .= "<td>" . $fsfcms_portfolio_images_row['title'] . "</td>";
          $fsfcms_portfolio_images_output .= "<td>" . trim($fsfcms_portfolio_images_row['caption']) . "</td>";
          $fsfcms_portfolio_images_output .= "<td>" . $fsfcms_portfolio_images_row['manufacturer'] . " " . $fsfcms_portfolio_images_row['model'] . "</td>";
          $fsfcms_portfolio_images_output .= "<td class=\"center\">" . date("m/d/Y<\b\\r />H:i",strtotime($fsfcms_portfolio_images_row['post'])) . "</td>";
          $fsfcms_portfolio_images_output .= "<td class=\"center\"><a href=\"/admin/portfolio.php?gc=portfolioEditImage&image_id=" . $fsfcms_portfolio_image_id . "\">edit</a></td>";
          $fsfcms_portfolio_images_output .= "<td class=\"center\"><a href=\"/admin/portfolio.php?gc=portfolioDeleteImage&image_id=" . $fsfcms_portfolio_image_id . "\">delete</a></td>";
          $fsfcms_portfolio_images_output .= "</tr>";
          }
        $fsfcms_portfolio_images_output .= "</table></div>";
        echo $fsfcms_portfolio_images_output;
 */
    
    ?>
    </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->
  </body>
</html>