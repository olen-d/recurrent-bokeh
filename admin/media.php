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

$fsfcms_media_output      = "";
$fsfcms_media_list_json   = fsf_port_getMediaList();
$fsfcms_media_list        = json_decode($fsfcms_media_list_json,true);

foreach ($fsfcms_media_list as $fsfcms_media)
  {          
  $fsfcms_media_output    .=  "<div class=\"portfolio-media-item\"><div class=\"portfolio-media-text\"><div class=\"portfolio-media-delete\"><a href=\"/admin/portfolio/media/deleteMedia/" . $fsfcms_media['mediaId'] . "\" onclick=\"return confirmMediaDelete('" . $fsfcms_media['mediaManufacturer'] . " " . $fsfcms_media['mediaName']. " " . $fsfcms_media['mediaSpeed'] . "')\">delete</a></div><div class=\"portfolio-media-edit\"><a href=\"/admin/portfolio/media/editMedia/" . $fsfcms_media['mediaId'] . "\">edit</a></div><span class=\"portfolio-media-media-name\">" . $fsfcms_media['mediaManufacturer'] . " " . $fsfcms_media['mediaName']. " " . $fsfcms_media['mediaSpeed'] . "</span><br />" . 
                              "<span class=\"portfolio-media-meta\">Media ID:&nbsp;" . $fsfcms_media['mediaId'] . "</span><br />" .
                              "<p>" . $fsfcms_media['mediaType'] . "</p></div></div>";
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
  <script src="../javaScript/confirmMediaDelete.js"></script>

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
            require "media-menu.php";
      ?>
      <div id="page-content">    
        <?php
        if ($fsfcms_page_request_parts[3] == "newMedia")
          {
          require "portfolioNewMedia.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "addMedia") {
          require "portfolioAddMedia.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "editMedia") {
          require "portfolioEditMedia.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "updateMedia") {
          require "portfolioUpdateMedia.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "deleteMedia") {
          $fsfcms_media_id  = $fsfcms_page_request_parts[4];
          $fsfcms_media_delete_query = "DELETE FROM " . FSFCMS_MEDIA_TABLE . " WHERE id = " . $fsfcms_media_id . " LIMIT 1";
          if(mysql_query($fsfcms_media_delete_query))
            {                                   
            echo "Media successfully deleted.";
            } else  {
            echo "The media was not deleted";
            }
          exit;
          }
        ?>
        <div id="portfolio-media"> 
          <?php echo $fsfcms_media_output; ?>
        </div>
      </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->
  </body>
</html>