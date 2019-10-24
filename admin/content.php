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

  // Check to see if a file is being uploaded
  if ($fsfp_ground_control == "contentHeaderNewPost")
    {
      // Get the info from the form
      // REMEMBER to validate all this crap and prevent SQL injections and what not
      $fsfcms_header_page       = $_POST['page'];
      $fsfcms_header_content  = trim($_POST['headerContent']);
      
      // Set up the timestamps. Note: Everything is stored in UTC and displayed according to the user set time zone.
      
      $fsfcms_header_content_uploaded_datetime = date("Y-m-d H:i:s", time());      
      $fsfcms_header_content_post_datetime     = date("Y-m-d H:i:s", time());  // Remember to make this user selectable
            
      // Now insert all the associated data into the DB

      $fsfcms_header_content_insert_query = "INSERT INTO " . $fsfcms_header_content_table . 
                                            " (id, page, content, uploaded, post)
                                            VALUES
                                            ('', '" . $fsfcms_header_page . "', '" . $fsfcms_header_content . "', '" . 
                                            $fsfcms_header_content_uploaded_datetime . "', '" . $fsfcms_header_content_post_datetime . "')";
//echo "<p>" . $fsfcms_header_content_insert_query . "</p>";
      // REMEMBER TO ERROR TRAP THIS
      mysql_query($fsfcms_header_content_insert_query);
      $fsfcms_header_content_parent_id = mysql_insert_id();
      
      // REMEMBER TO ADD IN THE AUTHOR STUFF. TABLE FSF_CMS_CONTENT_AUTHORS. aLSO CHANGE FSF_CMS_AUTHORS TO FSF_CMS_IMAGE_AUTHORS
      }
      if ($fsfp_ground_control == "contentHeaderUpdate")
        {
        $contentHeader_id = $_GET['contentHeader_id'];
        if($contentHeader_id  !="")
          {
          $fsfcms_edit_header_content_id      = trim($contentHeader_id); 
          $fsfcms_edit_header_content_page    = $_POST['page'];
          $fsfcms_edit_header_content_content = trim($_POST['headerContent']);
          $fsfcms_edit_header_content_post_datetime     = date("Y-m-d H:i:s", time());  // Remember to make this user selectable
          $fsfcms_edit_header_content_update_query  = "UPDATE " . $fsfcms_header_content_table . " SET page='" . $fsfcms_edit_header_content_page . 
                                                      "', content='" . $fsfcms_edit_header_content_content . 
                                                      "', post='" . $fsfcms_edit_header_content_post_datetime . 
                                                      "' WHERE id = " . $fsfcms_edit_header_content_id . " LIMIT 1";
          mysql_query($fsfcms_edit_header_content_update_query);
          }        
        }
      if ($fsfp_ground_control == "contentHeaderDelete")
        {
        $contentHeader_id = $_GET['contentHeader_id'];
        if($contentHeader_id  !="")
          {
          $fsfcms_header_content_delete_query = "DELETE FROM " . $fsfcms_header_content_table .
                                                " WHERE id = " . $contentHeader_id . " LIMIT 1";
                                               // echo "<p>cheese ". $fsfcms_header_content_delete_query . "</p>";
          mysql_query($fsfcms_header_content_delete_query);
          // REMEMBER TO ERROR TRAP THIS 
          }
        }
    if ($fsfp_ground_control == "contentPages")
      {
      // Get the pages.
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
            require "content-menu.php";
      ?>
      <div id="page-content">    
<?php
if ($fsfcms_page_request_parts[2] == "announcements")
//
//
//  Announcements
//
//

  {
  $fsfcms_announcements_output  = "";
  $fsfcms_announcements_json    = fsf_cms_getAnnouncements("all");
  $fsfcms_announcements         = json_decode($fsfcms_announcements_json,true);

  $fsfcms_server_timezone                   =  fsfcms_getServerTimeZone();
  $fsfcms_server_timezone_off               =  $fsfcms_server_timezone['serverTimeZoneOffset'];

  $fsfcms_announcements_status  = array_pop($fsfcms_announcements);
  if ($fsfcms_announcements_status == 200)
    {
    foreach ($fsfcms_announcements as $fsfcms_announcement)
      {
      $fsfcms_posted_date_formatted     = date("l, F jS, Y \a\\t g:i a",$fsfcms_announcement['postedDate'] + $fsfcms_server_timezone_off * 3600);
      $fsfcms_expiration_date_formatted = date("l, F jS, Y \a\\t g:i a",$fsfcms_announcement['expirationDate'] + $fsfcms_server_timezone_off * 3600);          
      $fsfcms_announcements_output  .=  "<div class=\"content-announcements-item\"><div class=\"content-announcements-text\"><div class=\"content-announcements-delete\"><a href=\"/admin/content/deleteAnnouncement/" . $fsfcms_announcement['id'] . "\" onclick=\"return confirmAnnouncementDelete('" . $fsfcms_announcement['headline'] . "')\">delete</a></div><div class=\"content-announcements-edit\"><a href=\"/admin/content/editAnnouncement/" . $fsfcms_announcement['id'] . "\">edit</a></div><span class=\"content-announcements-headline\">" . $fsfcms_announcement['headline'] . "</span><br />" . 
                              "<span class=\"content-announcements-meta\">" . $fsfcms_posted_date_formatted . "</span><br />" .
                              "<span class=\"content-announcements-meta\">" . $fsfcms_announcement['authorFirstName'] . " " . $fsfcms_announcement['authorLastName'] . "</span><br />" .
                              "<span class=\"content-announcements-meta\">Announcement ID:&nbsp;" . $fsfcms_announcement['id'] . "</span><br />" .
                              "<p>" . $fsfcms_announcement['article'] . "</p><span class=\"content-announcements-meta\">Expires: " . $fsfcms_expiration_date_formatted . "</div></div>";
      }
    }  
  ?>
  <div id="content-announcements">
    <?php echo $fsfcms_announcements_output; ?>
  </div>
  <?php
  exit;
  } elseif  ($fsfcms_page_request_parts[2] == "newAnnouncement")  {
//
//
//  New Announcment
//
//
    $fsfcms_announcement_form_action       = "/admin/content/createAnnouncement";
    $fsfcms_announcement_form_submit_value = "Create Announcement";  

    $fsfcms_announcement_id = "";
    // Date and time setup
    $fsfcms_server_timezone                   =  fsfcms_getServerTimeZone();
    $fsfcms_server_timezone_off               =  $fsfcms_server_timezone['serverTimeZoneOffset'];

    // Set up the month array for the specific date dropdown
    $publish_on_month_dropdown = array();
    $publish_on_month_dropdown['01'] = "January";
    $publish_on_month_dropdown['02'] = "February";
    $publish_on_month_dropdown['03'] = "March";
    $publish_on_month_dropdown['04'] = "April";
    $publish_on_month_dropdown['05'] = "May";
    $publish_on_month_dropdown['06'] = "June";
    $publish_on_month_dropdown['07'] = "July";
    $publish_on_month_dropdown['08'] = "August";
    $publish_on_month_dropdown['09'] = "September";
    $publish_on_month_dropdown['10'] = "October";
    $publish_on_month_dropdown['11'] = "November";
    $publish_on_month_dropdown['12'] = "December";

    // Set up the announcement publication date
    $publish_on_now_value = time() + $fsfcms_server_timezone_off * 3600;
    $publish_on_month_current_month = date("m", $publish_on_now_value);
    $publish_on_day_current_day = date("d", $publish_on_now_value);
    $publish_on_year_current_year = date("Y", $publish_on_now_value);
    $publish_on_hour_current_hour = date("H", $publish_on_now_value);
    $publish_on_minute_current_minute = date("i", $publish_on_now_value);
    $publish_on_second_current_second = date("s", $publish_on_now_value);

    //  Set up the announcement expiration date
    $announcement_expiration_value = $publish_on_now_value + 1209600;
    $announcement_expiration_month = date("m", $announcement_expiration_value);
    $announcement_expiration_day = date("d", $announcement_expiration_value);
    $announcement_expiration_year = date("Y", $announcement_expiration_value);
    $announcement_expiration_hour = date("H", $announcement_expiration_value);
    $announcement_expiration_minute = date("i", $announcement_expiration_value);
    $announcement_expiration_second = date("s", $announcement_expiration_value);
  
    // Build the list of authors and preselect the person currently logged in

    $fsfcms_announcement_authors_json = fsf_port_getAuthorsCleanURL();
    $fsfcms_announcement_authors      = json_decode($fsfcms_announcement_authors_json,true);
    $fsfcms_announcement_checkbox_id  = 0;

  foreach($fsfcms_announcement_authors as $fsfcms_announcement_author)
    {
    $fsfcms_announcement_authors_box_checked = "";
    (FSFCMS_USER_ID == $fsfcms_announcement_author['authorID'] ? $fsfcms_announcement_authors_box_checked = "checked=\"checked\"" : $fsfcms_announcement_authors_box_checked = "");
    $fsfcms_announcement_authors_output .= "<input type=\"checkbox\" name=\"announcementAuthors[]\" id=\"author" . $fsfcms_author_checkbox_id . "\" value=\"" . $fsfcms_announcement_author['authorID'] . "\" " . $fsfcms_announcement_authors_box_checked . "/><label for=\"author" . $fsfcms_announcement_checkbox_id . "\">" . $fsfcms_announcement_author['authorFirstName'] . "&nbsp;" . $fsfcms_announcement_author['authorLastName'] . "</label><br />";
    $fsfcms_announcement_checkbox_id++;
    }
  require FSFCMS_ADMIN_INCLUDE_PATH . "announcement-form.php";
  exit;
  } elseif  ($fsfcms_page_request_parts[2] == "createAnnouncement")  {
  $fsfp_announcement_created_unix_ts = time();

  //  Get the contents of the form
  require FSFCMS_ADMIN_INCLUDE_PATH . "announcement-form-post.php";

// Now insert all the associated data into the DB

$fsfp_insert_query  = "INSERT INTO " . FSFCMS_ANNOUNCEMENTS_TABLE . 
                      "(id, headline, article, post_date, expiration_date, created_date)
                      VALUES
                      ('', '" . $fsfp_announcement_headline . "', '" . $fsfp_announcement_article . "', " . $fsfp_announcement_post_unix_ts . ", " . 
                      $fsfp_announcement_expiration_unix_ts . ", " . $fsfp_announcement_created_unix_ts . ")";
                       
// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfp_insert_query);
$fsfp_announcement_parent_id = mysql_insert_id();

// Now perform the insert to the author table - the author defaults to whatever user is currently logged in
// REMEMBER TO FIGURE OUT [1] WHO CAN CHANGE AUTHORS AND [2] HOW TO IMPLEMENT THAT FUNCTIONALITY.
$fsfp_announcement_authors_insert_values = "";
if (count($fsfp_announcement_authors) < 1)
  {
  $fsfp_announcement_authors[] = $fsfcms_userID;   //  Default to the currently logged in author if no checkbox is selected
  }
foreach ($fsfp_announcement_authors as $fsfp_announcement_author)
  {
  $fsfp_announcement_author_trimmed = trim($fsfp_announcement_author);
  $fsfp_announcement_authors_insert_values .= "('', " . $fsfp_announcement_parent_id . ", " . $fsfp_announcement_author_trimmed . "),";
  }
        
// Important - drop the last comma off the string
$fsfp_announcement_authors_insert_values = rtrim($fsfp_announcement_authors_insert_values, ",");             
$fsfp_announcement_authors_query = "INSERT INTO " . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . " VALUES " . $fsfp_announcement_authors_insert_values;
mysql_query($fsfp_announcement_authors_query);
} elseif  ($fsfcms_page_request_parts[2] == "editAnnouncement")  {
//
//
//  Edit Announcement
//
//
  $fsfcms_announcement_id = $fsfcms_page_request_parts[3];
  $fsfcms_announcement_form_action       = "/admin/content/updateAnnouncement";
  $fsfcms_announcement_form_submit_value = "Update Announcement";  

  $fsfcms_announcement_json = fsf_cms_getAnnouncements($fsfcms_announcement_id);
  $fsfcms_announcement      = json_decode($fsfcms_announcement_json,true);

  // Date and time setup
  $fsfcms_server_timezone                   =  fsfcms_getServerTimeZone();
  $fsfcms_server_timezone_off               =  $fsfcms_server_timezone['serverTimeZoneOffset'];


  // Set up the month array for the specific date dropdown
  $publish_on_month_dropdown = array();
  $publish_on_month_dropdown['01'] = "January";
  $publish_on_month_dropdown['02'] = "February";
  $publish_on_month_dropdown['03'] = "March";
  $publish_on_month_dropdown['04'] = "April";
  $publish_on_month_dropdown['05'] = "May";
  $publish_on_month_dropdown['06'] = "June";
  $publish_on_month_dropdown['07'] = "July";
  $publish_on_month_dropdown['08'] = "August";
  $publish_on_month_dropdown['09'] = "September";
  $publish_on_month_dropdown['10'] = "October";
  $publish_on_month_dropdown['11'] = "November";
  $publish_on_month_dropdown['12'] = "December";

  // Set up the announcement publication date
  $publish_on_now_value = $fsfcms_announcement[0]['postedDate'] + $fsfcms_server_timezone_off * 3600;
  $publish_on_month_current_month = date("m", $publish_on_now_value);
  $publish_on_day_current_day = date("d", $publish_on_now_value);
  $publish_on_year_current_year = date("Y", $publish_on_now_value);
  $publish_on_hour_current_hour = date("H", $publish_on_now_value);
  $publish_on_minute_current_minute = date("i", $publish_on_now_value);
  $publish_on_second_current_second = date("s", $publish_on_now_value);

  //  Set up the announcement expiration date
  $announcement_expiration_value = $fsfcms_announcement[0]['expirationDate'] + $fsfcms_server_timezone_off * 3600;
  $announcement_expiration_month = date("m", $announcement_expiration_value);
  $announcement_expiration_day = date("d", $announcement_expiration_value);
  $announcement_expiration_year = date("Y", $announcement_expiration_value);
  $announcement_expiration_hour = date("H", $announcement_expiration_value);
  $announcement_expiration_minute = date("i", $announcement_expiration_value);
  $announcement_expiration_second = date("s", $announcement_expiration_value);
  
  //  Get the Headline
  $fsfcms_announcement_headline = trim($fsfcms_announcement[0]['headline']);
  
  //  Get the Article
  
  $fsfcms_announcement_article = trim($fsfcms_announcement[0]['article']);

  // Build the list of authors and preselect the person currently logged in

  $fsfcms_announcement_authors_json = fsf_port_getAuthorsCleanURL();
  $fsfcms_announcement_authors      = json_decode($fsfcms_announcement_authors_json,true);
  $fsfcms_announcement_checkbox_id  = 0;

  foreach($fsfcms_announcement_authors as $fsfcms_announcement_author)
    {
    $fsfcms_announcement_authors_box_checked = "";
    ($fsfcms_announcement[0]['authorId'] == $fsfcms_announcement_author['authorID'] ? $fsfcms_announcement_authors_box_checked = "checked=\"checked\"" : $fsfcms_announcement_authors_box_checked = "");
    $fsfcms_announcement_authors_output .= "<input type=\"checkbox\" name=\"announcementAuthors[]\" id=\"author" . $fsfcms_author_checkbox_id . "\" value=\"" . $fsfcms_announcement_author['authorID'] . "\" " . $fsfcms_announcement_authors_box_checked . "/><label for=\"author" . $fsfcms_announcement_checkbox_id . "\">" . $fsfcms_announcement_author['authorFirstName'] . "&nbsp;" . $fsfcms_announcement_author['authorLastName'] . "</label><br />";
    $fsfcms_announcement_checkbox_id++;
    }
  require FSFCMS_ADMIN_INCLUDE_PATH . "announcement-form.php";
  exit;
  } elseif  ($fsfcms_page_request_parts[2] == "updateAnnouncement")  {
  //  Get the contents of the form
  require FSFCMS_ADMIN_INCLUDE_PATH . "announcement-form-post.php";  

  //  Now update the DB

$fsfp_update_query  = "UPDATE " . FSFCMS_ANNOUNCEMENTS_TABLE . 
                      " SET headline='" . $fsfp_announcement_headline . 
                      "', article='" . $fsfp_announcement_article . 
                      "', post_date=" . $fsfp_announcement_post_unix_ts .
                      ", expiration_date=" . $fsfp_announcement_expiration_unix_ts . " WHERE id=" . $fsfp_announcement_id . " LIMIT 1";
                  
// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfp_update_query);


// Delete the old authors
$fsfcms_delete_authors_query = "DELETE FROM " . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . " WHERE announcement_parent_id ="  . $fsfp_announcement_id;
mysql_query($fsfcms_delete_authors_query);

// Now perform the insert to the author table - the author defaults to whatever user is currently logged in
// REMEMBER TO FIGURE OUT [1] WHO CAN CHANGE AUTHORS AND [2] HOW TO IMPLEMENT THAT FUNCTIONALITY.
$fsfp_announcement_authors_insert_values = "";
if (count($fsfp_announcement_authors) < 1)
  {
  $fsfp_announcement_authors[] = $fsfcms_userID;   //  Default to the currently logged in author if no checkbox is selected
  }
foreach ($fsfp_announcement_authors as $fsfp_announcement_author)
  {
  $fsfp_announcement_author_trimmed = trim($fsfp_announcement_author);
  $fsfp_announcement_authors_insert_values .= "('', " . $fsfp_announcement_id . ", " . $fsfp_announcement_author_trimmed . "),";
  }
        
// Important - drop the last comma off the string
$fsfp_announcement_authors_insert_values = rtrim($fsfp_announcement_authors_insert_values, ",");             
$fsfp_announcement_authors_query = "INSERT INTO " . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . " VALUES " . $fsfp_announcement_authors_insert_values;

mysql_query($fsfp_announcement_authors_query);
  }
  
if ($fsfcms_page_request_parts[2] == "publications")
  {
  require "publications.php";
  exit; 
  }
        switch($fsfp_ground_control)
          {
          case "contentHeaderNew":
          ?>
        <div id="image-form-container">
    <form name="contentHeaderNew" id="contentHeaderNew" method="post" enctype="multipart/form-data" action="content.php?gc=contentHeaderNewPost">
      <input type="hidden" name="page" value="all">
      <div class="form-field">
        <label for="headerContent">Header Content</label><br />
        <textarea name="headerContent" id="headerContent" rows="8" cols="80" />
        </textarea>
      </div>         
      <p class="submit">
        <input type="submit" class="button" name="submit" value="Post Content">
      </p>
    </form>
    </div>  
        <?php
        break;
        case "contentHeaderEdit":
          $contentHeader_id = $_GET['contentHeader_id'];
          if($contentHeader_id  !="")
            {
            $fsfcms_edit_header_content_query = "SELECT id, page, content, post FROM " . $fsfcms_header_content_table . " WHERE id = " . $contentHeader_id . " LIMIT 1";
            $fsfcms_edit_header_content_result = mysql_query($fsfcms_edit_header_content_query);
            $fsfcms_edit_header_content_row = mysql_fetch_row($fsfcms_edit_header_content_result);
            $fsfcms_edit_header_content_id      = trim($fsfcms_edit_header_content_row[0]); 
            $fsfcms_edit_header_content_page    = trim($fsfcms_edit_header_content_row[1]);
            $fsfcms_edit_header_content_content = trim($fsfcms_edit_header_content_row[2]);
            }
            ?>
        <div id="image-form-container">
    <form name="contentHeaderEdit" id="contentHeaderEdit" method="post" enctype="multipart/form-data" action="content.php?gc=contentHeaderUpdate&contentHeader_id=<?php echo $contentHeader_id; ?>">
      <input type="hidden" name="page" value="all">
      <div class="form-field">
        <label for="headerContent">Header Content</label><br />
        <textarea name="headerContent" id="headerContent" rows="8" cols="80" />
<?php echo $fsfcms_edit_header_content_content; ?>
        </textarea>
      </div>         
      <p class="submit">
        <input type="submit" class="button" name="submit" value="Update Content">
      </p>
    </form>
    </div>            
            <?php
          break;
      case "contentHeader":
      default:
        $fsfcms_header_content_query = "SELECT id, page, content, post FROM " . $fsfcms_header_content_table . " ORDER BY page DESC";
        $fsfcms_header_content_result = mysql_query($fsfcms_header_content_query);
        if(mysql_num_rows($fsfcms_header_content_result) > 0)
          {
          $fsfcms_header_content_output = "<div id=\"portfolio-images-container\"><table class=\"portfolio-images-table\"><tr class=\"portfolio-table-header\"><th>ID</th><th>Page</th><th>Content</th><th>Updated</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
          while($fsfcms_header_content_row = mysql_fetch_assoc($fsfcms_header_content_result))
            {
            $fsfcms_header_content_output .= "<tr>";
            $fsfcms_header_content_id     = $fsfcms_header_content_row['id'];
            $fsfcms_header_content_output .= "<td class=\"center\">" . $fsfcms_header_content_id . "</td>";
            $fsfcms_header_content_output .= "<td>" . $fsfcms_header_content_row['page'] . "</td>";
            $fsfcms_header_content_output .= "<td>" . trim($fsfcms_header_content_row['content']) . "</td>";
            $fsfcms_header_content_output .= "<td class=\"center\">" . date("m/d/Y<\b\\r />H:i",strtotime($fsfcms_header_content_row['post'])) . "</td>";
            $fsfcms_header_content_output .= "<td class=\"center\"><a href=\"/admin/content.php?gc=contentHeaderEdit&contentHeader_id=" . $fsfcms_header_content_id . "\">edit</a></td>";
            $fsfcms_header_content_output .= "<td class=\"center\"><a href=\"/admin/content.php?gc=contentHeaderDelete&contentHeader_id=" . $fsfcms_header_content_id . "\">delete</a></td>";
            $fsfcms_header_content_output .= "</tr>";
            }
          $fsfcms_header_content_output .= "</table></div>";
          echo $fsfcms_header_content_output;
          } else  {
             
      echo "No header content found. <a href=\"/admin/content.php?gc=contentHeaderNew\">Create new header content</a>.";
    
          }
    } // End Switch
    ?>
    </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->
  </body>
</html>

